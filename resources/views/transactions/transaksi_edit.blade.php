@extends('main.home')
@section('content')
<section class="content-header">
    <div class="container-fluid">
        <!-- Page Heading -->
        <h1 class="h3 mb-4 text-gray-800">Halaman Edit Transaksi</h1>
    </div>

    <section class="content">

    <div class="card">
        <div class="card-header">
            <h5 class="card-title">Edit Data Transaksi</h5>
        </div>
        <div class="card-body">
            <a href="{{ route('transactions.index') }}" class="btn btn-dark">Kembali</a>
                <br><br>
                <form action="{{ route('transactions.update', $transactions->id) }}" method="POST">
                @csrf
                @method('put')
                <div class="form-group">
                    <label>Nomor Unik</label>
                    <input name="nomor_unik" type="number" class="form-control" value="{{ $transactions->nomor_unik }}" readonly>
                    @error('nomor_unik')
                        <p>{{ $message }}</p>
                    @enderror
                </div>
                <br>
                <div class="form-group">
                    <label>Nama Pelanggan</label>
                    <input name="nama_pelanggan" type="text" class="form-control" value="{{ $transactions->nama_pelanggan }}">
                    @error('nama_pelanggan')
                        <p>{{ $message }}</p>
                    @enderror
                </div>
                <br>
                <div class="form-group">
                    <label>Jumlah orang</label>
                    <input name="jmlh_org" type="text" class="form-control" value="{{ $transactions->jmlh_org }}">
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
                <div class="form-group">
                    <label>Lantai - Nomor Kamar - Nama Kamar - Harga Kamar/Malam - Kapasitas kamar</label>
                    <select name="id_produk" id="id_produk" class="form-control" required>
                        <option>Pilih Kamar</option>
                        @foreach ($availableProducts as $data)
                            <option value="{{ $data->id }}" data-harga="{{ $data->harga_produk }}" data-fasilitas="{{ $data->fasilitas }}"
                                {{ $data->id == $transactions->id_produk ? 'selected' : '' }}>
                                {{ $data->lantai_kamar }} - {{ $data->nomor_kamar }} - {{ $data->nama_produk }} - {{ $data->harga_produk }} - {{ $data->kapasitas }}
                            </option>
                        @endforeach
                    </select>
                    @error('id_produk')
                        <p>{{ $message }}</p>
                    @enderror
                </div>

                <br>
                <div class="form-group">
                    <label>Fasilitas kamar</label>
                    <input name="fasilitas" type="text" class="form-control" value="{{ $data->fasilitas }}" readonly>
                    @error('fasilitas')
                        <p>{{ $message }}</p>
                    @enderror
                </div>
                <br>
                <div class="form-group">
                    <label>Check-In</label>
                    <input name="checkin" id="checkin" type="date" class="form-control" value="{{ $transactions->checkin }}">
                    @error('checkin')
                        <p>{{ $message }}</p>
                    @enderror
                </div>
                <br>
                <div class="form-group">
                    <label>Check-Out</label>
                    <input name="checkout" id="checkout" type="date" class="form-control" value="{{ $transactions->checkout }}">
                    @error('checkout')
                        <p>{{ $message }}</p>
                    @enderror
                </div>
                <br>
                <div>
                    <label>Total Harga</label>
                    <input name="total_harga" id="total_harga" type="number" class="form-control" value="{{ $transactions->total_harga }}" readonly>
                    @error('total_harga')
                    <p>{{ $message }}</p>
                    @enderror
                </div>
                <br>
                <div class="form-group">
                    <label>Uang Bayar</label>
                    <input name="uang_bayar" type="number" class="form-control" value="{{ $transactions->uang_bayar }}">
                    @error('uang_bayar')
                        <p>{{ $message }}</p>
                    @enderror
                </div>
                <br>
                <div class="form-group">
                    <label>Uang Kembali</label>
                    <input name="uang_kembali" type="number" class="form-control" value="{{ $transactions->uang_kembali }}" readonly>
                    @error('uang_kembali')
                        <p>{{ $message }}</p>
                    @enderror
                </div>
                <br>
                <input type="submit" name="submit" class="btn btn-success" value="edit">
            </form>
        </div>
    </div>
</section>

<script>
    // Ambil elemen-elemen yang diperlukan
    const id_produk = document.getElementById('id_produk');
    const fasilitas = document.getElementsByName('fasilitas')[0];
    const checkin = document.getElementById('checkin');
    const checkout = document.getElementById('checkout');
    const total_harga = document.getElementById('total_harga');
    const uang_bayar = document.getElementsByName('uang_bayar')[0];
    const uang_kembali = document.getElementsByName('uang_kembali')[0];

    // Tambahkan event listener untuk setiap perubahan pada form
    id_produk.addEventListener('change', function() {
        updateTotalHarga();
        updateFasilitas();
    });
    checkin.addEventListener('change', updateTotalHarga);
    checkout.addEventListener('change', updateTotalHarga);
    uang_bayar.addEventListener('input', hitungUangKembali);
    total_harga.addEventListener('input', hitungUangKembali);

    function updateFasilitas() {
        var selectedProduk = id_produk.options[id_produk.selectedIndex];
        fasilitas.value = selectedProduk.dataset.fasilitas; // Update the value of the input field
    }

    function updateTotalHarga() {
        var harga_produk = id_produk.options[id_produk.selectedIndex].dataset.harga;
        var checkinDate = new Date(checkin.value);
        var checkoutDate = new Date(checkout.value);
        var durasi = (checkoutDate - checkinDate) / (1000 * 60 * 60 * 24); // Durasi dalam hari
        var total = durasi * harga_produk;
        total_harga.value = total;
        hitungUangKembali(); // Call hitungUangKembali() to update uang_kembali when total_harga changes
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

    function hitungUangKembali() {
        var totalHarga = parseFloat(total_harga.value);
        var uangBayar = parseFloat(uang_bayar.value);
        if (!isNaN(totalHarga) && !isNaN(uangBayar)) {
            var uangKembali = uangBayar - totalHarga;
            uang_kembali.value = uangKembali;
        }
    }

    window.addEventListener('load', updateFasilitas); // Call updateFasilitas() when the page loads
</script>
@endsection
