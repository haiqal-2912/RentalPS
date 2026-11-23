<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <title>Nota {{ $t->nomor() }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body { font-family: Arial, sans-serif; background: #f3f3f3; margin: 0; padding: 20px; color: #222; }
        .nota { max-width: 560px; margin: 0 auto; background: #fff; padding: 28px; border-radius: 8px; }
        h2 { margin: 0; text-align: center; }
        .sub { text-align: center; color: #666; margin-bottom: 18px; font-size: 14px; }
        table { width: 100%; border-collapse: collapse; font-size: 14px; }
        td, th { padding: 6px 0; vertical-align: top; }
        .garis td, .garis th { border-top: 1px dashed #aaa; }
        .kanan { text-align: right; }
        .total td { font-weight: bold; font-size: 16px; border-top: 2px solid #222; }
        .aksi { text-align: center; margin-top: 20px; }
        button, a.btn { padding: 8px 16px; border: 0; border-radius: 4px; background: #eb1616; color: #fff; cursor: pointer; text-decoration: none; font-size: 14px; }
        @media print { body { background: #fff; padding: 0; } .aksi { display: none; } .nota { box-shadow: none; } }
    </style>
</head>

<body>
    <div class="nota">
        <h2>{{ strtoupper(config('psone.nama_usaha')) }}</h2>
        <div class="sub">Nota Main Playstation</div>

        <table>
            <tr><td>No. Nota</td><td class="kanan">{{ $t->nomor() }}</td></tr>
            <tr><td>Dibuat</td><td class="kanan">{{ $t->created_at->format('d/m/Y H:i') }}</td></tr>
            <tr><td>Pemesan</td><td class="kanan">{{ $t->nama }}</td></tr>
            <tr><td>No. Telepon</td><td class="kanan">{{ $t->no_telp }}</td></tr>
            <tr><td>Status</td><td class="kanan">{{ ucfirst($t->status) }}</td></tr>
        </table>

        @php
            $durasi = max(1, (int) $t->durasi_jam);
            $tarif = (int) round($t->Total_harga / $durasi); // tarif per jam saat booking dibuat
        @endphp
        <table class="garis" style="margin-top: 12px;">
            <tr>
                <th style="text-align:left">Rincian Main</th>
                <th class="kanan">Subtotal</th>
            </tr>
            <tr>
                <td>
                    {{ $t->playstation->Type ?? 'Unit' }} (SN: {{ $t->playstation->no_seri ?? '-' }})<br>
                    <small>{{ optional($t->waktu_mulai)->format('d/m/Y') }}, {{ $t->rentangJam() }}
                        &middot; {{ $durasi }} jam &times; @rupiah($tarif)</small>
                </td>
                <td class="kanan">@rupiah($t->Total_harga)</td>
            </tr>
            <tr class="total">
                <td>TOTAL</td>
                <td class="kanan">@rupiah($t->Total_harga)</td>
            </tr>
            <tr><td>Metode Pembayaran</td><td class="kanan">{{ $t->Metode_pembayaran }}</td></tr>
        </table>

        <p class="sub" style="margin-top: 20px;">Terima kasih telah bermain di {{ config('psone.nama_usaha') }}.</p>

        <div class="aksi">
            <button onclick="window.print()">Cetak / Simpan PDF</button>
            <a class="btn" style="background:#666" href="{{ route('transaksi') }}">Kembali</a>
        </div>
    </div>
</body>

</html>
