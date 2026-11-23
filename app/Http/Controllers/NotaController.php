<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;

class NotaController extends Controller
{
    /** Nota digital (e-receipt) per transaksi: kasir untuk semua, pelanggan hanya miliknya sendiri. */
    public function show(Transaksi $transaksi)
    {
        $user = auth()->user();
        abort_unless($user->role === 'kasir' || (int) $transaksi->user_id === (int) $user->id, 403);

        $transaksi->load('playstation');
        return view('transaksi.nota', ['t' => $transaksi]);
    }
}
