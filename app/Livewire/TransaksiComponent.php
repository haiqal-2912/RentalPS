<?php

namespace App\Livewire;

use App\Models\Playstation;
use App\Models\Transaksi;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class TransaksiComponent extends Component
{
    use WithPagination, WithoutUrlPagination;
    public $addPage, $lihatPage = false;
    public $nama, $alamat, $no_telp, $jaminan, $tanggal_Pemesanan, $Durasi_sewa, $playstation_id, $harga_sewa, $Total_harga, $status, $Metode_pembayaran;
    public $dataTransaksi = array();

    public function render()
    {
        $data['playstation'] = Playstation::paginate(5);
        return view('livewire.transaksi-component', $data);
    }
    public function create($id, $harga_sewa)
    {
        $this->harga_sewa = $harga_sewa;
        $this->playstation_id = $id;
        $this->addPage = true;
    }
    public function hitung()
    {
        $Durasi_sewa = $this->Durasi_sewa;
        $harga_sewa = $this->harga_sewa;
        $this->Total_harga = $Durasi_sewa * $harga_sewa;
    }
    public function store()
    {
        $this->validate([
            'nama' => 'required',
            'alamat' => 'required',
            'no_telp' => 'required',
            'jaminan' => 'required',
            'tanggal_Pemesanan' => 'required',
            'Durasi_sewa' => 'required',
            'Metode_pembayaran' => 'required'
        ], [
            'nama.required' => 'Nama Tidak Boleh Kosong!',
            'alamat.required' => 'Alamat Tidak Boleh Kosong!',
            'no_telp.required' => 'No Telepom Tidak Boleh Kosong!',
            'jaminan.required' => 'Jaminan Tidak Boleh Kosong!',
            'tanggal_Pemesanan.required' => 'Tanggal Tidak Boleh Kosong!',
            'Durasi_sewa.required' => 'Lama Sewa Tidak Boleh Kosong!',
            'Metode_pembayaran.required' => "Metode Pembayaran Tidak Boleh Kosong"
        ]);
        $cari = Transaksi::where('playstation_id', $this->playstation_id)
            ->where('status', '!=', 'kembali')
            ->count();


        if ($cari > 0) {
            session()->flash('error', 'PlayStation sudah dibooking!');
            return;
        }
        $cari = Transaksi::where('playstation_id', $this->playstation_id)
            ->where('tanggal_Pemesanan', $this->tanggal_Pemesanan)
            ->where('status', '!=', 'kembali')->count();
        if ($cari == 1) {
            session()->flash('error', 'Playstation Sudah Ada yang memesan!');
        } else {
            Transaksi::create([
                'user_id' => auth()->user()->id,
                'playstation_id' => $this->playstation_id,
                'nama' => $this->nama,
                'alamat' => $this->alamat,
                'no_telp' => $this->no_telp,
                'jaminan' => $this->jaminan,
                'tanggal_Pemesanan' => $this->tanggal_Pemesanan,
                'Durasi_sewa' => $this->Durasi_sewa,
                'Total_harga' => $this->Total_harga,
                'Metode_pembayaran' => $this->Metode_pembayaran,
                'status' => 'proses',
            ]);
            session()->flash('success', 'Transaksi berhasil disimpan');
        }
        $this->dispatch('lihat-transaksi');
        $this->reset();
    }
    public function lihat()
    {
        $this->dataTransaksi['transaksi'] = Transaksi::paginate(10);
        $this->lihatPage = true;
    }
}
