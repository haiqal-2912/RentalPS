<div class="container-fluid pt-4 px-4">
    <div class="row g-4">
        <div class="col-sm-12 col-xl-12">
            <div class="bg-light rounded h-100 p-4">
                <h6 class="mb-4">Data Transaksi</h6>
                @if (session()->has('success'))
                    <div class="alert alert-success" role="alert">
                        {{ session('success') }}
                    </div>
                @endif
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">No</th>
                            {{-- <th scope="col">Type</th>
                            <th scope="col">Serial Number</th>
                            <th scope="col">Harga Sewa</th> --}}
                            <th scope="col">Nama</th>
                            <th scope="col">Alamat</th>
                            <th scope="col">No Telp</th>
                            <th scope="col">Jaminan</th>
                            <th scope="col">Tanggal Pesan</th>
                            <th scope="col">Lama Pesan</th>
                            <th scope="col">Total Harga</th>
                            <th scope="col">Status</th>
                            <th>
                                Proses
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($transaksi as $data)
                            <tr>
                                <th scope="row">{{ $loop->iteration }}</th>
                                {{-- <td>{{ $data->playstation->Type}}</td>
                                <td>{{ $data->playstation->no_seri }}</td>
                                <td>{{ $data->playstation->harga_sewa }}</td> --}}
                                <td>{{ $data->nama }}</td>
                                <td>{{ $data->alamat }}</td>
                                <td>{{ $data->no_telp }}</td>
                                <td>{{ $data->jaminan }}</td>
                                <td>{{ $data->tanggal_Pemesanan }}</td>
                                <td>{{ $data->Durasi_sewa }}</td>
                                <td>@rupiah($data->Total_harga)</td>
                                <td>{{ $data->status }}</td>
                                <td>
                                    @if ($data->status == 'proses')
                                        <button class="btn btn-sm btn-success"
                                            wire:click="proses({{ $data->id }})">Pinjam</button>
                                    @endif
                                    @if ($data->status == 'pinjam')
                                        <button class="btn btn-sm btn-success"
                                            wire:click="selesai({{ $data->id }})">Kembali</button>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5">Data Playstation Belum Ada</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                {{ $transaksi->links() }}
            </div>
        </div>
    </div>
</div>
