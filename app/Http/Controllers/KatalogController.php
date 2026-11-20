<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Playstation;

class KatalogController extends Controller
{
    /** Katalog tarif konsol + menu makanan/minuman, bisa dibuka tanpa login. */
    public function index()
    {
        return view('katalog.index', [
            'playstation' => Playstation::orderBy('Type')->get(['id', 'Type', 'foto', 'harga_sewa']),
            'makanan' => Menu::tersedia()->where('kategori', 'makanan')->orderBy('nama')->get(),
            'minuman' => Menu::tersedia()->where('kategori', 'minuman')->orderBy('nama')->get(),
        ]);
    }
}
