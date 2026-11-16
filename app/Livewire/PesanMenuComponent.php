<?php

namespace App\Livewire;

use App\Livewire\Concerns\ChecksRole;
use App\Models\DetailPesananMenu;
use App\Models\Menu;
use App\Models\PesananMenu;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class PesanMenuComponent extends Component
{
    use ChecksRole;

    public $kategori = '';            // filter: '', makanan, minuman
    public $keranjang = [];           // [menu_id => jumlah]
    public $metode_pembayaran = '';

    public function mount()
    {
        $this->hanyaPelanggan();
    }

    public function render()
    {
        $this->hanyaPelanggan();

        $query = Menu::tersedia()->orderBy('kategori')->orderBy('nama');
        if (in_array($this->kategori, ['makanan', 'minuman'], true)) {
            $query->where('kategori', $this->kategori);
        }
        $data['menu'] = $query->get();

        // Isi keranjang dibaca ulang dari DB agar harga/stok selalu terbaru
        $data['itemKeranjang'] = Menu::whereIn('id', array_keys($this->keranjang))->get()
            ->map(function ($m) {
                $m->jumlah_pesan = $this->keranjang[$m->id];
                $m->subtotal = $m->harga * $m->jumlah_pesan;
                return $m;
            });
        $data['total'] = $data['itemKeranjang']->sum('subtotal');

        return view('livewire.pesan-menu-component', $data);
    }

    public function tambah($id)
    {
        $this->hanyaPelanggan();
        $menu = Menu::tersedia()->find($id);
        if (!$menu) {
            session()->flash('error', 'Menu sudah tidak tersedia.');
            unset($this->keranjang[$id]);
            return;
        }

        $sekarang = $this->keranjang[$id] ?? 0;
        if ($sekarang + 1 > $menu->stok) {
            session()->flash('error', "Stok {$menu->nama} hanya tersisa {$menu->stok}.");
            return;
        }
        $this->keranjang[$id] = $sekarang + 1;
    }

    public function kurang($id)
    {
        if (!isset($this->keranjang[$id])) {
            return;
        }
        $this->keranjang[$id]--;
        if ($this->keranjang[$id] <= 0) {
            unset($this->keranjang[$id]);
        }
    }

    public function hapusItem($id)
    {
        unset($this->keranjang[$id]);
    }

    public function pesan()
    {
        $this->hanyaPelanggan();

        $this->validate([
            'metode_pembayaran' => 'required|in:Tunai,Transfer,E-wallet',
        ], [
            'metode_pembayaran.required' => 'Metode Pembayaran Tidak Boleh Kosong!',
            'metode_pembayaran.in' => 'Metode Pembayaran tidak valid!',
        ]);

        if (empty($this->keranjang)) {
            session()->flash('error', 'Keranjang masih kosong.');
            return;
        }

        try {
            DB::transaction(function () {
                // Kunci baris menu agar dua pelanggan tidak bisa mengambil stok yang sama
                $menu = Menu::whereIn('id', array_keys($this->keranjang))
                    ->lockForUpdate()->get()->keyBy('id');

                $total = 0;
                $baris = [];
                foreach ($this->keranjang as $id => $jumlah) {
                    $m = $menu->get($id);
                    $jumlah = (int) $jumlah;
                    if (!$m || !$m->status || $jumlah < 1) {
                        throw new \RuntimeException("Menu \"" . ($m->nama ?? '#' . $id) . "\" sudah tidak tersedia.");
                    }
                    if ($m->stok < $jumlah) {
                        throw new \RuntimeException("Stok {$m->nama} tidak cukup (tersisa {$m->stok}).");
                    }
                    $subtotal = $m->harga * $jumlah;
                    $total += $subtotal;
                    $baris[] = [$m, $jumlah, $subtotal];
                }

                $pesanan = PesananMenu::create([
                    'user_id' => auth()->id(),
                    'tanggal_pesan' => now()->toDateString(),
                    'metode_pembayaran' => $this->metode_pembayaran,
                    'status' => 'proses',
                    'total_harga' => $total,
                ]);

                foreach ($baris as [$m, $jumlah, $subtotal]) {
                    DetailPesananMenu::create([
                        'pesanan_menu_id' => $pesanan->id,
                        'menu_id' => $m->id,
                        'jumlah' => $jumlah,
                        'harga_satuan' => $m->harga,
                        'subtotal' => $subtotal,
                    ]);
                    $m->decrement('stok', $jumlah);
                }
            });
        } catch (\RuntimeException $e) {
            session()->flash('error', $e->getMessage());
            return;
        }

        $this->reset(['keranjang', 'metode_pembayaran']);
        session()->flash('success', 'Pesanan berhasil dibuat. Lihat statusnya di menu "Pesanan".');
    }
}
