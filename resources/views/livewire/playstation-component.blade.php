<div class="container-fluid pt-4 px-4">
    <div class="row g-4">
        <div class="col-sm-12 col-xl-12">
            <div class="bg-light rounded h-100 p-4">
                <h6 class="mb-4">Playstation</h6>
                @if (session()->has('success'))
                    <div class="alert alert-success" role="alert">
                        {{ session('success') }}
                    </div>
                @endif
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">No</th>
                            <th scope="col">Type</th>
                            <th scope="col">Serial Number</th>
                            <th scope="col">Harga Sewa</th>
                            <th scope="col">Foto</th>
                            <th>
                                Proses
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($playstation as $data )
                        <tr>
                            <th scope="row">{{ $loop->iteration }}</th>
                            <td>{{ $data->Type }}</td>
                            <td>{{ $data->no_seri }}</td>
                            <td>@rupiah($data->harga_sewa)</td>
                            <td>
                                <img src="{{ asset('storage/playstation/' .$data->foto) }}" style="width:200px" alt="{{ $data->Type }}">
                            </td>
                            <td>
                                <button class="btn btn-info" wire:click="edit({{ $data->id }})">Edit</button>
                                <button class="btn btn-danger"
                                    wire:click="destroy({{ $data->id }})">Delete</button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5">Data Playstation Belum Ada</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
                {{ $playstation->links() }}
                <button wire:click="create" class="btn btn-primary">Tambah</button>
            </div>
        </div>
    </div>
    @if ($addPage)
        @include('playstation.create')
    @endif
    @if ($editPage)
        @include('playstation.edit')
    @endif
</div>
