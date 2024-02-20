<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\transactionsM;
use App\Models\productsM;
use App\Models\logM;
use Carbon\Carbon;
use PDF;

class transactionsR extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $log = logM::create([
            'id_user' => Auth::user()->id,
            'activity' => 'User ' . Auth::user()->username . ' (' . Auth::user()->nama . ') Melihat Halaman Daftar Transaksi'
        ]);

        $query = transactionsM::select('transactions.*', 'products.*', 'transactions.id AS id_trans', 'transactions.created_at as createtrans')
            ->join('products', 'products.id', '=', 'transactions.id_produk')
            ->orderBy('transactions.id', 'desc');
        if ($request->filled('start_date') && $request->filled('end_date')) {
            // Jika kedua tanggal diisi, cari transaksi antara rentang tanggal
            $query->whereDate('transactions.created_at', '>=', $request->start_date)
                  ->whereDate('transactions.created_at', '<=', $request->end_date);
        } elseif ($request->filled('start_date')) {
            // Jika hanya tanggal awal diisi, cari transaksi pada tanggal awal
            $query->whereDate('transactions.created_at', '=', $request->start_date);
        } elseif ($request->filled('end_date')) {
            // Jika hanya tanggal akhir diisi, cari transaksi pada tanggal akhir
            $query->whereDate('transactions.created_at', '=', $request->end_date);
        }

        $transactions = $query->get();

        $bookedProducts = productsM::where('status','booked')->get();

        $subtittle = "Daftar Transaksi";
        return view('transactions/transaksi', compact('transactions', 'subtittle', 'bookedProducts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $log = logM::create([
            'id_user' => Auth::user()->id,
            'activity' => 'User ' . Auth::user()->username . ' (' . Auth::user()->nama . ') Melihat Halaman Tambah Transaksi'
        ]);

        $subtittle = "Tambah Data Transaksi";
        $products = productsM::where('status','free')->get();
        return view('transactions/transaksi_create', compact('products', 'subtittle'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'nomor_unik' => 'required',
            'nama_pelanggan' => 'required',
            'jmlh_org' => 'required|integer|min:1',
            'checkin' => 'required',
            'checkout' => 'required',
            'id_produk' => 'required',
            'uang_bayar' => 'required',
            'uang_kembali' => 'required',
        ]);

        $products = productsM::where("id", $request->input('id_produk'))->first();

        if ($request->input('jmlh_org') > $products->kapasitas) {
            return redirect()->back()->withInput()->withErrors(['jmlh_org' => 'Jumlah orang melebihi kapasitas kamar']);
        }

        $checkin = Carbon::parse($request->checkin);
        $checkout = Carbon::parse($request->checkout);
        $durasi_inap = $checkout->diffInDays($checkin);

        $total_harga = $durasi_inap * $products->harga_produk;

        $transactions = new transactionsM;
        $transactions->nomor_unik = $request->input('nomor_unik');
        $transactions->nama_pelanggan = $request->input('nama_pelanggan');
        $transactions->jmlh_org = $request->input('jmlh_org');
        $transactions->checkin = $request->input('checkin');
        $transactions->checkout = $request->input('checkout');
        $transactions->total_harga = $total_harga;
        $transactions->harga_awal = $products->harga_produk;
        $transactions->id_produk = $request->input('id_produk');
        $transactions->uang_bayar = $request->input('uang_bayar');
        $transactions->uang_kembali = $request->input('uang_bayar') - $total_harga;
        $transactions->created_at = Carbon::now();
        $transactions->save();

        $products->status = 'booked';
        $products->save();

        $log = logM::create([
            'id_user' => Auth::user()->id,
            'activity' => 'User ' . Auth::user()->username . ' (' . Auth::user()->nama . ') Menambah Transaksi Baru: ' . 'Nama Pelanggan: ' . $transactions->nama_pelanggan . ', ID Transaksi: ' . $transactions->id
        ]);

        return redirect()->route('transactions.index')->with('success', 'Transaksi berhasil Ditambahkan');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $log = logM::create([
            'id_user' => Auth::user()->id,
            'activity' => 'User ' . Auth::user()->username . ' (' . Auth::user()->nama . ') Melihat halaman Edit Transaksi'
        ]);

        $subtittle = "Edit Data Transactions";
        $transactions = transactionsM::find($id);

        // Mengambil semua produk yang tersedia untuk dipilih
        $availableProducts = productsM::whereNotIn('id', function($query) use ($id) {
            $query->select('id_produk')->from('transactions')->where('id', '!=', $id);
        })->get();

        // Menyimpan data kamar yang sudah dipilih sebelumnya
        $selectedProduct = productsM::find($transactions->id_produk);

        return view('transactions/transaksi_edit', compact('subtittle', 'availableProducts', 'transactions'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nomor_unik' => 'required',
            'nama_pelanggan' => 'required',
            'jmlh_org' => 'required|integer|min:1',
            'checkin' => 'required',
            'checkout' => 'required',
            'id_produk' => 'required',
            'uang_bayar' => 'required',
        ]);

        $transactions = transactionsM::find($id);
        $products = productsM::where("id", $request->input('id_produk'))->first();
        if ($request->input('jmlh_org') > $products->kapasitas) {
            return redirect()->back()->withInput()->withErrors(['jmlh_org' => 'Jumlah orang melebihi kapasitas kamar']);
        }

        $checkin = Carbon::parse($request->checkin);
        $checkout = Carbon::parse($request->checkout);
        $durasi_inap = $checkout->diffInDays($checkin);
        $total_harga = $durasi_inap * $products->harga_produk;
        $selectedProduct = productsM::find($request->input('id_produk'));
        $previousProduct = productsM::find($transactions->id_produk);
        $previousProduct->status = 'free';
        $previousProduct->save();

        // Data sebelum diupdate
        $previousData = [
            'nama_pelanggan' => $transactions->nama_pelanggan,
            'id_transaksi' => $transactions->id
        ];

        $transactions->nomor_unik = $request->input('nomor_unik');
        $transactions->nama_pelanggan = $request->input('nama_pelanggan');
        $transactions->jmlh_org = $request->input('jmlh_org');
        $transactions->checkin = $request->input('checkin');
        $transactions->checkout = $request->input('checkout');
        $transactions->total_harga = $total_harga;
        $transactions->id_produk = $request->input('id_produk');
        $transactions->uang_bayar = $request->input('uang_bayar');
        $transactions->uang_kembali = $request->input('uang_bayar') - $total_harga;
        $transactions->created_at = Carbon::now();
        $transactions->save();

        $selectedProduct->status = 'booked';
        $selectedProduct->save();

        $logM = LogM::create([
            'id_user' => Auth::user()->id,
            'activity' => 'User ' . Auth::user()->username . ' (' . Auth::user()->nama . ') Mengedit Transaksi: ' . 'Nama Pelanggan: ' . $previousData['nama_pelanggan'] . ', ID Transaksi: ' . $previousData['id_transaksi'] . ' Menjadi: ' . 'Nama Pelanggan: ' . $transactions->nama_pelanggan . ', ID Transaksi: ' . $transactions->id
        ]);

        return redirect()->route('transactions.index')->with('success', 'Transaksi berhasil Diedit');
    }



    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $transactions = transactionsM::find($id);
        if (!$transactions) {
            return redirect()->route('transactions.index')->with('error', 'Transaksi tidak ditemukan');
        }

        $products = productsM::find($transactions->id_produk);
        $products->status = 'free';
        $products->save();

        $log = logM::create([
            'id_user' => Auth::user()->id,
            'activity' => 'User ' . Auth::user()->username . ' (' . Auth::user()->nama . ') Menghapus Transaksi: ' . 'Nama Pelanggan: ' . $transactions->nama_pelanggan . ', ID Transaksi: ' . $transactions->id
        ]);

        $transactions->delete();

        return redirect()->route('transactions.index')->with('success', 'Data Transaksi Berhasil Dihapus');
    }

    public function pdf()
    {
        $log = logM::create([
            'id_user' => Auth::user()->id,
            'activity' => 'User ' . Auth::user()->username . ' (' . Auth::user()->nama . ') Mengunduh PDF Daftar Transaksi'
        ]);

        $transactions = transactionsM::select('transactions.*', 'products.*', 'transactions.id AS id_trans', 'transactions.created_at AS createtrans')->join('products', 'products.id',  '=', 'transactions.id_produk')->orderBy('transactions.created_at', 'desc')->get();
        $totalIncome = transactionsM::sum('total_harga');
        $pdf = PDF::loadview('transactions/transaksi_pdf', ['transactions' => $transactions, 'totalIncome' => $totalIncome]);
        return $pdf->stream('transactions.pdf');
    }

    public function pdf2($id)
    {
        $log = logM::create([
            'id_user'=> Auth::user()->id,
            'activity'=> 'User ' . Auth::user()->username . ' (' . Auth::user()->nama . ') Mencetak PDF Struk'
        ]);
        // Ambil data transaksi dan produk berdasarkan ID
        $data = transactionsM::select('transactions.*', 'products.*', 'transactions.id AS id_trans', 'transactions.created_at AS createtrans')->join('products', 'products.id', '=', 'transactions.id_produk')->where('transactions.id', $id)->first();

        if ($data) {
            // Jika data ditemukan, buat PDF
            $pdf = PDF::loadView('transactions/transaksi_struk', ['data' => $data]);
            return $pdf->stream('transactions.struk' . $id . '.pdf');
        } else {
            // Jika data tidak ditemukan, Anda dapat mengembalikan respons yang sesuai, misalnya, halaman 404.
            return response('Data transaksi tidak ditemukan', 404);
        }
    }

    public function tgl()
    {
        $subtittle = "Transaksi PDF Berdasarkan Tanggal";
        return view('transactions/transaksi_tgl', compact('subtittle'));
    }

    public function pertanggal(Request $request)
    {
        $log = logM::create([
            'id_user' => Auth::user()->id,
            'activity' => 'User ' . Auth::user()->username . ' (' . Auth::user()->nama . ') Mengunduh PDF Daftar Transaksi Pertanggal'
        ]);
        // Gunakan tanggal yang diterima sesuai kebutuhan
        $tgl_awal = $request->input('tgl_awal');
        $tgl_akhir = $request->input('tgl_akhir');

        // Jika tanggal awal dan tanggal akhir sama, atur rentang tanggal agar mencakup seluruh hari tersebut
        if ($tgl_awal == $tgl_akhir) {
            $tgl_awal = Carbon::parse($tgl_awal)->startOfDay();
            $tgl_akhir = Carbon::parse($tgl_akhir)->endOfDay();
        }

        // Lakukan pengolahan data sesuai rentang tanggal yang diinginkan
        $transactions = transactionsM::select('transactions.*', 'products.*', 'transactions.id AS id_tran', 'transactions.created_at AS createtrans')
            ->join('products', 'products.id', '=', 'transactions.id_produk')
            ->whereBetween('transactions.created_at', [$tgl_awal, $tgl_akhir])
            ->get();

        // Calculate total income
        $total_income = 0;
        foreach ($transactions as $transaction) {
            $total_income += $transaction->total_harga;
        }

        // Pass the total income to the view
        $pdf = PDF::loadview('transactions/transaksi_pertgl', ['transactions' => $transactions, 'total_income' => $total_income]);
        return $pdf->stream('stgl.pdf');
    }

    public function updateProductStatus($id)
    {
        $products = productsM::find($id);
        if ($products) {
            $products->status = 'free';
            $products->save();
            return redirect()->back()->with('success', 'Status produk berhasil diperbarui');
        }
        return redirect()->back()->with('error', 'Gagal memperbarui status produk');
    }

}
