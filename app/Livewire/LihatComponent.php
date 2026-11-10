<?php

namespace App\Livewire;

use App\Models\Transaksi;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;
use Livewire\Attributes\On;

class LihatComponent extends Component
{
    use WithPagination,WithoutUrlPagination;
    #[On('lihat-transaksi')]
    #[On('lihat-playstation')]
    public function render()
    {
        $data['transaksi'] = Transaksi::paginate(20);
        return view('livewire.lihat-component',$data);
    }
    public function proses($id)
    {
        $transaksi = Transaksi::find($id);
        $transaksi->update([
            'status' => 'pinjam'
        ]);
        session()->flash('success','Berhasil Proses Data');
    }
    public function selesai($id)
    {
        $transaksi = Transaksi::find($id);
        $transaksi->update([
            'status' => 'kembali'
        ]);
        session()->flash('success','Berhasil Proses Data');
    }
}
