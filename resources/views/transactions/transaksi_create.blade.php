@extends('main.home')
@section('content')
<section class="content-header">
    <div class="container-fluid">
        <!-- Page Heading -->
        <h1 class="h3 mb-4 text-gray-800">Halaman Tambah Transaksi</h1>
    </div>

    <section class="content">

    <div class="card">
        <div class="card-header">
            <h5 class="card-title">Tambah Data Transaksi</h5>
        </div>
        <div class="card-body">
            <a href="{{ route('transactions.index') }}" class="btn btn-dark">Kembali</a>
                <br><br>
                <form action="{{ route('transactions.store') }}" method="POST">
                @csrf
                <div>
                    <label>Nomor Unik</label>
                    <input name="nomor_unik" type="number" class="form-control" value="{{ random_int(1000000000,9999999999); }}"readonly>
                    @error('nomor_unik')
                    <p>{{ $message }}</p>
                    @enderror
                </div>
                <br>
                <div>
                    <label>Nama Pelanggan</label>
                    <input name="nama_pelanggan" type="text" class="form-control" placeholder="...">
                    @error('nama_pelanggan')
                    <p>{{ $message }}</p>
                    @enderror
                </div>
                <br>
                <div>
                    <label>Jumlah orang</label>
                    <input name="jmlh_org" type="text" class="form-control" placeholder="...">
                    @error('jmlh_org')
                    <p>{{ $message }}</p>
                    @enderror
                </div>
                <br>
                <div>
                    <label for="search_produk">Cari Kamar</label>
                    <input type="text" id="search_produk" class="form-control" placeholder="Masukkan kata kunci">
                </div>
                <br>
                <div>
                    <label>Lantai - Nomor kamar - Nama kamar - Harga kamar/Malam - Kapasitas kamar</label>
                    <select name="id_produk" id="id_produk" class="form-control" required>
                        <option value="">-Pilih kamar-</option>
                        @foreach ($products as $data)
                            <option value="{{ $data->id }}" data-harga="{{ $data->harga_produk }}" data-fasilitas="{{ $data->fasilitas }}">
                                {{ $data->lantai_kamar }} - {{ $data->nomor_kamar }} - {{ $data->nama_produk }} - {{ $data->harga_produk }} - {{ $data->kapasitas }}
                            </option>
                        @endforeach
                    </select>
                    @error('id_produk')
                    <p>{{ $message }}</p>
                    @enderror
                </div>
                <br>
                <div>
                    <label>Fasilitas kamar</label>
                    <input name="fasilitas" type="text" class="form-control" readonly>
                    @error('fasilitas')
                    <p>{{ $message }}</p>
                    @enderror
                </div>
                <br>
                <div>
                    <label>Check-In</label>
                    <input name="checkin" id="checkin" type="date" class="form-control">
                    @error('checkin')
                    <p>{{ $message }}</p>
                    @enderror
                </div>
                <br>
                <div>
                    <label>Check-Out</label>
                    <input name="checkout" id="checkout" type="date" class="form-control">
                    @error('checkout')
                    <p>{{ $message }}</p>
                    @enderror
                </div>
                <br>
                <div>
                    <label>Total Harga</label>
                    <input name="total_harga" id="total_harga" type="number" class="form-control" readonly>
                    @error('total_harga')
                    <p>{{ $message }}</p>
                    @enderror
                </div>
                <br>
                <div>
                    <label>Uang Bayar</label>
                    <input name="uang_bayar" type="number" class="form-control" placeholder="...">
                    @error('uang_bayar')
                    <p>{{ $message }}</p>
                    @enderror
                </div>
                <br>
                <div>
                    <label>Uang kembali</label>
                    <input name="uang_kembali" type="number" class="form-control" readonly>
                    @error('uang_kembali')
                    <p>{{ $message }}</p>
                    @enderror
                </div>
                <br>
                <input type="submit" name="submit" class="btn btn-primary" value="Tambah">
            </form>
        </div>
    </div>
</section>

<script>
    // Ambil elemen-elemen yang diperlukan
    var id_produk = document.getElementById('id_produk');
    var checkin = document.getElementById('checkin');
    var checkout = document.getElementById('checkout');
    var total_harga = document.getElementById('total_harga');
    var uang_bayar = document.getElementsByName('uang_bayar')[0]; // Ambil elemen input uang bayar
    var uang_kembali = document.getElementsByName('uang_kembali')[0]; // Ambil elemen input uang kembali

    // Tambahkan event listener untuk setiap perubahan pada form
    id_produk.addEventListener('change', function() {
        hitungTotalHarga();
        tampilkanFasilitas();
    });
    checkin.addEventListener('change', hitungTotalHarga);
    checkout.addEventListener('change', hitungTotalHarga);
    uang_bayar.addEventListener('input', hitungUangKembali); // Gunakan event input untuk mendeteksi perubahan saat pengguna mengetik

    function hitungTotalHarga() {
        var harga_produk = id_produk.options[id_produk.selectedIndex].getAttribute('data-harga');
        var checkinDate = new Date(checkin.value);
        var checkoutDate = new Date(checkout.value);
        var durasi = (checkoutDate - checkinDate) / (1000 * 60 * 60 * 24); // Durasi dalam hari
        var total = durasi * harga_produk;
        total_harga.value = total;
    }

    function hitungUangKembali() {
        var totalHarga = parseFloat(total_harga.value);
        var uangBayar = parseFloat(uang_bayar.value);
        var uangKembali = uangBayar - totalHarga;
        uang_kembali.value = uangKembali;
    }

    var search_produk = document.getElementById('search_produk');
    search_produk.addEventListener('input', function() {
        var keyword = this.value.toLowerCase();
        var options = id_produk.getElementsByTagName('option');

        for (var i = 0; i < options.length; i++) {
            var text = options[i].text.toLowerCase();
            if (text.includes(keyword)) {
                options[i].style.display = 'block';
            } else {
                options[i].style.display = 'none';
            }
        }
    });

    function tampilkanFasilitas() {
        var selectedOption = id_produk.options[id_produk.selectedIndex];
        var fasilitasInput = document.getElementsByName('fasilitas')[0];
        fasilitasInput.value = selectedOption.getAttribute('data-fasilitas');
    }
</script>
@endsection
