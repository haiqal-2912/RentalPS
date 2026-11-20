@extends(auth()->check() ? 'layout.app' : 'layout.public')
@section('title', 'Katalog - PsOne')

@section('content')
    <div class="container-fluid pt-4 px-4">
        <div class="bg-white rounded p-4 mb-4">
            <h6 class="mb-4">Tarif Sewa Playstation</h6>
            <div class="row">
                @forelse ($playstation as $ps)
                    <div class="col-md-4 col-lg-3 mb-4">
                        <div class="card h-100">
                            @if ($ps->foto)
                                <img src="{{ asset('storage/playstation/' . $ps->foto) }}" class="card-img-top"
                                    style="object-fit: cover; height: 160px;" alt="{{ $ps->Type }}">
                            @endif
                            <div class="card-body text-center">
                                <h5 class="card-title">{{ $ps->Type }}</h5>
                                <p class="mb-0"><b>@rupiah($ps->harga_sewa)</b> / hari</p>
                            </div>
                        </div>
                    </div>
                @empty
                    <p>Belum ada unit.</p>
                @endforelse
            </div>
            <a href="{{ route('jadwal') }}" class="btn btn-outline-primary">Lihat Jadwal Ketersediaan</a>
        </div>

        @foreach (['Makanan' => $makanan, 'Minuman' => $minuman] as $judul => $daftar)
            <div class="bg-white rounded p-4 mb-4">
                <h6 class="mb-4">{{ $judul }}</h6>
                <div class="row">
                    @forelse ($daftar as $m)
                        <div class="col-md-4 col-lg-3 mb-4">
                            <div class="card h-100">
                                @if ($m->foto)
                                    <img src="{{ asset('storage/menu/' . $m->foto) }}" class="card-img-top"
                                        style="object-fit: cover; height: 140px;" alt="{{ $m->nama }}">
                                @endif
                                <div class="card-body">
                                    <h6 class="card-title">{{ $m->nama }}</h6>
                                    <p class="card-text small text-muted">{{ $m->deskripsi }}</p>
                                    <b>@rupiah($m->harga)</b>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-muted">Belum ada {{ strtolower($judul) }} yang tersedia.</p>
                    @endforelse
                </div>
            </div>
        @endforeach
    </div>
@endsection
