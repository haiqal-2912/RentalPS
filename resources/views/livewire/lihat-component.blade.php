<div class="container-fluid pt-4 px-4" wire:poll.20s>
    <div class="row g-4">
        <div class="col-sm-12 col-xl-12">
            <div class="bg-light rounded h-100 p-4">
                <h6 class="mb-4">Data Transaksi</h6>
                @if (auth()->user()->role === 'kasir' && $menunggu > 0)
                    <div class="alert alert-warning" role="alert">
                        <i class="fa fa-bell me-2"></i>Ada <b>{{ $menunggu }}</b> booking baru menunggu konfirmasi.
                    </div>
                @endif
                @if (session()->has('success'))
                    <div class="alert alert-success" role="alert">
                        {{ session('success') }}
                    </div>
                @endif
                @if (session()->has('error'))
                    <div class="alert alert-danger" role="alert">
                        {{ session('error') }}
                    </div>
                @endif
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th scope="col">No</th>
                                <th scope="col">Nama</th>
                                <th scope="col">Unit</th>
                                <th scope="col">Alamat</th>
                                <th scope="col">No Telp</th>
                                <th scope="col">Jaminan</th>
                                <th scope="col">Tanggal Pesan</th>
                                <th scope="col">Lama Pesan</th>
                                <th scope="col">Total Harga</th>
                                <th scope="col">Status</th>
                                <th>Proses</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($transaksi as $data)
                                <tr wire:key="transaksi-{{ $data->id }}">
                                    <th scope="row">{{ $loop->iteration }}</th>
                                    <td>{{ $data->nama }}</td>
                                    <td>{{ $data->playstation->Type ?? '-' }}</td>
                                    <td>{{ $data->alamat }}</td>
                                    <td>{{ $data->no_telp }}</td>
                                    <td>{{ $data->jaminan }}</td>
                                    <td>{{ $data->tanggal_Pemesanan }}</td>
                                    <td>{{ $data->Durasi_sewa }}</td>
                                    <td>@rupiah($data->Total_harga)</td>
                                    <td>
                                        @if ($data->status === 'proses')
                                            <span class="badge bg-warning text-dark">Menunggu Konfirmasi</span>
                                        @elseif ($data->status === 'dikonfirmasi')
                                            <span class="badge bg-info">Dikonfirmasi</span>
                                        @elseif ($data->status === 'pinjam')
                                            <span class="badge bg-danger">Dipinjam</span>
                                        @else
                                            <span class="badge bg-success">Kembali</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if (auth()->user()->role === 'kasir')
                                            @if ($data->status == 'proses')
                                                <button class="btn btn-sm btn-warning"
                                                    wire:click="konfirmasi({{ $data->id }})">Konfirmasi</button>
                                            @endif
                                            @if ($data->status == 'dikonfirmasi')
                                                @if ($data->waPelangganUrl())
                                                    <a class="btn btn-sm btn-outline-success" target="_blank"
                                                        rel="noopener" href="{{ $data->waPelangganUrl() }}">WA Pelanggan</a>
                                                @endif
                                                <button class="btn btn-sm btn-success"
                                                    wire:click="proses({{ $data->id }})">Pinjam</button>
                                            @endif
                                            @if ($data->status == 'pinjam')
                                                <button class="btn btn-sm btn-success"
                                                    wire:click="selesai({{ $data->id }})">Kembali</button>
                                            @endif
                                        @elseif ($data->status == 'proses' && $data->waKasirUrl())
                                            <a class="btn btn-sm btn-outline-success" target="_blank" rel="noopener"
                                                href="{{ $data->waKasirUrl() }}">Kirim ke WA Kasir</a>
                                        @endif
                                        <a class="btn btn-sm btn-outline-secondary" target="_blank"
                                            href="{{ route('transaksi.nota', $data->id) }}">Nota</a>
                                        @if (auth()->user()->role === 'kasir')
                                            <button class="btn btn-sm btn-danger"
                                                wire:click="destroy({{ $data->id }})"
                                                wire:confirm="Hapus transaksi ini?">Delete</button>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="11">Data Transaksi Belum Ada</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                {{ $transaksi->links() }}
            </div>
        </div>
    </div>
</div>
