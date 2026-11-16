<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detail_pesanan_menu', function (Blueprint $table) {
            $table->id();                                   // ID Detail (PK)
            $table->foreignId('pesanan_menu_id')->constrained('pesanan_menu')->cascadeOnDelete();
            // restrict: menu yang pernah dipesan tidak bisa dihapus (riwayat pesanan tetap utuh)
            $table->foreignId('menu_id')->constrained('menu')->restrictOnDelete();
            $table->unsignedInteger('jumlah');
            $table->unsignedInteger('harga_satuan');
            $table->unsignedInteger('subtotal');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_pesanan_menu');
    }
};
