@extends('main.home')
@section('content')
<section class="content-header">
    <div class="container-fluid">
        <!-- Page Heading -->
        <h1 class="h3 mb-4 text-gray-800">Halaman Daftar Kamar</h1>
    </div>

    <section class="content">

    <div class="card">
        <div class="card-header">
            <h5 class="card-title">Data Kamar</h5>
        </div>
        <div class="card-body">
            @if($message = Session::get('success'))
            <div class="alert alert-success">{{ $message }}</div>
            @endif
            <a href="{{route('products.create')}}" class="btn btn-success">Tambah Data</a>
            <a href="{{url('products/pdf')}}" class="btn btn-warning shadow">Unduh PDF</a>
            <br><br>
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>Nomor kamar</th>
                        <th>Lantai kamar</th>
                        <th>Nama kamar</th>
                        <th>Fasilitas kamar</th>
                        <th>Kapasitas kamar</th>
                        <th>Harga kamar</th>
                        <th>Status</th>
                        <th>Aksi</th>
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
                        <td>{{ $data->status }}</td>
                        <td>
                            <form action="{{route('products.destroy', $data->id)}}" method="POST">
                                <a href="{{route('products.edit', $data->id)}}" class="btn btn-sm btn-warning">Edit</a>
                                {{-- <a href="{{route('products.reset', $data->id)}}" class="btn btn-sm  btn-success shadow">Reset Status</a> --}}
                                @csrf
                                @method('delete')
                                <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</section>
@endsection
