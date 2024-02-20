<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\productsM;
use App\Models\logM;
use PDF;

class productsR extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $log = logM::create([
            'id_user' => Auth::user()->id,
            'activity' => 'User ' . Auth::user()->username . ' (' . Auth::user()->nama . ') Melihat Halaman Daftar Produk'
        ]);

        $subtittle = "Daftar Kamar";
        $products = productsM::all();
        return view('products/kamar', compact('products', 'subtittle'));
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
            'activity' => 'User ' . Auth::user()->username . ' (' . Auth::user()->nama . ') Melihat Halaman Tambah Produk'
        ]);

        $subtittle = "Tambah Data Kamar";
        return view("products/kamar_create", compact('subtittle'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $log = logM::create([
            'id_user' => Auth::user()->id,
            'activity' => 'User ' . Auth::user()->username . ' (' . Auth::user()->nama . ') Melakukan Tambah Produk - Nama Produk: ' .  $request->input('nama_produk') . ' - Lantai: ' . $request->input('lantai_kamar') . ' - Nomor Kamar: ' . $request->input('nomor_kamar')
        ]);

        $request->validate([
            'nomor_kamar' => 'required|unique:products,nomor_kamar,NULL,id|max:255',
            'lantai_kamar' => 'required',
            'nama_produk' => 'required',
            'harga_produk' => 'required',
            'fasilitas' => 'required',
            'kapasitas' => 'required',
        ], [
            'nomor_kamar.unique' => 'Nomor kamar sudah ada',
        ]);

        $data = [
            'nomor_kamar' => $request->input('nomor_kamar'),
            'lantai_kamar' => $request->input('lantai_kamar'),
            'nama_produk' => $request->input('nama_produk'),
            'harga_produk' => $request->input('harga_produk'),
            'fasilitas' => $request->input('fasilitas'),
            'kapasitas' => $request->input('kapasitas'),
        ];

        productsM::create($data);

        return redirect()->route('products.index')->with('success', 'Kamar Berhasil Ditambah');
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
            'activity' => 'User ' . Auth::user()->username . ' (' . Auth::user()->nama . ') Melihat Halaman Edit Produk'
        ]);

        $data = productsM::find($id);
        $subtittle = "Edit Data Kamar";
        return view("products/kamar_edit", compact('data', 'subtittle'));
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
        $product = productsM::find($id);

        $previousData = [
            'nomor_kamar' => $product->nomor_kamar,
            'lantai_kamar' => $product->lantai_kamar,
            'nama_produk' => $product->nama_produk,
            'harga_produk' => $product->harga_produk,
            'fasilitas' => $product->fasilitas,
            'kapasitas' => $product->kapasitas,
        ];

        $log = LogM::create([
            'id_user' => Auth::user()->id,
            'activity' => 'User ' . Auth::user()->username . ' (' . Auth::user()->nama . ') Melakukan Edit Produk: ' . 'Nama Produk: ' . $previousData['nama_produk'] . ' - Lantai: ' . $previousData['lantai_kamar'] . ' - Nomor Kamar: ' . $previousData['nomor_kamar'] . ' Menjadi: ' . 'Nama Produk: ' . $request->input('nama_produk') . ' - Lantai: ' . $request->input('lantai_kamar') . ' - Nomor Kamar: ' . $request->input('nomor_kamar')
        ]);

        $request->validate([
            'nomor_kamar' => 'required',
            'lantai_kamar' => 'required',
            'nama_produk' => 'required',
            'harga_produk' => 'required',
            'fasilitas' => 'required',
            'kapasitas' => 'required',
        ]);

        $product->nomor_kamar = $request->input('nomor_kamar');
        $product->lantai_kamar = $request->input('lantai_kamar');
        $product->nama_produk = $request->input('nama_produk');
        $product->harga_produk = $request->input('harga_produk');
        $product->fasilitas = $request->input('fasilitas');
        $product->kapasitas = $request->input('kapasitas');
        $product->save();

        return redirect()->route('products.index')->with('success', 'Kamar Berhasil Diedit');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $products = productsM::find($id);

        if (!$products) {
            return redirect()->route('products.index')->with('error', 'Produk tidak ditemukan');
        }

        $log = logM::create([
            'id_user' => Auth::user()->id,
            'activity' => 'User ' . Auth::user()->username . ' (' . Auth::user()->nama . ') Menghapus Produk: ' . $products->nama_produk . ' - Lantai: ' . $products->lantai_kamar . ' - Nomor Kamar: ' . $products->nomor_kamar
        ]);

        $products->delete();

        productsM::where('id',$id)->delete();
        return redirect()->route('products.index')->with('success', 'Kamar Berhasil Dihapus');
    }

    public function pdf()
    {
        $log = logM::create([
            'id_user' => Auth::user()->id,
            'activity' => 'User ' . Auth::user()->username . ' (' . Auth::user()->nama . ') Mengunduh PDF Daftar Produk'
        ]);

        $products = productsM::orderBy('products.nomor_kamar', 'asc')->get();
        $pdf = PDF::loadview('products/kamar_pdf', ['products' => $products]);
        return $pdf->stream('products.pdf');
    }
}
