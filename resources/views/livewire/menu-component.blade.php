<div class="container-fluid pt-4 px-4">
    <div class="row g-4">
        <div class="col-sm-12 col-xl-12">
            <div class="bg-light rounded h-100 p-4">
                <h6 class="mb-4">Menu Makanan & Minuman</h6>
                @if (session()->has('success'))
                    <div class="alert alert-success" role="alert">{{ session('success') }}</div>
                @endif
                @if (session()->has('error'))
                    <div class="alert alert-danger" role="alert">{{ session('error') }}</div>
                @endif
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th scope="col">No</th>
                                <th scope="col">Foto</th>
                                <th scope="col">Nama</th>
                                <th scope="col">Kategori</th>
                                <th scope="col">Deskripsi</th>
                                <th scope="col">Harga</th>
                                <th scope="col">Stok</th>
                                <th scope="col">Status</th>
                                <th>Proses</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($menu as $data)
                                <tr wire:key="menu-{{ $data->id }}">
                                    <th scope="row">{{ $menu->firstItem() + $loop->index }}</th>
                                    <td>
                                        @if ($data->foto)
                                            <img src="{{ asset('storage/menu/' . $data->foto) }}" style="width:100px"
                                                alt="{{ $data->nama }}">
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>{{ $data->nama }}</td>
                                    <td>{{ ucfirst($data->kategori) }}</td>
                                    <td>{{ $data->deskripsi }}</td>
                                    <td>@rupiah($data->harga)</td>
                                    <td>{{ $data->stok }}</td>
                                    <td>
                                        @if ($data->status)
                                            <span class="badge bg-success">Tersedia</span>
                                        @else
                                            <span class="badge bg-secondary">Tidak Tersedia</span>
                                        @endif
                                    </td>
                                    <td>
                                        <button class="btn btn-info btn-sm"
                                            wire:click="edit({{ $data->id }})">Edit</button>
                                        <button class="btn btn-danger btn-sm"
                                            wire:click="destroy({{ $data->id }})"
                                            wire:confirm="Hapus menu {{ $data->nama }}?">Delete</button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9">Data Menu Belum Ada</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                {{ $menu->links() }}
                <button wire:click="create" class="btn btn-primary">Tambah</button>
            </div>
        </div>
    </div>
    @if ($addPage)
        @include('menu.create')
    @endif
    @if ($editPage)
        @include('menu.edit')
    @endif
</div>
