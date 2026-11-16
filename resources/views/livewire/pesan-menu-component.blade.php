<div class="container-fluid pt-4 px-4">
    <div class="row g-4">
        {{-- Daftar menu tersedia --}}
        <div class="col-lg-8">
            <div class="bg-light rounded h-100 p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h6 class="mb-0">Menu Makanan & Minuman</h6>
                    <select class="form-select w-auto" wire:model.live="kategori">
                        <option value="">Semua</option>
                        <option value="makanan">Makanan</option>
                        <option value="minuman">Minuman</option>
                    </select>
                </div>
                @if (session()->has('success'))
                    <div class="alert alert-success" role="alert">{{ session('success') }}</div>
                @endif
                @if (session()->has('error'))
                    <div class="alert alert-danger" role="alert">{{ session('error') }}</div>
                @endif
                <div class="row">
                    @forelse ($menu as $data)
                        <div class="col-md-6 d-flex align-items-stretch mb-4" wire:key="pilih-{{ $data->id }}">
                            <div class="card w-100">
                                @if ($data->foto)
                                    <img src="{{ asset('storage/menu/' . $data->foto) }}" class="card-img-top"
                                        style="object-fit: cover; height: 180px;" alt="{{ $data->nama }}">
                                @endif
                                <div class="card-body d-flex flex-column">
                                    <h5 class="card-title">{{ $data->nama }}
                                        <span class="badge bg-primary fs-6 ms-1">{{ ucfirst($data->kategori) }}</span>
                                    </h5>
                                    <p class="card-text flex-grow-1">{{ $data->deskripsi }}</p>
                                    <p class="mb-1"><b>@rupiah($data->harga)</b> &middot; Stok: {{ $data->stok }}</p>
                                    <button wire:click="tambah({{ $data->id }})"
                                        class="btn btn-outline-success w-100">Tambah ke Keranjang</button>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p>Belum ada menu yang tersedia.</p>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Keranjang --}}
        <div class="col-lg-4">
            <div class="bg-light rounded p-4">
                <h6 class="mb-4">Keranjang Pesanan</h6>
                @forelse ($itemKeranjang as $item)
                    <div class="d-flex justify-content-between align-items-center border-bottom py-2"
                        wire:key="keranjang-{{ $item->id }}">
                        <div>
                            <div>{{ $item->nama }}</div>
                            <small>@rupiah($item->harga) &times; {{ $item->jumlah_pesan }} = @rupiah($item->subtotal)</small>
                        </div>
                        <div class="btn-group btn-group-sm">
                            <button class="btn btn-outline-secondary" wire:click="kurang({{ $item->id }})">&minus;</button>
                            <button class="btn btn-outline-secondary" wire:click="tambah({{ $item->id }})">+</button>
                            <button class="btn btn-outline-danger" wire:click="hapusItem({{ $item->id }})">&times;</button>
                        </div>
                    </div>
                @empty
                    <p class="text-muted">Keranjang masih kosong.</p>
                @endforelse

                @if ($itemKeranjang->isNotEmpty())
                    <div class="d-flex justify-content-between mt-3">
                        <b>Total</b>
                        <b>@rupiah($total)</b>
                    </div>
                    <div class="mb-3 mt-3">
                        <label for="metode_pembayaran" class="form-label">Metode Pembayaran</label>
                        <select id="metode_pembayaran" class="form-select" wire:model="metode_pembayaran">
                            <option value="">--Pilih--</option>
                            <option value="Tunai">Tunai</option>
                            <option value="Transfer">Transfer</option>
                            <option value="E-wallet">E-wallet</option>
                        </select>
                        @error('metode_pembayaran') <div class="form text text-danger">{{ $message }}</div> @enderror
                    </div>
                    <button wire:click="pesan" class="btn btn-primary w-100">Pesan Sekarang</button>
                @endif
            </div>
        </div>
    </div>
</div>
