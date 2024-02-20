<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Kamar</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        h1 {
            text-align: center;
            margin-bottom: 30px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th,
        td {
            border: 1px solid #dddddd;
            padding: 12px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        td:last-child {
            text-align: center;
        }
    </style>
</head>
<body>
    <h1>Daftar kamar</h1>
    <table>
        <thead>
            <tr>
                <th>Nomor kamar</th>
                <th>Lantai kamar</th>
                <th>Nama kamar</th>
                <th>Fasilitas kamar</th>
                <th>Kapasitas kamar</th>
                <th>Harga kamar</th>
                <th>Tanggal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($products as $data)
            <tr>
                <td>{{ $data->nomor_kamar }}</td>
                <td>{{ $data->lantai_kamar }}</td>
                <td>{{ $data->nama_produk }}</td>
                <td>{{ $data->fasilitas }}</td>
                <td>{{ $data->kapasitas }}</td>
                <td>Rp. {{ number_format($data->harga_produk) }}</td>
                <td>{{ $data->created_at->format('d-m-Y H:i:s') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
