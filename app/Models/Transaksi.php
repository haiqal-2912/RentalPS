<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;
    protected $table = "transaksi";
    protected $guarded = ['id'];
    protected $casts = [
        'waktu_mulai' => 'datetime',
        'waktu_selesai' => 'datetime',
    ];

    /** Status yang sedang "memegang" slot waktu sebuah unit. */
    public const STATUS_AKTIF = ['proses', 'dikonfirmasi', 'main'];

    public function playstation()
    {
        return $this->belongsTo(Playstation::class);
    }
    public function users()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function scopeAktif($query)
    {
        return $query->whereIn('status', self::STATUS_AKTIF);
    }

    /**
     * Transaksi aktif (semua unit) yang waktunya beririsan dengan [mulai, selesai).
     * Pelanggan yang berstatus "main" tetapi melewati jam selesai tetap dianggap memakai unit
     * sampai kasir menandainya selesai (sama seperti di Live Slot Board).
     */
    public function scopeTumpang($query, Carbon $mulai, Carbon $selesai, $kecualiId = null)
    {
        $akhirEfektif = "CASE WHEN status = 'main'
            THEN GREATEST(waktu_selesai, CAST(? AS DATETIME))
            ELSE waktu_selesai END";

        return $query->aktif()
            ->where('waktu_mulai', '<', $selesai->toDateTimeString())
            ->whereRaw("($akhirEfektif) > ?", [now()->toDateTimeString(), $mulai->toDateTimeString()])
            ->when($kecualiId, fn($q) => $q->where('id', '!=', $kecualiId));
    }

    /** Sama seperti tumpang(), tetapi untuk satu unit. */
    public function scopeBentrok($query, $playstationId, Carbon $mulai, Carbon $selesai, $kecualiId = null)
    {
        return $query->where('playstation_id', $playstationId)->tumpang($mulai, $selesai, $kecualiId);
    }

    public function nomor(): string
    {
        return 'PS-' . str_pad($this->id, 6, '0', STR_PAD_LEFT);
    }

    /** Contoh: "14:00 - 16:00" */
    public function rentangJam(): string
    {
        if (!$this->waktu_mulai || !$this->waktu_selesai) {
            return '-';
        }
        return $this->waktu_mulai->format('H:i') . ' - ' . $this->waktu_selesai->format('H:i');
    }

    /** Pesan booking terformat untuk dikirim pelanggan ke WhatsApp kasir (F-02 / ID-A1). */
    public function pesanWhatsapp(): string
    {
        $unit = $this->playstation ? $this->playstation->Type . ' (SN: ' . $this->playstation->no_seri . ')' : '-';
        return implode("\n", [
            '*BOOKING MAIN - ' . strtoupper(config('psone.nama_usaha')) . '*',
            'No. Booking : ' . $this->nomor(),
            'Nama        : ' . $this->nama,
            'No. Telp    : ' . $this->no_telp,
            'Unit        : ' . $unit,
            'Tanggal     : ' . optional($this->waktu_mulai)->format('d/m/Y'),
            'Jam         : ' . $this->rentangJam() . ' (' . $this->durasi_jam . ' jam)',
            'Total       : Rp ' . number_format((int) $this->Total_harga, 0, ',', '.'),
            'Pembayaran  : ' . $this->Metode_pembayaran,
            '',
            'Mohon dikonfirmasi ya, terima kasih.',
        ]);
    }

    /** Link WhatsApp ke kasir; null jika nomor kasir belum diatur di .env. */
    public function waKasirUrl(): ?string
    {
        $nomor = preg_replace('/\D/', '', (string) config('psone.wa_kasir'));
        return $nomor ? 'https://wa.me/' . $nomor . '?text=' . rawurlencode($this->pesanWhatsapp()) : null;
    }

    /** Link WhatsApp kasir -> pelanggan untuk memberi kabar booking sudah dikonfirmasi. */
    public function waPelangganUrl(): ?string
    {
        $nomor = preg_replace('/\D/', '', (string) $this->no_telp);
        if ($nomor === '') {
            return null;
        }
        if (str_starts_with($nomor, '0')) {
            $nomor = '62' . substr($nomor, 1);
        } elseif (str_starts_with($nomor, '8')) {
            $nomor = '62' . $nomor;
        }
        $pesan = 'Halo ' . $this->nama . ', booking ' . $this->nomor() . ' di ' . config('psone.nama_usaha')
            . ' pada ' . optional($this->waktu_mulai)->format('d/m/Y') . ' jam ' . $this->rentangJam()
            . ' sudah DIKONFIRMASI. Kami tunggu kedatangannya!';
        return 'https://wa.me/' . $nomor . '?text=' . rawurlencode($pesan);
    }
}
