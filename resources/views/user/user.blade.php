@extends('main.home')
@section('content')
<section class="content-header">
    <div class="container-fluid">
        <!-- Page Heading -->
        <h1 class="h3 mb-4 text-gray-800">Halaman User</h1>
    </div>

    <section class="content">

    <div class="card">
        <div class="card-header">
            <h5 class="card-title">Data User</h5>
        </div>
        <div class="card-body">
            @if($message = Session::get('success'))
            <div class="alert alert-success">{{ $message }}</div>
            @endif
            <a href="{{route('user.create')}}" class="btn btn-success">Tambah Data</a>
            <a href="{{url('user/pdf')}}" class="btn btn-warning shadow">Unduh PDF</a>
            <br><br>
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>Username</th>
                        <th>Nama</th>
                        <th>Role</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($user as $data)
                    <tr>
                        <td>{{ $data->username }}</td>
                        <td>{{ $data->nama }}</td>
                        <td>{{ $data->role }}</td>
                        <td>
                            <form action="{{route('user.destroy', $data->id)}}" method="POST">
                                <a href="{{route('user.edit', $data->id)}}" class="btn btn-sm btn-warning">Edit</a>
                                @csrf
                                @method('delete')
                                <a href="{{route('user.changepassword', $data->id)}}" class="btn btn-sm btn-primary shadow">Change Password</a>
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
