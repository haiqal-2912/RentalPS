<?php

namespace App\Livewire;

use App\Livewire\Concerns\ChecksRole;
use App\Models\DetailPesananMenu;
use App\Models\Menu;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class MenuComponent extends Component
{
    use WithPagination, WithoutUrlPagination, WithFileUploads, ChecksRole;
    protected $paginationTheme = 'bootstrap';

    public $addPage = false, $editPage = false;
    public $menu_id, $nama, $kategori, $deskripsi, $foto, $fotoLama, $harga, $stok, $status = 1;

    public function mount()
    {
        $this->hanyaKasir();
    }

    public function render()
    {
        $this->hanyaKasir();
        $data['menu'] = Menu::orderBy('kategori')->orderBy('nama')->paginate(10);
        return view('livewire.menu-component', $data);
    }

    protected function rules(): array
    {
        return [
            'nama' => 'required|max:100',
            'kategori' => 'required|in:makanan,minuman',
            'deskripsi' => 'nullable|max:500',
            'foto' => ($this->editPage ? 'nullable' : 'required') . '|image|max:2048',
            'harga' => 'required|integer|min:0',
            'stok' => 'required|integer|min:0',
            'status' => 'required|in:0,1',
        ];
    }

    protected function messages(): array
    {
        return [
            'nama.required' => 'Nama Tidak Boleh Kosong!',
            'kategori.required' => 'Kategori Tidak Boleh Kosong!',
            'kategori.in' => 'Kategori harus makanan atau minuman!',
            'foto.required' => 'Foto Tidak Boleh Kosong!',
            'foto.image' => 'Foto harus sesuai format',
            'foto.max' => 'Ukuran foto maksimal 2 MB',
            'harga.required' => 'Harga Tidak Boleh Kosong!',
            'harga.integer' => 'Harga harus berupa angka!',
            'harga.min' => 'Harga tidak boleh negatif!',
            'stok.required' => 'Stok Tidak Boleh Kosong!',
            'stok.integer' => 'Stok harus berupa angka!',
            'stok.min' => 'Stok tidak boleh negatif!',
            'status.required' => 'Status Tidak Boleh Kosong!',
        ];
    }

    public function create()
    {
        $this->hanyaKasir();
        $this->resetForm();
        $this->addPage = true;
    }

    public function store()
    {
        $this->hanyaKasir();
        $this->validate();

        $this->foto->storeAs('menu', $this->foto->hashName(), 'public');
        Menu::create([
            'nama' => $this->nama,
            'kategori' => $this->kategori,
            'deskripsi' => $this->deskripsi,
            'foto' => $this->foto->hashName(),
            'harga' => $this->harga,
            'stok' => $this->stok,
            'status' => (bool) $this->status,
        ]);

        session()->flash('success', 'Menu Berhasil Disimpan');
        $this->resetForm();
    }

    public function edit($id)
    {
        $this->hanyaKasir();
        $this->resetForm();
        $menu = Menu::findOrFail($id);
        $this->menu_id = $menu->id;
        $this->nama = $menu->nama;
        $this->kategori = $menu->kategori;
        $this->deskripsi = $menu->deskripsi;
        $this->fotoLama = $menu->foto;
        $this->harga = $menu->harga;
        $this->stok = $menu->stok;
        $this->status = $menu->status ? 1 : 0;
        $this->editPage = true;
    }

    public function update()
    {
        $this->hanyaKasir();
        $this->validate();

        $menu = Menu::findOrFail($this->menu_id);
        $dataBaru = [
            'nama' => $this->nama,
            'kategori' => $this->kategori,
            'deskripsi' => $this->deskripsi,
            'harga' => $this->harga,
            'stok' => $this->stok,
            'status' => (bool) $this->status,
        ];

        if ($this->foto) {
            $this->hapusFile($menu->foto);
            $this->foto->storeAs('menu', $this->foto->hashName(), 'public');
            $dataBaru['foto'] = $this->foto->hashName();
        }

        $menu->update($dataBaru);
        session()->flash('success', 'Menu Berhasil Diubah');
        $this->resetForm();
    }

    public function destroy($id)
    {
        $this->hanyaKasir();
        $menu = Menu::findOrFail($id);

        // Menu yang sudah pernah dipesan tidak dihapus agar riwayat pesanan tetap utuh.
        if (DetailPesananMenu::where('menu_id', $menu->id)->exists()) {
            session()->flash('error', 'Menu sudah pernah dipesan dan tidak bisa dihapus. Ubah status menjadi "Tidak Tersedia" saja.');
            return;
        }

        $this->hapusFile($menu->foto);
        $menu->delete();
        session()->flash('success', 'Menu Berhasil Dihapus');
        $this->resetForm();
    }

    public function batal()
    {
        $this->resetForm();
    }

    private function hapusFile($foto): void
    {
        if ($foto && Storage::disk('public')->exists('menu/' . $foto)) {
            Storage::disk('public')->delete('menu/' . $foto);
        }
    }

    private function resetForm(): void
    {
        $this->reset(['addPage', 'editPage', 'menu_id', 'nama', 'kategori', 'deskripsi', 'foto', 'fotoLama', 'harga', 'stok']);
        $this->status = 1;
        $this->resetValidation();
    }
}
