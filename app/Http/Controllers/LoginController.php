<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function index()
    {
        return view('login.index');
    }
    public function proses(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ], [
            'email.required' => 'Email Tidak Boleh Kosong!',
            'email.email' => 'Format email salah!',
            'password.required' => 'Password Tidak Boleh Kosong!'
        ]);
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return redirect()->intended(route('beranda'));
        }
        return back()->withErrors([
            'email' => 'Autentikasi Gagal!',
            'password' => 'Password Salah'
        ])->onlyInput('email');
    }
    public function keluar(Request $request){
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('beranda');
    }
}
