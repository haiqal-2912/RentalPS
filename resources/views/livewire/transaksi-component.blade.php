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

                {{-- Booking berhasil: kirim pesan terformat ke WhatsApp kasir (F-02) & lihat nota --}}
                @if ($booking)
                    <div class="alert alert-info" role="alert">
                        <b>Booking {{ $booking->nomor() }}</b> tersimpan.
                        @if (auth()->user()->role === 'pelanggan')
                            Kirim detail ke kasir agar cepat dikonfirmasi.
                        @endif
                        <div class="mt-2">
                            @if (auth()->user()->role === 'pelanggan' && $booking->waKasirUrl())
                                <a class="btn btn-sm btn-success" target="_blank" rel="noopener"
                                    href="{{ $booking->waKasirUrl() }}"><i class="fab fa-whatsapp me-1"></i>Kirim ke WhatsApp Kasir</a>
                            @endif
                            <a class="btn btn-sm btn-outline-secondary" target="_blank"
                                href="{{ route('transaksi.nota', $booking->id) }}">Lihat Nota</a>
                        </div>
                    </div>
                @endif

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h6 class="mb-0">Data Playstation</h6>
                    <a href="{{ route('jadwal') }}" class="btn btn-sm btn-outline-primary">Lihat Jadwal Unit</a>
                </div>
                <div class="row">
                    @foreach ($playstation as $data)
                        @php $terisi = in_array($data->id, $terisiSekarang); @endphp
                        <div class="col-md-4 d-flex align-items-stretch mb-4" wire:key="ps-{{ $data->id }}">
                            <div class="card w-100" style="position: relative;">
                                @if ($terisi)
                                    <span class="badge bg-danger"
                                        style="position: absolute; top: 10px; right: 10px; z-index: 1;">Sedang dipakai</span>
                                @endif
                                <!-- Gambar dengan tinggi tetap -->
                                <img src="{{ asset('storage/playstation/' . $data->foto) }}" class="card-img-top"
                                    style="object-fit: cover; height: 200px;" alt="Playstation">
                                <div class="card-body d-flex flex-column">
                                    <h5 class="card-title text-center">{{ $data->Type }}</h5>
                                    <ul class="list-group list-group-flush flex-grow-1">
                                        <li class="list-group-item">Serial Number: {{ $data->no_seri }}</li>
                                        <li class="list-group-item">Harga/Jam: @rupiah($data->harga_sewa)</li>
                                    </ul>
                                    <div class="mt-auto text-center">
                                        <button wire:click="create({{ $data->id }})"
                                            class="btn btn-outline-success w-100">Booking Unit Ini</button>
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
