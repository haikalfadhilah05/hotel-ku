@extends('main.home')
@section('content')
<section class="content-header">
    <div class="container-fluid">
        <!-- Page Heading -->
        <h1 class="h3 mb-4 text-gray-800">Halaman Pertanggal</h1>
    </div>

    <section class="content">

    <div class="card">
        <div class="card-header">
            <h5 class="card-title">Laporan Pertanggal</h5>
        </div>
        <div class="card-body">
            <a href="{{ route('transactions.index') }}" class="btn btn-dark">Kembali</a>
            <br><br>
            <form action="{{ route('transactions.pertanggal') }}" method="GET">
                <div class="form-group">
                    <label>Tanggal Awal</label>
                    <input name="tgl_awal" type="date" class="form-control" value="{{ old('tgl_awal', \Carbon\Carbon::now()->format('Y-m-d')) }}">
                    @error('tgl_awal')
                    <p>{{ $message }}</p>
                    @enderror
                </div>
                <div class="form-group">
                    <label>Tanggal Akhir</label>
                    <input name="tgl_akhir" type="date" class="form-control" value="{{ old('tgl_akhir', \Carbon\Carbon::now()->format('Y-m-d')) }}">
                    @error('tgl_akhir')
                    <p>{{ $message }}</p>
                    @enderror
                </div>
                <button type="submit" class="btn btn-success">Tampilkan Data</button>
            </form>
        </div>
    </div>
</section>
@endsection
