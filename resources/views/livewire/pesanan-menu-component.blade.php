<div class="container-fluid pt-4 px-4">
    <div class="row g-4">
        <div class="col-sm-12 col-xl-12">
            <div class="bg-light rounded h-100 p-4">
                <h6 class="mb-4">{{ auth()->user()->role === 'kasir' ? 'Data Pesanan Menu' : 'Pesanan Saya' }}</h6>
                @if (session()->has('success'))
                    <div class="alert alert-success" role="alert">{{ session('success') }}</div>
                @endif
                <div class="table-responsive">
                    <table class="table align-top">
                        <thead>
                            <tr>
                                <th scope="col">No</th>
                                @if (auth()->user()->role === 'kasir')
                                    <th scope="col">Pelanggan</th>
                                @endif
                                <th scope="col">Tanggal Pesan</th>
                                <th scope="col">Pesanan</th>
                                <th scope="col">Metode Pembayaran</th>
                                <th scope="col">Total Harga</th>
                                <th scope="col">Status</th>
                                @if (auth()->user()->role === 'kasir')
                                    <th>Proses</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($pesanan as $data)
                                <tr wire:key="pesanan-{{ $data->id }}">
                                    <th scope="row">{{ $pesanan->firstItem() + $loop->index }}</th>
                                    @if (auth()->user()->role === 'kasir')
                                        <td>{{ $data->users->name ?? '-' }}</td>
                                    @endif
                                    <td>{{ $data->tanggal_pesan }}</td>
                                    <td>
                                        @foreach ($data->detail as $d)
                                            <div>{{ $d->menu->nama ?? '-' }} &times; {{ $d->jumlah }}
                                                <small class="text-muted">(@rupiah($d->subtotal))</small>
                                            </div>
                                        @endforeach
                                    </td>
                                    <td>{{ $data->metode_pembayaran }}</td>
                                    <td>@rupiah($data->total_harga)</td>
                                    <td>
                                        @if ($data->status === 'proses')
                                            <span class="badge bg-warning text-dark">Proses</span>
                                        @elseif ($data->status === 'diantar')
                                            <span class="badge bg-info">Diantar</span>
                                        @else
                                            <span class="badge bg-success">Selesai</span>
                                        @endif
                                    </td>
                                    @if (auth()->user()->role === 'kasir')
                                        <td>
                                            @if ($data->status === 'proses')
                                                <button class="btn btn-sm btn-info"
                                                    wire:click="ubahStatus({{ $data->id }}, 'diantar')">Antar</button>
                                            @endif
                                            @if ($data->status === 'diantar')
                                                <button class="btn btn-sm btn-success"
                                                    wire:click="ubahStatus({{ $data->id }}, 'selesai')">Selesai</button>
                                            @endif
                                            <button class="btn btn-sm btn-danger"
                                                wire:click="destroy({{ $data->id }})"
                                                wire:confirm="Hapus pesanan ini?">Delete</button>
                                        </td>
                                    @endif
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8">Belum ada pesanan</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                {{ $pesanan->links() }}
            </div>
        </div>
    </div>
</div>
