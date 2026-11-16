<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Ubah role dari (owner, kasir) menjadi (kasir, pelanggan).
     * Data lama dengan role "owner" dijadikan "kasir" agar tetap bisa login sebagai pengelola.
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE users MODIFY role ENUM('owner','kasir','pelanggan') NOT NULL DEFAULT 'pelanggan'");
        DB::table('users')->where('role', 'owner')->update(['role' => 'kasir']);
        DB::statement("ALTER TABLE users MODIFY role ENUM('kasir','pelanggan') NOT NULL DEFAULT 'pelanggan'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE users MODIFY role ENUM('owner','kasir','pelanggan') NOT NULL DEFAULT 'kasir'");
        DB::table('users')->where('role', 'pelanggan')->update(['role' => 'kasir']);
        DB::statement("ALTER TABLE users MODIFY role ENUM('owner','kasir') NOT NULL");
    }
};
