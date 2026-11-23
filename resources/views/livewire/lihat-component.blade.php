<div class="container-fluid pt-4 px-4" wire:poll.20s>
    <div class="row g-4">
        <div class="col-sm-12 col-xl-12">
            <div class="bg-light rounded h-100 p-4">
                <h6 class="mb-4">Data Booking</h6>
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
                                <th scope="col">No Telp</th>
                                <th scope="col">Tanggal Main</th>
                                <th scope="col">Jam</th>
                                <th scope="col">Durasi</th>
                                <th scope="col">Total Harga</th>
                                <th scope="col">Status</th>
                                <th>Proses</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($transaksi as $data)
                                <tr wire:key="transaksi-{{ $data->id }}">
                                    <th scope="row">{{ $transaksi->firstItem() + $loop->index }}</th>
                                    <td>{{ $data->nama }}</td>
                                    <td>{{ $data->playstation->Type ?? '-' }}</td>
                                    <td>{{ $data->no_telp }}</td>
                                    <td>{{ $data->waktu_mulai?->format('d/m/Y') ?? $data->tanggal_Pemesanan }}</td>
                                    <td>{{ $data->rentangJam() }}</td>
                                    <td>{{ $data->durasi_jam }} jam</td>
                                    <td>@rupiah($data->Total_harga)</td>
                                    <td>
                                        @if ($data->status === 'proses')
                                            <span class="badge bg-warning text-dark">Menunggu Konfirmasi</span>
                                        @elseif ($data->status === 'dikonfirmasi')
                                            <span class="badge bg-info">Dikonfirmasi</span>
                                        @elseif ($data->status === 'main')
                                            <span class="badge bg-danger">Sedang Main</span>
                                        @elseif ($data->status === 'selesai')
                                            <span class="badge bg-success">Selesai</span>
                                        @else
                                            <span class="badge bg-secondary">Batal</span>
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
                                                    wire:click="mulaiMain({{ $data->id }})">Mulai Main</button>
                                            @endif
                                            @if ($data->status == 'main')
                                                <button class="btn btn-sm btn-success"
                                                    wire:click="selesai({{ $data->id }})">Selesai</button>
                                            @endif
                                            @if (in_array($data->status, ['proses', 'dikonfirmasi']))
                                                <button class="btn btn-sm btn-outline-danger"
                                                    wire:click="batalkan({{ $data->id }})"
                                                    wire:confirm="Batalkan booking ini?">Batalkan</button>
                                            @endif
                                        @elseif ($data->status == 'proses')
                                            @if ($data->waKasirUrl())
                                                <a class="btn btn-sm btn-outline-success" target="_blank" rel="noopener"
                                                    href="{{ $data->waKasirUrl() }}">Kirim ke WA Kasir</a>
                                            @endif
                                            <button class="btn btn-sm btn-outline-danger"
                                                wire:click="batalkan({{ $data->id }})"
                                                wire:confirm="Batalkan booking ini?">Batalkan</button>
                                        @endif
                                        <a class="btn btn-sm btn-outline-secondary" target="_blank"
                                            href="{{ route('transaksi.nota', $data->id) }}">Nota</a>
                                        @if (auth()->user()->role === 'kasir')
                                            <button class="btn btn-sm btn-danger"
                                                wire:click="destroy({{ $data->id }})"
                                                wire:confirm="Hapus data booking ini?">Delete</button>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10">Data Booking Belum Ada</td>
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
