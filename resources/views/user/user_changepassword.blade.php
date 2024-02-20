@extends('main.home')
@section('content')
<section class="content-header">
    <div class="container-fluid">
        <!-- Page Heading -->
        <h1 class="h3 mb-4 text-gray-800">Halaman Edit Password</h1>
    </div>

    <section class="content">

    <div class="card">
        <div class="card-header">
            <h5 class="card-title">Edit Password User</h5>
        </div>
        <div class="card-body">
            <a href="{{ route('user.index') }}" class="btn btn-dark">Kembali</a>
            <br><br>
            <form action="{{ route('user.change', $user->id) }}" method="POST">
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
                    <label>Role</label>
                    <input name="role" type="text" class="form-control" value="{{ $user->role }}" readonly>
                    @error('role')
                        <p>{{ $message }}</p>
                    @enderror
                </div>
                <br>
                <div class="form-group">
                    <label>Password Baru</label>
                    <input name="new_password" type="password" class="form-control">
                    @error('new_password')
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
                <input type="submit" name="submit" class="btn btn-primary" value="Simpan password baru">
            </form>
        </div>
    </div>

</section>
@endsection
