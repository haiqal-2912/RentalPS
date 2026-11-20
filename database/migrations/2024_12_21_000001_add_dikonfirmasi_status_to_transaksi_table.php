<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Alur baru: proses (menunggu konfirmasi kasir) -> dikonfirmasi -> pinjam -> kembali
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE transaksi MODIFY status ENUM('proses','dikonfirmasi','pinjam','kembali') NOT NULL DEFAULT 'proses'");
    }

    public function down(): void
    {
        DB::table('transaksi')->where('status', 'dikonfirmasi')->update(['status' => 'proses']);
        DB::statement("ALTER TABLE transaksi MODIFY status ENUM('proses','pinjam','kembali') NOT NULL");
    }
};
