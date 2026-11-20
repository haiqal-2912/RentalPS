<div class="container-fluid pt-4 px-4">
    <div class="row g-4">
        <div class="col-sm-12 col-xl-12">
            <div class="bg-light rounded h-100 p-4">
                <h6 class="mb-4">Add Transaksi</h6>

                <form>
                    @if (session()->has('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif
                    <div class="mb-3">
                        <label for="nama" class="form-label">Nama Pemesan</label>
                        <input type="text" class="form-control" wire:model="nama" id="nama"
                            value="{{ old('nama') }}">
                        @error('nama')
                            <div class="form text text-danger"> {{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="alamat" class="form-label">Alamat Pemesan</label>
                        <input type="text" class="form-control" wire:model="alamat" id="alamat"
                            value="{{ old('alamat') }}">
                        @error('alamat')
                            <div class="form text text-danger"> {{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="no_telp" class="form-label">No Telepon Pemesan</label>
                        <input type="text" class="form-control" wire:model="no_telp" id="no_telp"
                            value="{{ old('no_telp') }}">
                        @error('no_telp')
                            <div class="form text text-danger"> {{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="jaminan" class="form-label">Jaminan Pemesan</label>
                        <select id="jaminan" class="form-select" wire:model="jaminan">
                            <option value="">--Pilih--</option>
                            <option value="Ktp">KTP</option>
                            <option value="Sim">SIM</option>
                            <option value="Ijazah">IJAZAH</option>
                        </select>
                        @error('jaminan')
                            <div class="form text text-danger"> {{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="tanggal_Pemesanan" class="form-label">Tanggal Pemesanan</label>
                        <input type="date" min="{{ now()->toDateString() }}" class="form-control" wire:model="tanggal_Pemesanan" id="tanggal_Pemesanan"
                            value="{{ old('tanggal_Pemesanan') }}">
                        @error('tanggal_Pemesanan')
                            <div class="form text text-danger"> {{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="Durasi_sewa" class="form-label">Lama Sewa</label>
                        <input type="number" min="1" max="30" class="form-control" wire:change="hitung" wire:model="Durasi_sewa"
                            id="Durasi_sewa" value="{{ old('Durasi_sewa') }}">
                        @error('Durasi_sewa')
                            <div class="form text text-danger"> {{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="Metode_pembayaran" class="form-label">Metode pembayaran</label>
                        <select id="Metode_pembayaran" class="form-select" wire:model="Metode_pembayaran">
                            <option value="">--Pilih--</option>
                            <option value="Tunai">Tunai</option>
                            <option value="Transfer">Transfer</option>
                            <option value="E-wallet">E-wallet</option>
                        </select>
                        @error('Metode_pembayaran')
                            <div class="form text text-danger"> {{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="Total_harga" class="form-label">Total Harga : @rupiah((int) $Total_harga)</label>
                    </div>
                    <button type="button" wire:click="store" class="btn btn-primary">Tambah</button>
                </form>
            </div>
        </div>
    </div>
</div>
