<div class="container-fluid pt-4 px-4">
    <div class="row g-4">
        <div class="col-sm-12 col-xl-12">
            <div class="bg-light rounded h-100 p-4">
                <h6 class="mb-1">Booking Main di Tempat</h6>
                @if ($unitDipilih)
                    <p class="text-muted">Unit: <b>{{ $unitDipilih->Type }}</b> &middot; @rupiah($unitDipilih->harga_sewa) / jam
                        &middot; Buka {{ sprintf('%02d:00', config('psone.jam_buka', 10)) }} - {{ sprintf('%02d:00', config('psone.jam_tutup', 22)) }}</p>
                @endif

                <form>
                    @if (session()->has('error'))
                        <div class="alert alert-danger" role="alert">
                            {{ session('error') }}
                        </div>
                    @endif
                    <div class="mb-3">
                        <label for="nama" class="form-label">Nama Pemesan</label>
                        <input type="text" class="form-control" wire:model="nama" id="nama">
                        @error('nama')
                            <div class="form text text-danger"> {{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="no_telp" class="form-label">No Telepon / WhatsApp</label>
                        <input type="text" class="form-control" wire:model="no_telp" id="no_telp">
                        @error('no_telp')
                            <div class="form text text-danger"> {{ $message }}</div>
                        @enderror
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="tanggal_Pemesanan" class="form-label">Tanggal Main</label>
                            <input type="date" min="{{ now()->toDateString() }}" class="form-control"
                                wire:model.live="tanggal_Pemesanan" id="tanggal_Pemesanan">
                            @error('tanggal_Pemesanan')
                                <div class="form text text-danger"> {{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="jam_mulai" class="form-label">Jam Mulai</label>
                            <select id="jam_mulai" class="form-select" wire:model.live="jam_mulai">
                                <option value="">--Pilih--</option>
                                @foreach ($jamOpsi as $j)
                                    <option value="{{ $j }}">{{ sprintf('%02d:00', $j) }}</option>
                                @endforeach
                            </select>
                            @if (empty($jamOpsi))
                                <div class="form text text-danger">Tidak ada jam tersisa pada tanggal ini. Pilih tanggal lain.</div>
                            @endif
                            @error('jam_mulai')
                                <div class="form text text-danger"> {{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="durasi_jam" class="form-label">Durasi Main</label>
                            <select id="durasi_jam" class="form-select" wire:model.live="durasi_jam">
                                <option value="">--Pilih--</option>
                                @foreach ($durasiOpsi as $d)
                                    <option value="{{ $d }}">{{ $d }} jam</option>
                                @endforeach
                            </select>
                            @error('durasi_jam')
                                <div class="form text text-danger"> {{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    @if ($infoSlot === 'tersedia')
                        <div class="alert alert-success py-2">Jam tersebut masih kosong.</div>
                    @elseif ($infoSlot === 'bentrok')
                        <div class="alert alert-danger py-2">Jam tersebut sudah dibooking. Pilih jam lain atau cek
                            <a href="{{ route('jadwal') }}" target="_blank">Jadwal Unit</a>.</div>
                    @endif
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
                        <label class="form-label">Total Harga : @rupiah((int) $Total_harga)</label>
                    </div>
                    <button type="button" wire:click="store" class="btn btn-primary">Booking</button>
                </form>
            </div>
        </div>
    </div>
</div>
