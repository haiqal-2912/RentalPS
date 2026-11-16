<?php

namespace App\Livewire;

use App\Livewire\Concerns\ChecksRole;
use App\Models\PesananMenu;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class PesananMenuComponent extends Component
{
    use WithPagination, WithoutUrlPagination, ChecksRole;
    protected $paginationTheme = 'bootstrap';

    public function render()
    {
        abort_unless($this->isKasir() || $this->isPelanggan(), 403);

        $query = PesananMenu::with(['users', 'detail.menu'])->latest('id');
        // Pelanggan hanya boleh melihat pesanannya sendiri
        if ($this->isPelanggan()) {
            $query->where('user_id', auth()->id());
        }
        $data['pesanan'] = $query->paginate(10);

        return view('livewire.pesanan-menu-component', $data);
    }

    public function ubahStatus($id, $statusBaru)
    {
        $this->hanyaKasir();
        abort_unless(in_array($statusBaru, ['proses', 'diantar', 'selesai'], true), 422);

        PesananMenu::findOrFail($id)->update(['status' => $statusBaru]);
        session()->flash('success', 'Status pesanan berhasil diubah');
    }

    public function destroy($id)
    {
        $this->hanyaKasir();

        DB::transaction(function () use ($id) {
            $pesanan = PesananMenu::with('detail')->lockForUpdate()->findOrFail($id);

            // Pesanan yang belum selesai dibatalkan: kembalikan stok menu
            if ($pesanan->status !== 'selesai') {
                foreach ($pesanan->detail as $d) {
                    DB::table('menu')->where('id', $d->menu_id)->increment('stok', $d->jumlah);
                }
            }
            $pesanan->delete(); // detail ikut terhapus (cascade)
        });

        session()->flash('success', 'Pesanan berhasil dihapus');
    }
}
