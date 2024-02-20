@extends('main.home')
@section('content')
<section class="content-header">
    <div class="container-fluid">
        <!-- Page Heading -->
        <h1 class="h3 mb-4 text-gray-800">Halaman Edit User</h1>
    </div>

    <section class="content">

    <div class="card">
        <div class="card-header">
            <h5 class="card-title">Edit Data User</h5>
        </div>
        <div class="card-body">
            <a href="{{ route('user.index') }}" class="btn btn-dark">Kembali</a>
            <br><br>
            <form action="{{ route('user.update', $user->id) }}" method="POST">
                @csrf
                @method('put')
                <div class="form-group">
                    <label>Username</label>
                    <input name="username" type="text" class="form-control" value="{{ $user->username }}" readonly>
                    @error('username')
                        <p>{{ $message }}</p>
                    @enderror
                </div>
                <br>
                <div class="form-group">
                    <label>Nama Lengkap</label>
                    <input name="nama" type="text" class="form-control" placeholder="Ex. Eben" value="{{ $user->nama }}">
                    @error('nama')
                        <p>{{ $message }}</p>
                    @enderror
                </div>
                <br>
                <div class="form-group">
                    <label>Role</label>
                    <select name="role" class="form-control">
                        <option>Pilih Role</option>
                        @if($user->role == 'admin')
                        <option value="admin" selected>Admin</option>
                        <option value="kasir">Kasir</option>
                        <option value="owner">Owner</option>
                        @endif
                        @if($user->role == 'kasir')
                        <option value="admin">Admin</option>
                        <option value="kasir" selected>Kasir</option>
                        <option value="owner">Owner</option>
                        @endif
                        @if($user->role == 'owner')
                        <option value="admin">Admin</option>
                        <option value="kasir">Kasir</option>
                        <option value="owner" selected>Owner</option>
                        @endif
                    </select>
                    @error('role')
                        <p>{{ $message }}</p>
                    @enderror
                </div>
                <br>
                <input type="submit" name="submit" class="btn btn-primary" value="Edit">
            </form>
        </div>
    </div>

</section>
@endsection
