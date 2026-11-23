<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Dari sewa per hari (dibawa pulang) menjadi booking main per jam di tempat.
     * Status: proses -> dikonfirmasi -> main -> selesai (atau batal).
     */
    public function up(): void
    {
        Schema::table('transaksi', function (Blueprint $table) {
            $table->dateTime('waktu_mulai')->nullable()->after('tanggal_Pemesanan');
            $table->dateTime('waktu_selesai')->nullable()->after('waktu_mulai');
        });

        // Lama sewa (hari) -> durasi main (jam)
        DB::statement('ALTER TABLE transaksi CHANGE Durasi_sewa durasi_jam INT NULL');

        // Main di tempat: jaminan tidak diperlukan lagi
        DB::statement("ALTER TABLE transaksi MODIFY jaminan ENUM('Ktp','Sim','Ijazah') NULL");

        // Status baru. Enum dilebarkan dulu supaya aman dijalankan dari kondisi apa pun
        // (sebelum/sesudah migrasi status 'dikonfirmasi').
        DB::statement("ALTER TABLE transaksi MODIFY status ENUM('proses','dikonfirmasi','pinjam','kembali','main','selesai','batal') NOT NULL DEFAULT 'proses'");
        DB::table('transaksi')->where('status', 'kembali')->update(['status' => 'selesai']);
        // Booking lama (per hari) tidak bisa dipetakan ke jam, jadi yang belum selesai dibatalkan
        DB::table('transaksi')->whereIn('status', ['proses', 'dikonfirmasi', 'pinjam'])->update(['status' => 'batal']);
        DB::statement("ALTER TABLE transaksi MODIFY status ENUM('proses','dikonfirmasi','main','selesai','batal') NOT NULL DEFAULT 'proses'");

        // Isi waktu untuk data lama agar tidak kosong
        DB::statement("UPDATE transaksi
            SET waktu_mulai = TIMESTAMP(tanggal_Pemesanan, '00:00:00'),
                waktu_selesai = DATE_ADD(TIMESTAMP(tanggal_Pemesanan, '00:00:00'), INTERVAL COALESCE(durasi_jam, 1) HOUR)
            WHERE tanggal_Pemesanan IS NOT NULL");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE transaksi MODIFY status ENUM('proses','dikonfirmasi','pinjam','kembali','main','selesai','batal') NOT NULL DEFAULT 'proses'");
        DB::table('transaksi')->where('status', 'selesai')->update(['status' => 'kembali']);
        DB::table('transaksi')->where('status', 'main')->update(['status' => 'pinjam']);
        DB::table('transaksi')->where('status', 'batal')->update(['status' => 'proses']);
        DB::statement("ALTER TABLE transaksi MODIFY status ENUM('proses','dikonfirmasi','pinjam','kembali') NOT NULL DEFAULT 'proses'");

        DB::table('transaksi')->whereNull('jaminan')->update(['jaminan' => 'Ktp']);
        DB::statement("ALTER TABLE transaksi MODIFY jaminan ENUM('Ktp','Sim','Ijazah') NOT NULL");
        DB::statement('ALTER TABLE transaksi CHANGE durasi_jam Durasi_sewa INT NULL');

        Schema::table('transaksi', function (Blueprint $table) {
            $table->dropColumn(['waktu_mulai', 'waktu_selesai']);
        });
    }
};
