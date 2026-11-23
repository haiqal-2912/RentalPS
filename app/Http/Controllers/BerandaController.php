<?php

namespace App\Http\Controllers;

use App\Models\Playstation;
use App\Models\Transaksi;
use App\Models\User;
use Illuminate\Http\Request;

class BerandaController extends Controller
{
    public function index()
    {
        $data['playstation']=Playstation::count();
        $data['user'] =User::count();
        $data['transaksi']=Transaksi::where('status', 'selesai')->sum('Total_harga');
        return view('beranda', $data);
    }
}
