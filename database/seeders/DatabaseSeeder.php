<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Akun kasir awal (pelanggan mendaftar sendiri lewat halaman Register).
        // Segera ganti password ini setelah login pertama!
        User::firstOrCreate(
            ['email' => 'kasir@psone.test'],
            [
                'name' => 'Kasir PS ONE',
                'password' => Hash::make('kasir12345'),
                'alamat' => '-',
                'no_telp' => '-',
                'role' => 'kasir',
                'status' => 1,
            ]
        );
    }
}
