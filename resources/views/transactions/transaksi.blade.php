@extends('main.home')
@section('content')
<section class="content-header">
    <div class="container-fluid">
        <!-- Page Heading -->
        <h1 class="h3 mb-4 text-gray-800">Halaman Daftar Transaksi</h1>
    </div>

    <section class="content">

    <div class="card">
        <div class="card-header">
            <h5 class="card-title">Data Transaksi</h5>
        </div>
        <div class="card-body">
            @if($message = Session::get('success'))
            <div class="alert alert-success">{{ $message }}</div>
            @endif
            @if (Auth::user()->role == 'owner')
            <form action="{{ route('transactions.index') }}" method="get" class="form-inline">
                <div class="form-group mx-2">
                    <label for="start_date" class="mr-2">Tanggal Awal :</label>
                    <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
                </div>
                <div class="form-group mx-2">
                    <label for="end_date" class="mr-2">Tanggal Akhir :</label>
                    <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
                </div>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-search"></i>
                </button>
                <a href="{{ route('transactions.index') }}" class="btn btn-danger">
                    <i class="fas fa-undo"></i>
                </a>
            </form>
            <br>
            @endif
            @if (Auth::user()->role == 'kasir')
            <a href="{{route('transactions.create')}}" class="btn btn-success">Tambah Data</a>
            @endif
            @if (Auth::user()->role == 'owner')
            <a href="{{url('transactions/pdf')}}" class="btn btn-warning shadow">Unduh PDF</a>
            @endif
            @if (Auth::user()->role == 'owner')
            <a href="{{url('transactions/tgl')}}" class="btn btn-warning shadow">Unduh PDF Pertanggal</a>
            @endif
            <br><br>
            @if (Auth::user()->role == 'kasir')
            <div class="row">
                <div class="col-md-6 col-12">
                    <select name="id_produk" id="id_produk" class="form-control" required>
                        <option value="">Daftar kamar yang dibooking</option>
                        @foreach ($bookedProducts as $data)
                            <option value="{{ $data->id }}" data-harga="{{ $data->harga_produk }}" data-fasilitas="{{ $data->fasilitas }}">
                                {{ $data->lantai_kamar }} - {{ $data->nomor_kamar }} - {{ $data->nama_produk }} - {{ $data->harga_produk }} - {{ $data->kapasitas }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6 col-12">
                    <div class="form-group">
                        <button type="button" class="btn btn-primary d-block" id="refreshButton">Refresh status</button>
                    </div>
                </div>
            </div>
            @endif
            <br>
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nomor Unik</th>
                        <th>Nama Pelanggan - Jumlah orang</th>
                        <th>Kamar</th>
                        <th>Fasilitas kamar</th>
                        <th>Harga kamar/Malam</th>
                        <th>Tanggal Check-In - Check-Out</th>
                        <th>Total Harga</th>
                        <th>Uang Bayar</th>
                        <th>Uang Kembali</th>
                        @if (Auth::user()->role == 'owner')
                        <th>Tanggal</th>
                        @endif
                        @if (Auth::user()->role != 'owner')
                        <th>Aksi</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    <?php $no_transaksi = 1?>
                    @foreach ($transactions as $data)
                    <tr>
                        <td>{{ $no_transaksi }}</td>
                        <td>{{ $data->nomor_unik }}</td>
                        <td>{{ $data->nama_pelanggan }} - {{ $data->jmlh_org }}</td>
                        <td>{{ $data->lantai_kamar }} - {{ $data->nomor_kamar }} - {{ $data->nama_produk }} - {{ $data->kapasitas }}</td>
                        <td>{{ $data->fasilitas }}</td>
                        <td>Rp. {{ number_format ($data->harga_awal) }}</td>
                        <td>{{ $data->checkin }} - {{ $data->checkout }}</td>
                        <td>Rp. {{ number_format ($data->total_harga) }}</td>
                        <td>Rp. {{ number_format ($data->uang_bayar) }}</td>
                        <td>Rp. {{ number_format ($data->uang_kembali) }}</td>
                        @if (Auth::user()->role == 'owner')
                        <td>{{ date_format(new DateTime($data->createtrans), 'd-m-Y H:i:s') }}</td>
                        @endif
                        @if (Auth::user()->role != 'owner')
                        <td>
                            @if (Auth::user()->role == 'kasir')
                            <a href="{{url('transactions/pdf2', $data->id_trans)}}" class="btn btn-sm btn-primary shadow">Cetak Struk</a>
                            @endif
                            @if (Auth::user()->role == 'admin')
                            <a href="{{route('transactions.edit', $data->id_trans)}}" class="btn btn-sm btn-warning">Edit</a>
                            <form action="{{route('transactions.destroy', $data->id_trans)}}" method="POST">
                                @csrf
                                @method('delete')
                                <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                            </form>
                            @endif
                        </td>
                        @endif
                    </tr>
                    <?php $no_transaksi++ ?>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</section>

<script>
    document.getElementById('refreshButton').addEventListener('click', function() {
        var productId = document.getElementById('id_produk').value;

        if (productId) {
            var confirmation = confirm('Anda yakin ingin memperbarui status kamar?');
            if (confirmation) {
                // Redirect ke URL dengan parameter untuk memperbarui status
                window.location.href = '/update-product-status/' + productId;
            }
        }
    });
</script>

@endsection
