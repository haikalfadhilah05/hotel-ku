@extends('main.home')
@section('content')
<section class="content-header">
    <div class="container-fluid">
        <!-- Page Heading -->
        <h1 class="h3 mb-4 text-gray-800">Halaman Log</h1>
    </div>

    <section class="content">

    <div class="card">
        <div class="card-header">
            <h5 class="card-title">Data Log</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('log.index') }}" method="get" class="form-inline">
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
                <a href="{{ route('log.index') }}" class="btn btn-danger">
                    <i class="fas fa-undo"></i>
                </a>
            </form>
            <br>
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>no</th>
                        <th>Nama User</th>
                        <th>Aktifitas</th>
                        <th>Tanggal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no_log = 1 ?>
                    @foreach ($log as $data)
                    <tr>
                        <td>{{ $no_log }}</td>
                        <td>{{ $data->nama }}</td>
                        <td>{{ $data->activity }}</td>
                        <td>{{ $data->created_at->format('d-m-Y H:i:s') }}</td>
                    </tr>
                    <?php $no_log++ ?>
                    @endforeach
                </tbody>

            </table>
        </div>
    </div>

</section>
@endsection
