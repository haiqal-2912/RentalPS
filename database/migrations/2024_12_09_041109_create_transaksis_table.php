<?php

use App\Models\Penyewa;
use App\Models\Playstation;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transaksi', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Playstation::class);
            $table->foreignIdFor(User::class);
            $table->string('nama');
            $table->text('alamat')->nullable();
            $table->string('no_telp')->nullable();
            $table->enum('jaminan',['Ktp','Sim','Ijazah']);
            $table->date('tanggal_Pemesanan')->nullable();
            $table->integer('Durasi_sewa')->nullable();
            $table->enum('Metode_pembayaran',['Tunai','Transfer','E-wallet'])->nullable();
            $table->integer('Total_harga')->nullable();
            $table->enum('status',['proses','pinjam','kembali']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksis');
    }
};
