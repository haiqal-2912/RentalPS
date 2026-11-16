<?php

namespace App\Livewire;

use App\Livewire\Concerns\ChecksRole;
use App\Models\Transaksi;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;
use Livewire\Attributes\On;

class LihatComponent extends Component
{
    use WithPagination,WithoutUrlPagination, ChecksRole;
    #[On('lihat-transaksi')]
    #[On('lihat-playstation')]
    public function render()
    {
        $query = Transaksi::latest('id');
        // Pelanggan hanya melihat transaksinya sendiri
        if ($this->isPelanggan()) {
            $query->where('user_id', auth()->id());
        }
        $data['transaksi'] = $query->paginate(20);
        return view('livewire.lihat-component',$data);
    }
    public function proses($id)
    {
        $this->hanyaKasir();
        $transaksi = Transaksi::findOrFail($id);
        $transaksi->update([
            'status' => 'pinjam'
        ]);
        session()->flash('success','Berhasil Proses Data');
    }
    public function selesai($id)
    {
        $this->hanyaKasir();
        $transaksi = Transaksi::findOrFail($id);
        $transaksi->update([
            'status' => 'kembali'
        ]);
        session()->flash('success','Berhasil Proses Data');
    }
    public function destroy($id)
    {
        $this->hanyaKasir();
        Transaksi::findOrFail($id)->delete();
        session()->flash('success','Berhasil Hapus Data');
    }
}
