<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Transaksi</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        table {
            border-collapse: collapse;
            width: 100%;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 5px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
            font-size: 10px;
            padding: 5px;
        }
        td {
            font-size: 9px;
            padding: 2px;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        tr:hover {
            background-color: #ddd;
        }
        .text-right {
            text-align: right;
        }
    </style>
</head>
<body>
    <h1>Daftar Transaksi</h1>
    <table>
        <thead>
            <tr>
                <th>Nomor Unik</th>
                <th>Nama Pelanggan - Jumlah orang</th>
                <th>Kamar</th>
                <th>Fasilitas kamar</th>
                <th>Harga kamar/Malam</th>
                <th>Tanggal Check-In - Check-Out</th>
                <th>Total Harga</th>
                <th>Uang Bayar</th>
                <th>Uang Kembali</th>
                <th>Tanggal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($transactions as $data)
            <tr>
                <td>{{ $data->nomor_unik }}</td>
                <td>{{ $data->nama_pelanggan }} - {{ $data->jmlh_org }}</td>
                <td>{{ $data->lantai_kamar }} - {{ $data->nomor_kamar }} - {{ $data->nama_produk }} - {{ $data->kapasitas }}</td>
                <td>{{ $data->fasilitas }}</td>
                <td style="text-align:right;">Rp.{{ number_format($data->harga_produk) }}</td>
                <td>{{ $data->checkin }} - {{ $data->checkout }}</td>
                <td style="text-align:right;">Rp.{{ number_format($data->total_harga) }}</td>
                <td style="text-align:right;">Rp.{{ number_format($data->uang_bayar) }}</td>
                <td style="text-align:right;">Rp.{{ number_format($data->uang_kembali) }}</td>
                <td>{{ date_format(new DateTime($data->createtrans), 'd-m-Y H:i:s') }}</td>
            </tr>
            @endforeach
            <tr>
                <td colspan="6" style="text-align:right;">Total Income:</td>
                <td colspan="4" style="text-align:right;">Rp.{{ number_format($total_income) }}</td>
            </tr>
        </tbody>
    </table>
</body>
</html>
