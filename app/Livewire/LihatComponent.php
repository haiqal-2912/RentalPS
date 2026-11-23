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

    /** Konfirmasi 1-klik: slot terkunci untuk pelanggan tersebut. */
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
    /** Pelanggan sudah datang dan mulai main (dikonfirmasi -> main). */
    public function mulaiMain($id)
    {
        $this->hanyaKasir();
        $transaksi = Transaksi::findOrFail($id);
        if ($transaksi->status !== 'dikonfirmasi') {
            session()->flash('error','Booking harus dikonfirmasi dulu sebelum mulai main.');
            return;
        }
        $transaksi->update(['status' => 'main']);
        session()->flash('success','Sesi main dimulai');
    }
    /** Sesi main selesai (main -> selesai). Hanya yang selesai dihitung di laporan. */
    public function selesai($id)
    {
        $this->hanyaKasir();
        $transaksi = Transaksi::findOrFail($id);
        if ($transaksi->status !== 'main') {
            return;
        }
        $transaksi->update(['status' => 'selesai']);
        session()->flash('success','Sesi main selesai');
    }
    /** Kasir: boleh membatalkan booking proses/dikonfirmasi. Pelanggan: hanya booking miliknya yang masih proses. */
    public function batalkan($id)
    {
        $transaksi = Transaksi::findOrFail($id);
        if ($this->isKasir()) {
            $boleh = in_array($transaksi->status, ['proses', 'dikonfirmasi'], true);
        } elseif ($this->isPelanggan()) {
            $boleh = (int) $transaksi->user_id === (int) auth()->id() && $transaksi->status === 'proses';
        } else {
            abort(403);
        }
        if (!$boleh) {
            session()->flash('error','Booking ini tidak bisa dibatalkan.');
            return;
        }
        $transaksi->update(['status' => 'batal']);
        session()->flash('success','Booking '.$transaksi->nomor().' dibatalkan, slot dikosongkan');
    }
    public function destroy($id)
    {
        $this->hanyaKasir();
        Transaksi::findOrFail($id)->delete();
        session()->flash('success','Berhasil Hapus Data');
    }
}
