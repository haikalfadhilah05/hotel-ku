@extends('main.home')
@section('content')
<section class="content-header">
    <div class="container-fluid">
        <!-- Page Heading -->
        <h1 class="h3 mb-4 text-gray-800">Halaman Tambah Kamar</h1>
    </div>

    <section class="content">

    <div class="card">
        <div class="card-header">
            <h5 class="card-title">Tambah Data Kamar</h5>
        </div>
        <div class="card-body">
            <a href="{{ route('products.index') }}" class="btn btn-dark">Kembali</a>
                <br><br>
                <form action="{{ route('products.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label>Nomor kamar</label>
                    <input name="nomor_kamar" type="number" class="form-control" placeholder="...">
                    @error('nomor_kamar')
                        <p>{{ $message }}</p>
                    @enderror
                </div>
                <br>
                <div class="form-group">
                    <label>Lantai</label>
                    <select name="lantai_kamar" class="form-control">
                        <option>Lantai kamar</option>
                        <option value="L1">L1</option>
                        <option value="L2">L2</option>
                        <option value="L3">L3</option>
                        <option value="L4">L4</option>
                    </select>
                    @error('lantai_kamar')
                        <p>{{ $message }}</p>
                    @enderror
                </div>
                <br>
                <div class="form-group">
                    <label>Nama kamar</label>
                    <input name="nama_produk" type="text" class="form-control" placeholder="...">
                    @error('nama_produk')
                    <p>{{ $message }}</p>
                    @enderror
                </div>
                <br>
                <div class="form-group">
                    <label>Fasilitas kamar</label>
                    <input name="fasilitas" type="text" class="form-control" placeholder="...">
                    @error('fasilitas')
                    <p>{{ $message }}</p>
                    @enderror
                </div>
                <br>
                <div class="form-group">
                    <label>Kapasitas kamar</label>
                    <input name="kapasitas" type="number" class="form-control" placeholder="...">
                    @error('kapasitas')
                    <p>{{ $message }}</p>
                    @enderror
                </div>
                <br>
                <div class="form-group">
                    <label>Harga kamar/Malam</label>
                    <input name="harga_produk" type="number" class="form-control" placeholder="...">
                    @error('harga_produk')
                        <p>{{ $message }}</p>
                    @enderror
                </div>
                <br>
                <input type="submit" name="submit" class="btn btn-primary" value="Tambah">
            </form>
        </div>
    </div>

</section>
@endsection
