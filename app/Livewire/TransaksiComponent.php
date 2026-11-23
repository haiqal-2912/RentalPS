<?php

namespace App\Livewire;

use App\Models\Playstation;
use App\Models\Transaksi;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class TransaksiComponent extends Component
{
    use WithPagination, WithoutUrlPagination;
    public $addPage, $lihatPage = false;
    public $nama, $no_telp, $tanggal_Pemesanan, $playstation_id, $harga_sewa, $Total_harga, $Metode_pembayaran;
    public $jam_mulai = '', $durasi_jam = '';
    public $infoSlot;       // null | 'tersedia' | 'bentrok'
    public $dataTransaksi = array();
    public $bookingTerakhir; // id transaksi yang baru dibuat (untuk tombol kirim WhatsApp & nota)

    public function render()
    {
        $data['playstation'] = Playstation::paginate(5);

        // Penanda unit yang sedang dipakai SAAT INI (unit tetap bisa dibooking untuk jam lain)
        $sekarang = now();
        $data['terisiSekarang'] = $data['playstation']->getCollection()->pluck('id')
            ->filter(fn($id) => Transaksi::bentrok($id, $sekarang, $sekarang->copy()->addMinute())->exists())
            ->all();

        // Pastikan hanya booking milik user yang sedang login yang ditampilkan
        $data['booking'] = $this->bookingTerakhir
            ? Transaksi::with('playstation')->where('id', $this->bookingTerakhir)->where('user_id', auth()->id())->first()
            : null;

        // Pilihan jam mulai & durasi untuk form
        $tutup = (int) config('psone.jam_tutup', 22);
        $jamOpsi = $this->daftarJam($this->tanggal_Pemesanan);
        $maks = (int) config('psone.maks_durasi_jam', 8);
        if ($this->jam_mulai !== '' && $this->jam_mulai !== null) {
            $maks = min($maks, $tutup - (int) $this->jam_mulai);
        }
        $data['jamOpsi'] = $jamOpsi;
        $data['durasiOpsi'] = $maks >= 1 ? range(1, $maks) : [];
        $data['unitDipilih'] = $this->playstation_id ? Playstation::find($this->playstation_id) : null;

        return view('livewire.transaksi-component', $data);
    }

    /** Jam mulai yang bisa dipilih pada tanggal tertentu (jam yang sudah lewat tidak ikut). */
    protected function daftarJam($tanggal): array
    {
        $buka = (int) config('psone.jam_buka', 10);
        $tutup = (int) config('psone.jam_tutup', 22);
        if ($tutup <= $buka) {
            return [];
        }
        $jam = range($buka, $tutup - 1);
        if ($tanggal === now()->toDateString()) {
            $jam = array_values(array_filter($jam, fn($j) => $j >= now()->hour));
        }
        return $jam;
    }

    public function create($id)
    {
        $ps = Playstation::findOrFail($id);
        $this->reset(['nama', 'no_telp', 'jam_mulai', 'durasi_jam', 'Metode_pembayaran', 'Total_harga', 'infoSlot']);
        $this->resetValidation();
        $this->bookingTerakhir = null;
        $this->playstation_id = $ps->id;
        $this->harga_sewa = $ps->harga_sewa; // hanya untuk tampilan; total final dihitung ulang saat store()
        // Kalau jam operasional hari ini sudah habis, langsung arahkan ke besok
        $this->tanggal_Pemesanan = $this->daftarJam(now()->toDateString())
            ? now()->toDateString()
            : now()->addDay()->toDateString();
        // Pelanggan: isi otomatis dari akun. Kasir (pelanggan walk-in): isi manual.
        if (auth()->user()->role === 'pelanggan') {
            $this->nama = auth()->user()->name;
            $this->no_telp = auth()->user()->no_telp;
        }
        $this->addPage = true;
    }

    /** Dipanggil Livewire setiap tanggal/jam/durasi diubah. */
    public function updated($name)
    {
        if (!in_array($name, ['tanggal_Pemesanan', 'jam_mulai', 'durasi_jam'])) {
            return;
        }
        $tutup = (int) config('psone.jam_tutup', 22);

        // Jam yang sudah lewat tidak boleh dipilih untuk hari ini
        if ($name === 'tanggal_Pemesanan' && $this->tanggal_Pemesanan === now()->toDateString()
            && $this->jam_mulai !== '' && (int) $this->jam_mulai < now()->hour) {
            $this->jam_mulai = '';
        }
        // Durasi tidak boleh melewati jam tutup
        if ($this->jam_mulai !== '' && $this->durasi_jam !== '' && (int) $this->jam_mulai + (int) $this->durasi_jam > $tutup) {
            $this->durasi_jam = '';
        }
        $this->hitung();
    }

    public function hitung()
    {
        // Hanya untuk tampilan; total final dihitung ulang di server saat store()
        $this->Total_harga = (int) $this->durasi_jam * (int) $this->harga_sewa;

        $this->infoSlot = null;
        $rentang = $this->rentangWaktu();
        if ($rentang && $this->playstation_id) {
            [$mulai, $selesai] = $rentang;
            $this->infoSlot = Transaksi::bentrok($this->playstation_id, $mulai, $selesai)->exists() ? 'bentrok' : 'tersedia';
        }
    }

    /** @return array{0: Carbon, 1: Carbon}|null */
    protected function rentangWaktu(): ?array
    {
        if (!$this->tanggal_Pemesanan || $this->jam_mulai === '' || $this->jam_mulai === null || (int) $this->durasi_jam < 1) {
            return null;
        }
        try {
            $mulai = Carbon::createFromFormat('Y-m-d', $this->tanggal_Pemesanan)->setTime((int) $this->jam_mulai, 0, 0);
        } catch (\Throwable) {
            return null;
        }
        return [$mulai, $mulai->copy()->addHours((int) $this->durasi_jam)];
    }

    public function store()
    {
        $buka = (int) config('psone.jam_buka', 10);
        $tutup = (int) config('psone.jam_tutup', 22);
        $maks = (int) config('psone.maks_durasi_jam', 8);

        $this->validate([
            'nama' => 'required|max:100',
            'no_telp' => 'required|max:20',
            'tanggal_Pemesanan' => 'required|date_format:Y-m-d|after_or_equal:today',
            'jam_mulai' => 'required|integer|between:' . $buka . ',' . ($tutup - 1),
            'durasi_jam' => 'required|integer|min:1|max:' . $maks,
            'Metode_pembayaran' => 'required|in:Tunai,Transfer,E-wallet'
        ], [
            'nama.required' => 'Nama Tidak Boleh Kosong!',
            'no_telp.required' => 'No Telepon Tidak Boleh Kosong!',
            'tanggal_Pemesanan.required' => 'Tanggal Tidak Boleh Kosong!',
            'tanggal_Pemesanan.after_or_equal' => 'Tanggal tidak boleh sebelum hari ini!',
            'jam_mulai.required' => 'Jam Mulai Tidak Boleh Kosong!',
            'jam_mulai.between' => 'Jam mulai harus di antara jam operasional (' . sprintf('%02d:00', $buka) . ' - ' . sprintf('%02d:00', $tutup) . ')!',
            'durasi_jam.required' => 'Durasi Main Tidak Boleh Kosong!',
            'durasi_jam.min' => 'Durasi main minimal 1 jam!',
            'durasi_jam.max' => 'Durasi main maksimal ' . $maks . ' jam!',
            'Metode_pembayaran.required' => "Metode Pembayaran Tidak Boleh Kosong"
        ]);

        [$mulai, $selesai] = $this->rentangWaktu();

        if ($mulai->lt(now()->startOfHour())) {
            $this->addError('jam_mulai', 'Jam mulai sudah lewat!');
            return;
        }
        if ($selesai->gt($mulai->copy()->startOfDay()->addHours($tutup))) {
            $this->addError('durasi_jam', 'Waktu main melewati jam tutup (' . sprintf('%02d:00', $tutup) . ')!');
            return;
        }

        $ps = Playstation::findOrFail($this->playstation_id);

        try {
            $transaksi = DB::transaction(function () use ($ps, $mulai, $selesai) {
                // Kunci baris unit supaya dua pemesan tidak bisa mengambil jam yang sama
                Playstation::whereKey($ps->id)->lockForUpdate()->first();

                if (Transaksi::bentrok($ps->id, $mulai, $selesai)->exists()) {
                    throw new \RuntimeException('Jam tersebut sudah dibooking. Cek Jadwal Unit untuk jam yang masih kosong.');
                }

                return Transaksi::create([
                    'user_id' => auth()->user()->id,
                    'playstation_id' => $ps->id,
                    'nama' => $this->nama,
                    'no_telp' => $this->no_telp,
                    'tanggal_Pemesanan' => $mulai->toDateString(),
                    'waktu_mulai' => $mulai->toDateTimeString(),
                    'waktu_selesai' => $selesai->toDateTimeString(),
                    'durasi_jam' => (int) $this->durasi_jam,
                    'Total_harga' => (int) $this->durasi_jam * $ps->harga_sewa, // harga dari DB, bukan dari browser
                    'Metode_pembayaran' => $this->Metode_pembayaran,
                    'status' => 'proses',
                ]);
            });
        } catch (\RuntimeException $e) {
            session()->flash('error', $e->getMessage());
            return;
        }

        $this->dispatch('lihat-transaksi');
        $this->reset();
        $this->bookingTerakhir = $transaksi->id;
        session()->flash('success', 'Booking berhasil dibuat dan menunggu konfirmasi kasir.');
    }
    public function lihat()
    {
        $this->dataTransaksi['transaksi'] = Transaksi::paginate(10);
        $this->lihatPage = true;
    }
}
