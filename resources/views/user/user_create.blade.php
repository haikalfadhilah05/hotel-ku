@extends('main.home')
@section('content')
<section class="content-header">
    <div class="container-fluid">
        <!-- Page Heading -->
        <h1 class="h3 mb-4 text-gray-800">Halaman Tambah User</h1>
    </div>

    <section class="content">

    <div class="card">
        <div class="card-header">
            <h5 class="card-title">Tambah Data User</h5>
        </div>
        <div class="card-body">
            <a href="{{ route('user.index') }}" class="btn btn-dark">Kembali</a>
                <br><br>
                <form action="{{ route('user.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label>username</label>
                    <input name="username" type="text" class="form-control" placeholder="...">
                    @error('username')
                    <p>{{ $message }}</p>
                    @enderror
                </div>
                <br>
                <div class="form-group">
                    <label>Nama</label>
                    <input name="nama" type="text" class="form-control" placeholder="...">
                    @error('nama')
                        <p>{{ $message }}</p>
                    @enderror
                </div>
                <br>
                <div class="form-group">
                    <label>Password</label>
                    <input name="password" type="password" class="form-control">
                    @error('password')
                        <p>{{ $message }}</p>
                    @enderror
                </div>
                <br>
                <div class="form-group">
                    <label>Ulangi password</label>
                    <input name="password_confirm" type="password" class="form-control">
                    @error('password_confirm')
                        <p>{{ $message }}</p>
                    @enderror
                </div>
                <br>
                <div class="form-group">
                    <label>Role</label>
                    <select name="role" class="form-control">
                        <option>Pilih Role</option>
                        <option value="admin">Admin</option>
                        <option value="kasir">Kasir</option>
                        <option value="owner">Owner</option>
                    </select>
                    @error('role')
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
