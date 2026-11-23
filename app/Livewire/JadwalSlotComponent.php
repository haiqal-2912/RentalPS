<?php

namespace App\Livewire;

use App\Models\Playstation;
use App\Models\Transaksi;
use Carbon\Carbon;
use Livewire\Component;

/**
 * Live Slot Board: matriks unit PS x jam untuk satu tanggal.
 * Hijau = kosong, Kuning = dibooking (proses/dikonfirmasi), Merah = sedang main, Abu-abu = jam sudah lewat.
 * Bisa dibuka tanpa login; tidak menampilkan data pribadi pemesan.
 */
class JadwalSlotComponent extends Component
{
    public $tanggal;

    public function mount()
    {
        $this->tanggal = today()->toDateString();
    }

    protected function hari(): Carbon
    {
        try {
            $d = Carbon::createFromFormat('Y-m-d', (string) $this->tanggal)->startOfDay();
        } catch (\Throwable $e) {
            return today();
        }
        if ($d->lt(today()) || $d->gt(today()->addDays(60))) {
            return today();
        }
        return $d;
    }

    public function berikutnya()
    {
        $this->tanggal = $this->hari()->addDay()->toDateString();
    }

    public function sebelumnya()
    {
        $this->tanggal = $this->hari()->subDay()->toDateString();
    }

    public function hariIni()
    {
        $this->tanggal = today()->toDateString();
    }

    public function render()
    {
        $hari = $this->hari();
        $buka = (int) config('psone.jam_buka', 10);
        $tutup = (int) config('psone.jam_tutup', 22);
        $sekarang = now();
        $jam = range($buka, $tutup - 1);

        $aktif = Transaksi::tumpang($hari->copy(), $hari->copy()->addDay())
            ->get(['playstation_id', 'waktu_mulai', 'waktu_selesai', 'status']);

        $grid = []; // [playstation_id][jam] = 'booking' | 'main'
        foreach ($aktif as $t) {
            $selesaiT = $t->waktu_selesai;
            if ($t->status === 'main' && $selesaiT->lt($sekarang)) {
                $selesaiT = $sekarang; // melebihi jam selesai: tetap merah sampai kasir menandai selesai
            }
            foreach ($jam as $j) {
                $awalSel = $hari->copy()->setTime($j, 0);
                $akhirSel = $awalSel->copy()->addHour();
                if ($t->waktu_mulai->lt($akhirSel) && $selesaiT->gt($awalSel)) {
                    if (($grid[$t->playstation_id][$j] ?? null) !== 'main') {
                        $grid[$t->playstation_id][$j] = $t->status === 'main' ? 'main' : 'booking';
                    }
                }
            }
        }

        $data['unit'] = Playstation::orderBy('Type')->orderBy('no_seri')->get(['id', 'Type', 'no_seri', 'harga_sewa']);
        $data['jam'] = $jam;
        $data['hari'] = $hari;
        $data['grid'] = $grid;
        $data['sekarang'] = $sekarang;
        $data['adalahHariIni'] = $hari->isSameDay(today());

        return view('livewire.jadwal-slot-component', $data);
    }
}
