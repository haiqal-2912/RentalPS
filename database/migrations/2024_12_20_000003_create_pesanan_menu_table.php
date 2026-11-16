<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pesanan_menu', function (Blueprint $table) {
            $table->id();                                   // ID Pesanan (PK)
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete(); // pelanggan
            $table->date('tanggal_pesan');
            $table->enum('metode_pembayaran', ['Tunai', 'Transfer', 'E-wallet']);
            $table->enum('status', ['proses', 'diantar', 'selesai'])->default('proses');
            $table->unsignedInteger('total_harga')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pesanan_menu');
    }
};
