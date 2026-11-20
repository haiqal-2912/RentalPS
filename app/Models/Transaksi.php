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
    public function playstation()
    {
        return $this->belongsTo(Playstation::class);
    }
    public function users()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Transaksi aktif pada unit tertentu yang bentrok dengan rentang tanggal [mulai, mulai + durasi hari).
     * Unit yang berstatus "pinjam" tetapi sudah lewat tanggal kembali tetap dianggap terisi sampai
     * dikembalikan (sama seperti yang ditampilkan di Live Slot Board).
     */
    public function scopeBentrok($query, $playstationId, $mulai, $durasi, $kecualiId = null)
    {
        $mulai = Carbon::parse($mulai)->toDateString();
        $selesai = Carbon::parse($mulai)->addDays(max(1, (int) $durasi))->toDateString();
        $besok = Carbon::today()->addDay()->toDateString();

        $akhirEfektif = "CASE WHEN status = 'pinjam'
            THEN GREATEST(DATE_ADD(tanggal_Pemesanan, INTERVAL Durasi_sewa DAY), ?)
            ELSE DATE_ADD(tanggal_Pemesanan, INTERVAL Durasi_sewa DAY) END";

        return $query->where('playstation_id', $playstationId)
            ->where('status', '!=', 'kembali')
            ->where('tanggal_Pemesanan', '<', $selesai)
            ->whereRaw("($akhirEfektif) > ?", [$besok, $mulai])
            ->when($kecualiId, fn($q) => $q->where('id', '!=', $kecualiId));
    }

    public function nomor(): string
    {
        return 'PS-' . str_pad($this->id, 6, '0', STR_PAD_LEFT);
    }

    public function tanggalMulai(): Carbon
    {
        return Carbon::parse($this->tanggal_Pemesanan);
    }

    /** Hari terakhir sewa (inklusif). */
    public function tanggalAkhir(): Carbon
    {
        return $this->tanggalMulai()->copy()->addDays(max(1, (int) $this->Durasi_sewa) - 1);
    }

    /** Pesan booking terformat untuk dikirim pelanggan ke WhatsApp kasir (F-02 / ID-A1). */
    public function pesanWhatsapp(): string
    {
        $unit = $this->playstation ? $this->playstation->Type . ' (SN: ' . $this->playstation->no_seri . ')' : '-';
        return implode("\n", [
            '*BOOKING BARU - ' . strtoupper(config('psone.nama_usaha')) . '*',
            'No. Booking : ' . $this->nomor(),
            'Nama        : ' . $this->nama,
            'No. Telp    : ' . $this->no_telp,
            'Unit        : ' . $unit,
            'Mulai       : ' . $this->tanggalMulai()->format('d/m/Y'),
            'Sampai      : ' . $this->tanggalAkhir()->format('d/m/Y') . ' (' . $this->Durasi_sewa . ' hari)',
            'Total       : Rp ' . number_format((int) $this->Total_harga, 0, ',', '.'),
            'Pembayaran  : ' . $this->Metode_pembayaran,
            'Jaminan     : ' . strtoupper($this->jaminan),
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
            . ' untuk tanggal ' . $this->tanggalMulai()->format('d/m/Y') . ' (' . $this->Durasi_sewa . ' hari) sudah DIKONFIRMASI. Terima kasih!';
        return 'https://wa.me/' . $nomor . '?text=' . rawurlencode($pesan);
    }
}
