<div class="container-fluid pt-4 px-4">
    <div class="row g-4">
        <div class="col-sm-12 col-xl-12">
            <div class="bg-light rounded h-100 p-4">
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
                <h6 class="mb-4">Data Playstation</h6>
                <div class="row">
                    @foreach ($playstation as $data)
                        @php
                            $dibooking = \App\Models\Transaksi::where('playstation_id', $data->id)
                                ->where('status', '!=', 'kembali')
                                ->exists();
                        @endphp
                        <div class="col-md-4 d-flex align-items-stretch mb-4">
                            <div class="card w-100" style="position: relative;">
                                @if ($dibooking)
                                    <!-- Overlay tanda SUDAH DIBOOKING -->
                                    <div
                                        style="
                                        position: absolute; 
                                        top: 0; left: 0; 
                                        width: 100%; height: 100%; 
                                        background: rgba(255, 0, 0, 0.7); 
                                        color: white; 
                                        display: flex; 
                                        justify-content: center; 
                                        align-items: center; 
                                        font-size: 1.5rem; 
                                        font-weight: bold;
                                        z-index: 1;">
                                        SUDAH DIBOOKING
                                    </div>
                                @endif
                                <!-- Gambar dengan tinggi tetap -->
                                <img src="{{ asset('storage/playstation/' . $data->foto) }}" class="card-img-top"
                                    style="object-fit: cover; height: 200px;" alt="Playstation">
                                <div class="card-body d-flex flex-column">
                                    <h5 class="card-title text-center">{{ $data->Type }}</h5>
                                    <ul class="list-group list-group-flush flex-grow-1">
                                        <li class="list-group-item">Serial Number: {{ $data->no_seri }}</li>
                                        <li class="list-group-item">Harga Sewa/Hari: @rupiah($data->harga_sewa)</li>
                                    </ul>
                                    <div class="mt-auto text-center">
                                        @if (!$dibooking)
                                            <button wire:click="create({{ $data->id }},{{ $data->harga_sewa }})"
                                                class="btn btn-outline-success w-100">Pesan Playstation</button>
                                        @else
                                            <button class="btn btn-danger w-100" disabled>Sudah Dibooking</button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                @if ($addPage)
                    @include('transaksi.create')
                @endif
            </div>
            {{ $playstation->links() }}
        </div>
    </div>
</div>
