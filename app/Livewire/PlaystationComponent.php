<?php

namespace App\Livewire;

use App\Livewire\Concerns\ChecksRole;
use App\Models\Playstation;
use App\Models\Transaksi;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class PlaystationComponent extends Component
{
    use WithPagination, WithoutUrlPagination, WithFileUploads, ChecksRole;
    protected $paginationTheme = 'bootstrap';
    public $addPage, $editPage = false;
    public $Type, $no_seri, $foto, $harga_sewa, $id;
    public function render()
    {
        if ($this->isKasir()) {
            $data['playstation'] = Playstation::paginate(10);
        } else {
            // Pelanggan hanya melihat unit yang kosong saat ini (tidak sedang dibooking / dipakai)
            $sekarang = now();
            $terisi = Transaksi::tumpang($sekarang, $sekarang->copy()->addMinute())->pluck('playstation_id');
            $data['playstation'] = Playstation::whereNotIn('id', $terisi)->paginate(10);
        }
        return view('livewire.playstation-component', $data);
    }
    public function create()
    {
        $this->hanyaKasir();
        $this->addPage = true;
    }
    public function store()
    {
        $this->hanyaKasir();
        // $this->reset();
        $this->validate([
            'Type' => 'required',
            'no_seri' => 'required',
            'foto' => 'required|image',
            'harga_sewa' => 'required|integer|min:0',
        ], [
            'Type.required' => 'Type Tidak Boleh Kosong!',
            'no_seri.required' => 'Serial Number Tidak Boleh Kosong!',
            'harga_sewa.required' => 'Harga per Jam Tidak Boleh Kosong!',
            'harga_sewa.integer' => 'Harga per Jam harus berupa angka!',
            'harga_sewa.min' => 'Harga per Jam tidak boleh negatif!',
            'foto.required' => 'Foto Tidak Boleh Kosong!',
            'foto.image' => 'Foto harus sesuai format'
        ]);

        $this->foto->storeAs('public/playstation', $this->foto->hashName());
        Playstation::create([
            'user_id' => auth()->user()->id,
            'Type' => $this->Type,
            'no_seri' => $this->no_seri,
            'foto' => $this->foto->hashName(),
            'harga_sewa' => $this->harga_sewa,
        ]);
        $this->dispatch('lihat-playstation');
        session()->flash('success', 'Data Berhasil Di Simpan');
        $this->reset();
    }
    public function edit($id)
    {
        $this->hanyaKasir();
        $this->reset();
        $this->editPage = true;
        $this->id = $id;
        $playstation = Playstation::find($id);
        $this->Type = $playstation->Type;
        $this->no_seri = $playstation->no_seri;
        $this->harga_sewa = $playstation->harga_sewa;
    }
    public function update()
    {
        $this->hanyaKasir();
        $playstation = Playstation::find($this->id);
        if (empty($this->foto)) {
            $playstation->update([
                'user_id' => auth()->user()->id,
                'Type' => $this->Type,
                'no_seri' => $this->no_seri,
                'harga_sewa' => $this->harga_sewa,
            ]);
        } else {
            unlink(public_path('storage/playstation/' . $playstation->foto));
            $this->foto->storeAs('public/playstation', $this->foto->hashName());
            $playstation->update([
                'user_id' => auth()->user()->id,
                'Type' => $this->Type,
                'no_seri' => $this->no_seri,
                'foto' => $this->foto->hashName(),
                'harga_sewa' => $this->harga_sewa,
            ]);
        }
        session()->flash('success', 'Berhasil Data diubah');
        $this->reset();
    }
    public function destroy($id)
    {
        $this->hanyaKasir();
        $playstation = Playstation::find($id);
        unlink(public_path('storage/playstation/' . $playstation->foto));
        $playstation->delete();
        session()->flash('success', 'Berhasil Dihapus');
        $this->reset();
    }
}
