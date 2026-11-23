<?php

namespace App\Livewire;

use App\Livewire\Concerns\ChecksRole;
use App\Models\Transaksi;
use Barryvdh\DomPDF\Facade\Pdf;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;
use Livewire\Attributes\On;

class LaporanComponent extends Component
{
    use WithPagination, WithoutUrlPagination, ChecksRole;
    public $tanggal1, $tanggal2;

    #[On('lihat-laporan')]
    public function render()
    {
        $this->hanyaKasir();
        if ($this->tanggal2 != "") {
            $data['transaksi'] = Transaksi::where('status', 'selesai')
                ->whereBetween('tanggal_Pemesanan', [$this->tanggal1, $this->tanggal2])
                ->paginate(5);
        } else {
            $data['transaksi'] = Transaksi::where('status', 'selesai')->paginate(5);
        }
        return view('livewire.laporan-component', $data);
    }
    public function cari()
    {
        $this->dispatch('lihat-laporan');
    }
    public function exportpdf()
    {
        $this->hanyaKasir();
        if ($this->tanggal2 != "") {
            $data['transaksi'] = Transaksi::where('status', 'selesai')
                ->whereBetween('tanggal_Pemesanan', [$this->tanggal1, $this->tanggal2])
                ->get();
            $pdf = Pdf::loadView('laporan.exportpdf', $data)->output();
            return response()->streamDownload(
                fn() => print($pdf),
                "laporan transaksi " . $this->tanggal1 . ' hingga ' . $this->tanggal2 . ".pdf"
            );
        } else {
            $data['transaksi'] = Transaksi::where('status', 'selesai')->get();
            $pdf = Pdf::loadView('laporan.exportpdf', $data)->output();
            return response()->streamDownload(
                fn() => print($pdf),
                "laporan transaksi keseluruhan.pdf"
            );
        }
    }
}
