<style>
    table {
        border-collapse: collapse;
        width: 100%;
        border: 1px solid #ccc;
    }

    table tr td {
        padding: 6px;
        font-weight: normal;
        border: 1px solid #ccc;
    }

    table th {
        border: 1px solid #ccc;
    }
</style>


<h1>laporan Transaksi</h1>
<table class="table">
    <thead>
        <tr>
            <th scope="col">No</th>
            <th scope="col">Type</th>
            <th scope="col">Serial Number</th>
            <th scope="col">Nama Pemesan</th>
            <th scope="col">Alamat</th>
            <th scope="col">No Telp</th>
            <th scope="col">Tanggal Pesan</th>
            <th scope="col">Lama Sewa</th>
            <th scope="col">Total Harga</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($transaksi as $data)
            <tr>
                <th scope="row">{{ $loop->iteration }}</th>
                <td>{{ $data->playstation->Type }}</td>
                <td>{{ $data->playstation->no_seri }}</td>
                <td>{{ $data->nama }}</td>
                <td>{{ $data->alamat }}</td>
                <td>{{ $data->no_telp }}</td>
                <td>{{ $data->tanggal_Pemesanan }}</td>
                <td>{{ $data->Durasi_sewa }}</td>
                <td>{{ $data->Total_harga }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="5">Data Laporan Belum Ada</td>
            </tr>
        @endforelse
    </tbody>
</table>
<script>
    window.onload = function() {
        printStruk();
    }

    function printStruk() {
        window.print();
    }
</script>
