<!DOCTYPE html>
<html>
<head>
    <title>Invoice Transaksi #{{ $data->id_trans }}</title>
    <style>
        body {
            font-family: 'Courier New', monospace;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 500px;
            margin: 0 auto;
            padding: 10px;
        }
        .header {
            text-align: center;
        }
        .content {
            margin-top: 20px;
            font-size: 14px;
        }
        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 12px;
        }
        .barcode {
            text-align: right;
            margin-top: 20px;
        }
        .divider {
            border-top: 1px dashed #000;
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h3 style="font-size: 20px;">Hotel-Ku</h3>
        </div>
        <div class="content">
            <div class="divider"></div>
            <p style="font-size: 17px;">Invoice Transaksi</p>
            <p style="font-size: 17px;">Nama Pelanggan: {{ $data->nama_pelanggan }}</p>
            <p style="font-size: 17px;">Jumlah Orang: {{ $data->jmlh_org }}</p>
            <p style="font-size: 17px;">Tanggal: {{ date('d/m/Y H:i:s') }}</p>
            <p style="font-size: 17px;">Kode Transaksi: {{ $data->nomor_unik  }}</p>
            <div class="divider"></div>

            <p style="font-size: 17px;">Lantai kamar & Nomor kamar: {{ $data->lantai_kamar }} - {{ $data->nomor_kamar }}</p>
            <p style="font-size: 17px;">Nama kamar: {{ $data->nama_produk }}</p>
            <p style="font-size: 17px;">Kapasitas kamar: {{ $data->kapasitas }}</p>
            <p style="font-size: 17px;">Fasilitas kamar: {{ $data->fasilitas }}</p>
            <p style="font-size: 17px;">Check-in: {{ $data->checkin }}</p>
            <p style="font-size: 17px;">Check-out: {{ $data->checkout }}</p>
            <p style="font-size: 17px;">Harga kamar/Malam: Rp.{{ number_format($data->harga_produk) }}</p>
            <p>Total Harga: Rp.{{ number_format($data->total_harga) }}</p>
            <div class="divider"></div>
        </div>
        <div class="barcode">
            <p>Uang Bayar: Rp.{{ number_format($data->uang_bayar) }}</p>
            <p>Uang Kembali: Rp.{{ number_format($data->uang_kembali) }}</p>
        </div>
        <div class="footer">
            <div class="divider"></div>
            <p style="font-size: 17px;">Terima Kasih Anda Telah Memesan kamar Hotel Di Hotel-Ku</p>
        </div>
    </div>
</body>
</html>
