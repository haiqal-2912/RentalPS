<div class="container-fluid pt-4 px-4">
    <div class="row g-4">
        <div class="col-sm-12 col-xl-12">
            <div class="bg-light rounded h-100 p-4">

                @if (session()->has('success'))
                    <div class="alert alert-success" role="alert">
                        {{ session('success') }}
                    </div>
                @endif
                <h6 class="mb-4">Data Laporan Transaksi</h6>
                <div class="row">
                    <div class="col-md-4">
                        <input type="date" wire:model="tanggal1" class="form-control" placeholder="Tanggal">
                    </div>
                    <div class="col-md-1 text-center">
                        s/d
                    </div>
                    <div class="col-md-4">
                        <input type="date" wire:model="tanggal2" class="form-control" placeholder="Tanggal">
                    </div>
                    <div class="col-md-3">
                        <button class="btn btn-sm btn-primary" wire:click="cari">Filter</button>
                    </div>
                </div>
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">No</th>
                            <th scope="col">Type</th>
                            <th scope="col">Serial Number</th>
                            <th scope="col">Nama Pemesan</th>
                            <th scope="col">No Telp</th>
                            <th scope="col">Tanggal Main</th>
                            <th scope="col">Jam</th>
                            <th scope="col">Durasi (Jam)</th>
                            <th scope="col">Total Harga</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($transaksi as $data)
                            <tr>
                                <th scope="row">{{ $loop->iteration }}</th>
                                <td>{{ $data->playstation->Type ?? '-' }}</td>
                                <td>{{ $data->playstation->no_seri ?? '-' }}</td>
                                <td>{{ $data->nama }}</td>
                                <td>{{ $data->no_telp }}</td>
                                <td>{{ $data->tanggal_Pemesanan }}</td>
                                <td>{{ $data->rentangJam() }}</td>
                                <td>{{ $data->durasi_jam }}</td>
                                <td>@rupiah($data->Total_harga)</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9">Data Laporan Belum Ada</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                {{ $transaksi->links() }}
                <button class="btn btn-primary" wire:click="exportpdf">Export Pdf</button>
            </div>
        </div>
    </div>
</div>
