<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\logM;

class logC extends Controller
{
    public function index(Request $request)
    {
        $log = logM::create([
            'id_user' => Auth::user()->id,
            'activity' => 'User ' . Auth::user()->username . ' (' . Auth::user()->nama . ') Melihat Halaman Log'
        ]);

        // $log = logM::select('users.*', 'logs.*')->join('users', 'users.id', '=', 'logs.id_user')->orderBy('logs.created_at', 'desc')->get();

        $query = LogM::select('users.*', 'logs.*', 'users.id AS id_user')
        ->join('users', 'users.id', '=', 'logs.id_user', )
        ->orderBy('logs.created_at', 'desc');
        if ($request->filled('start_date') && $request->filled('end_date')) {
            // Jika kedua tanggal diisi, cari transaksi antara rentang tanggal
            $query->whereDate('logs.created_at', '>=', $request->start_date)
            ->whereDate('logs.created_at', '<=', $request->end_date);
        } elseif ($request->filled('start_date')) {
            // Jika hanya tanggal awal diisi, cari transaksi pada tanggal awal
            $query->whereDate('logs.created_at', '=', $request->start_date);
        } elseif ($request->filled('end_date')) {
            // Jika hanya tanggal akhir diisi, cari transaksi pada tanggal akhir
            $query->whereDate('logs.created_at', '=', $request->end_date);
        }
        $log = $query->get();

        $subtittle = "Daftar Aktivitas";
        return view('log/log', compact('log', 'subtittle'));
    }
}
