<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\logM;
use App\Models\ProductsM;
use App\Models\transactionsM;
use App\Models\User;

class dashboardC extends Controller
{
    public function index()
    {
        $log = logM::create([
            'id_user' => Auth::user()->id,
            'activity' => 'User Melihat Halaman Dashboard'
        ]);
        $totaluser = User::count();
        $totaltransactions = transactionsM::count();
        $totalproducts = productsM::where('status', 'free')->count();
        $totaluangbayar = DB::table('transactions')->sum('total_harga');
        $subtittle = "Dashboard";
        return view('main/dashboard', compact('log','totaluser','totaltransactions','totalproducts','totaluangbayar', 'subtittle'));
    }
}
