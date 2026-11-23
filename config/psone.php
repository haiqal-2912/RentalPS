<?php

return [
    // Nama usaha yang tampil di pesan WhatsApp & nota
    'nama_usaha' => env('PSONE_NAMA', 'PS ONE'),

    // Nomor WhatsApp kasir, format internasional tanpa + (contoh: 6281234567890)
    'wa_kasir' => env('PSONE_WA_KASIR'),

    // Jam operasional (jam bulat, 0-24). Booking tidak boleh melewati jam tutup.
    'jam_buka' => (int) env('PSONE_JAM_BUKA', 10),
    'jam_tutup' => (int) env('PSONE_JAM_TUTUP', 22),

    // Durasi main maksimal per booking (jam)
    'maks_durasi_jam' => (int) env('PSONE_MAKS_DURASI', 8),
];
