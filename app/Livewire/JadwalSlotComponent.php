<?php

namespace App\Livewire;

use App\Models\Playstation;
use App\Models\Transaksi;
use Carbon\Carbon;
use Livewire\Component;

/**
 * Live Slot Board: matriks unit PS x tanggal.
 * Hijau = kosong, Kuning = dibooking (proses/dikonfirmasi), Merah = sedang dipakai (pinjam).
 * Bisa dibuka tanpa login; tidak menampilkan data pribadi pemesan.
 */
class JadwalSlotComponent extends Component
{
    public $mulai;
    protected int $jumlahHari = 7; // protected: tidak bisa diubah dari browser

    public function mount()
    {
        $this->mulai = today()->toDateString();
    }

    protected function awal(): Carbon
    {
        try {
            $d = Carbon::createFromFormat('Y-m-d', (string) $this->mulai)->startOfDay();
        } catch (\Throwable $e) {
            return today();
        }
        if ($d->lt(today()) || $d->gt(today()->addYear())) {
            return today();
        }
        return $d;
    }

    public function berikutnya()
    {
        $this->mulai = $this->awal()->addDays($this->jumlahHari)->toDateString();
    }

    public function sebelumnya()
    {
        $this->mulai = $this->awal()->subDays($this->jumlahHari)->toDateString();
    }

    public function hariIni()
    {
        $this->mulai = today()->toDateString();
    }

    public function render()
    {
        $awal = $this->awal();
        $akhirJendela = $awal->copy()->addDays($this->jumlahHari); // eksklusif
        $besok = today()->addDay();

        $hari = [];
        for ($i = 0; $i < $this->jumlahHari; $i++) {
            $hari[] = $awal->copy()->addDays($i);
        }

        $grid = []; // [playstation_id][Y-m-d] = 'booking' | 'dipakai'
        $aktif = Transaksi::where('status', '!=', 'kembali')
            ->get(['playstation_id', 'tanggal_Pemesanan', 'Durasi_sewa', 'status']);

        foreach ($aktif as $t) {
            $mulaiT = Carbon::parse($t->tanggal_Pemesanan)->startOfDay();
            $selesaiT = $mulaiT->copy()->addDays(max(1, (int) $t->Durasi_sewa));
            $dipakai = $t->status === 'pinjam';
            if ($dipakai && $selesaiT->lt($besok)) {
                $selesaiT = $besok->copy(); // terlambat kembali: tetap merah sampai dikembalikan
            }
            $tipe = $dipakai ? 'dipakai' : 'booking';

            $d = $mulaiT->lt($awal) ? $awal->copy() : $mulaiT->copy();
            while ($d->lt($selesaiT) && $d->lt($akhirJendela)) {
                $kunci = $d->toDateString();
                if (($grid[$t->playstation_id][$kunci] ?? null) !== 'dipakai') {
                    $grid[$t->playstation_id][$kunci] = $tipe;
                }
                $d->addDay();
            }
        }

        $data['unit'] = Playstation::orderBy('Type')->orderBy('no_seri')->get(['id', 'Type', 'no_seri', 'harga_sewa']);
        $data['hari'] = $hari;
        $data['grid'] = $grid;
        $data['adalahHariIni'] = $awal->isSameDay(today());

        return view('livewire.jadwal-slot-component', $data);
    }
}
