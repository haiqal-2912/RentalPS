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
        $query = Transaksi::with('playstation')->latest('id');
        // Pelanggan hanya melihat transaksinya sendiri
        if ($this->isPelanggan()) {
            $query->where('user_id', auth()->id());
        }
        $data['transaksi'] = $query->paginate(20);
        // Jumlah booking baru yang menunggu konfirmasi (untuk notifikasi kasir)
        $data['menunggu'] = $this->isKasir() ? Transaksi::where('status', 'proses')->count() : 0;
        return view('livewire.lihat-component',$data);
    }

    /** F-02 / ID-A2: konfirmasi 1-klik, slot terkunci untuk pelanggan tersebut. */
    public function konfirmasi($id)
    {
        $this->hanyaKasir();
        $transaksi = Transaksi::findOrFail($id);
        if ($transaksi->status !== 'proses') {
            return;
        }
        $transaksi->update(['status' => 'dikonfirmasi']);
        session()->flash('success','Booking '.$transaksi->nomor().' dikonfirmasi');
    }
    /** Unit diserahkan ke pelanggan (dikonfirmasi -> pinjam). */
    public function proses($id)
    {
        $this->hanyaKasir();
        $transaksi = Transaksi::findOrFail($id);
        if ($transaksi->status !== 'dikonfirmasi') {
            session()->flash('error','Booking harus dikonfirmasi dulu sebelum dipinjam.');
            return;
        }
        $transaksi->update([
            'status' => 'pinjam'
        ]);
        session()->flash('success','Berhasil Proses Data');
    }
    public function selesai($id)
    {
        $this->hanyaKasir();
        $transaksi = Transaksi::findOrFail($id);
        if ($transaksi->status !== 'pinjam') {
            return;
        }
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
