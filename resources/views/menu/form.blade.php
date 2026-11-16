<div class="mb-3">
    <label for="nama" class="form-label">Nama</label>
    <input type="text" class="form-control" wire:model="nama" id="nama">
    @error('nama') <div class="form text text-danger">{{ $message }}</div> @enderror
</div>
<div class="mb-3">
    <label for="kategori" class="form-label">Kategori</label>
    <select id="kategori" class="form-select" wire:model="kategori">
        <option value="">--Pilih--</option>
        <option value="makanan">Makanan</option>
        <option value="minuman">Minuman</option>
    </select>
    @error('kategori') <div class="form text text-danger">{{ $message }}</div> @enderror
</div>
<div class="mb-3">
    <label for="deskripsi" class="form-label">Deskripsi</label>
    <textarea class="form-control" rows="3" wire:model="deskripsi" id="deskripsi"></textarea>
    @error('deskripsi') <div class="form text text-danger">{{ $message }}</div> @enderror
</div>
<div class="mb-3">
    <label for="foto" class="form-label">Foto Menu</label>
    <input type="file" class="form-control" wire:model="foto" id="foto" accept="image/*">
    @error('foto') <div class="form text text-danger">{{ $message }}</div> @enderror
    <div wire:loading wire:target="foto" class="form-text">Mengunggah foto...</div>
    @if ($foto)
        <img src="{{ $foto->temporaryUrl() }}" class="mt-2 rounded" style="height:100px" alt="Pratinjau">
    @elseif ($fotoLama)
        <img src="{{ asset('storage/menu/' . $fotoLama) }}" class="mt-2 rounded" style="height:100px" alt="Foto saat ini">
        <div class="form-text">Kosongkan jika tidak ingin mengganti foto.</div>
    @endif
</div>
<div class="row">
    <div class="col-md-4 mb-3">
        <label for="harga" class="form-label">Harga</label>
        <input type="number" min="0" class="form-control" wire:model="harga" id="harga">
        @error('harga') <div class="form text text-danger">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-4 mb-3">
        <label for="stok" class="form-label">Stok</label>
        <input type="number" min="0" class="form-control" wire:model="stok" id="stok">
        @error('stok') <div class="form text text-danger">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-4 mb-3">
        <label for="status" class="form-label">Status</label>
        <select id="status" class="form-select" wire:model="status">
            <option value="1">Tersedia</option>
            <option value="0">Tidak Tersedia</option>
        </select>
        @error('status') <div class="form text text-danger">{{ $message }}</div> @enderror
    </div>
</div>
