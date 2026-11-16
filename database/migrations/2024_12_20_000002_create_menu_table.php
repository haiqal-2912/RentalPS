<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('menu', function (Blueprint $table) {
            $table->id();                                   // ID Menu (PK)
            $table->string('nama');
            $table->enum('kategori', ['makanan', 'minuman']);
            $table->text('deskripsi')->nullable();
            $table->string('foto')->nullable();
            $table->unsignedInteger('harga');
            $table->unsignedInteger('stok')->default(0);
            $table->boolean('status')->default(true);       // true = tersedia
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('menu');
    }
};
