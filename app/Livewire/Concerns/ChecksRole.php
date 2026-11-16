<?php

namespace App\Livewire\Concerns;

trait ChecksRole
{
    protected function isKasir(): bool
    {
        return auth()->check() && auth()->user()->role === 'kasir';
    }

    protected function isPelanggan(): bool
    {
        return auth()->check() && auth()->user()->role === 'pelanggan';
    }

    protected function hanyaKasir(): void
    {
        abort_unless($this->isKasir(), 403, 'Hanya kasir yang boleh melakukan aksi ini.');
    }

    protected function hanyaPelanggan(): void
    {
        abort_unless($this->isPelanggan(), 403, 'Hanya pelanggan yang boleh melakukan aksi ini.');
    }
}
