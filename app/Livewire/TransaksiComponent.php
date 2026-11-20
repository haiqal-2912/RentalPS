<?php

namespace App\Livewire;

use App\Models\Playstation;
use App\Models\Transaksi;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class TransaksiComponent extends Component
{
    use WithPagination, WithoutUrlPagination;
    public $addPage, $lihatPage = false;
    public $nama, $alamat, $no_telp, $jaminan, $tanggal_Pemesanan, $Durasi_sewa, $playstation_id, $harga_sewa, $Total_harga, $status, $Metode_pembayaran;
    public $dataTransaksi = array();
    public $bookingTerakhir; // id transaksi yang baru dibuat (untuk tombol kirim WhatsApp & nota)

    public function render()
    {
        $data['playstation'] = Playstation::paginate(5);

        // Unit yang terisi HARI INI (hanya penanda; unit tetap bisa dipesan untuk tanggal lain)
        $hariIni = now()->toDateString();
        $data['terisiHariIni'] = $data['playstation']->getCollection()->pluck('id')
            ->filter(fn($id) => Transaksi::bentrok($id, $hariIni, 1)->exists())
            ->all();

        // Pastikan hanya booking milik user yang sedang login yang ditampilkan
        $data['booking'] = $this->bookingTerakhir
            ? Transaksi::with('playstation')->where('id', $this->bookingTerakhir)->where('user_id', auth()->id())->first()
            : null;

        return view('livewire.transaksi-component', $data);
    }
    public function create($id, $harga_sewa)
    {
        $this->bookingTerakhir = null;
        $this->harga_sewa = $harga_sewa;
        $this->playstation_id = $id;
        $this->addPage = true;
    }
    public function hitung()
    {
        // Hanya untuk tampilan; total final dihitung ulang di server saat store()
        $this->Total_harga = (int) $this->Durasi_sewa * (int) $this->harga_sewa;
    }
    public function store()
    {
        $this->validate([
            'nama' => 'required',
            'alamat' => 'required',
            'no_telp' => 'required',
            'jaminan' => 'required|in:Ktp,Sim,Ijazah',
            'tanggal_Pemesanan' => 'required|date|after_or_equal:today',
            'Durasi_sewa' => 'required|integer|min:1|max:30',
            'Metode_pembayaran' => 'required|in:Tunai,Transfer,E-wallet'
        ], [
            'nama.required' => 'Nama Tidak Boleh Kosong!',
            'alamat.required' => 'Alamat Tidak Boleh Kosong!',
            'no_telp.required' => 'No Telepom Tidak Boleh Kosong!',
            'jaminan.required' => 'Jaminan Tidak Boleh Kosong!',
            'tanggal_Pemesanan.required' => 'Tanggal Tidak Boleh Kosong!',
            'tanggal_Pemesanan.after_or_equal' => 'Tanggal tidak boleh sebelum hari ini!',
            'Durasi_sewa.required' => 'Lama Sewa Tidak Boleh Kosong!',
            'Durasi_sewa.min' => 'Lama sewa minimal 1 hari!',
            'Durasi_sewa.max' => 'Lama sewa maksimal 30 hari!',
            'Metode_pembayaran.required' => "Metode Pembayaran Tidak Boleh Kosong"
        ]);

        $ps = Playstation::findOrFail($this->playstation_id);

        try {
            $transaksi = DB::transaction(function () use ($ps) {
                // Kunci baris unit supaya dua pemesan tidak bisa mengambil slot yang sama
                Playstation::whereKey($ps->id)->lockForUpdate()->first();

                if (Transaksi::bentrok($ps->id, $this->tanggal_Pemesanan, $this->Durasi_sewa)->exists()) {
                    throw new \RuntimeException('Unit sudah dibooking pada tanggal tersebut. Cek Jadwal Unit untuk tanggal yang masih kosong.');
                }

                return Transaksi::create([
                    'user_id' => auth()->user()->id,
                    'playstation_id' => $ps->id,
                    'nama' => $this->nama,
                    'alamat' => $this->alamat,
                    'no_telp' => $this->no_telp,
                    'jaminan' => $this->jaminan,
                    'tanggal_Pemesanan' => $this->tanggal_Pemesanan,
                    'Durasi_sewa' => $this->Durasi_sewa,
                    'Total_harga' => (int) $this->Durasi_sewa * $ps->harga_sewa, // harga dari DB, bukan dari browser
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