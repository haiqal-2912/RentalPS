<div class="container-fluid pt-4 px-4" wire:poll.30s>
    <div class="bg-white rounded p-4">
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
            <h6 class="mb-0">Jadwal Ketersediaan Unit</h6>
            <div class="btn-group btn-group-sm">
                <button class="btn btn-outline-secondary" wire:click="sebelumnya" @disabled($adalahHariIni)>&laquo; Sebelumnya</button>
                <button class="btn btn-outline-secondary" wire:click="hariIni">Hari Ini</button>
                <button class="btn btn-outline-secondary" wire:click="berikutnya">Berikutnya &raquo;</button>
            </div>
        </div>

        <div class="mb-3 small">
            <span class="badge bg-success">Kosong</span>
            <span class="badge bg-warning text-dark">Dibooking</span>
            <span class="badge bg-danger">Sedang Dipakai</span>
            <span class="text-muted ms-2">Diperbarui otomatis tiap 30 detik</span>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered text-center align-middle mb-0">
                <thead>
                    <tr>
                        <th class="text-start" style="min-width: 150px;">Unit</th>
                        @foreach ($hari as $h)
                            <th style="min-width: 80px;">
                                {{ ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'][$h->dayOfWeek] }}<br>
                                <small>{{ $h->format('d/m') }}</small>
                            </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @forelse ($unit as $u)
                        <tr wire:key="unit-{{ $u->id }}">
                            <td class="text-start">
                                <b>{{ $u->Type }}</b><br>
                                <small class="text-muted">@rupiah($u->harga_sewa)/hari</small>
                            </td>
                            @foreach ($hari as $h)
                                @php $s = $grid[$u->id][$h->toDateString()] ?? 'kosong'; @endphp
                                @if ($s === 'dipakai')
                                    <td class="bg-danger text-white"><small>Dipakai</small></td>
                                @elseif ($s === 'booking')
                                    <td class="bg-warning"><small>Booking</small></td>
                                @else
                                    <td class="bg-success text-white"><small>Kosong</small></td>
                                @endif
                            @endforeach
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ count($hari) + 1 }}">Belum ada unit Playstation.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            <a href="{{ route('transaksi') }}" class="btn btn-primary">Pesan Unit</a>
            @guest
                <span class="text-muted ms-2 small">Anda perlu login untuk memesan.</span>
            @endguest
        </div>
    </div>
</div>
