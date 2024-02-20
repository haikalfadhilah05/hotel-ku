<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\logM;
use PDF;

class userR extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $logM = logM::create([
            'id_user' => Auth::user()->id,
            'activity' => 'User ' . Auth::user()->username . ' (' . Auth::user()->nama . ') Melihat Halaman Daftar User'
        ]);

        $subtittle = "Daftar User";
        $user = User::all();
        return view('user/user', compact('user', 'subtittle'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $logM = logM::create([
            'id_user' => Auth::user()->id,
            'activity' => 'User ' . Auth::user()->username . ' (' . Auth::user()->nama . ') Melihat Halaman Tambah User'
        ]);

        $subtittle = "Tambah Data User";
        $user = User::all();
        return view('user/user_create', compact('user', 'subtittle'));
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
            'nama' => 'required',
            'username' => 'required|unique:users',
            'password' => 'required',
            'password_confirm' => 'required|same:password',
            'role' => 'required',
        ]);

        $users = new User([
            'nama' => $request->nama,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);
        $users->save();

        $logM = LogM::create([
            'id_user' => Auth::user()->id,
            'activity' => 'User ' . Auth::user()->username . ' (' . Auth::user()->nama . ') Menambah User Baru: ' . $users->username . ' (' . $users->nama . ', Role: ' . $users->role . ')'
        ]);

        return redirect()->route('user.index')->with('success', 'User Berhasil Ditambah');
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
        $logM = logM::create([
            'id_user' => Auth::user()->id,
            'activity' => 'User ' . Auth::user()->username . ' (' . Auth::user()->nama . ') Melihat Halaman Edit User'
        ]);

        $subtittle = "Edit Data User";
        $user = User::find($id);
        return view('user/user_edit', compact('user', 'subtittle'));
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
            'nama' => 'required',
            'username' => 'required',
            'role' => 'required',
        ]);

        $user = User::find($id);

        // Simpan data sebelum diupdate
        $previousData = [
            'nama' => $user->nama,
            'username' => $user->username,
            'role' => $user->role,
        ];

        $user->nama = $request->input('nama');
        $user->username = $request->input('username');
        $user->role = $request->input('role');
        $user->update();

        $logM = LogM::create([
            'id_user' => Auth::user()->id,
            'activity' => 'User ' . Auth::user()->username . ' (' . Auth::user()->nama . ') Mengedit User: ' . $previousData['username'] . ' (' . $previousData['nama'] . ', Role: ' . $previousData['role'] . ') Menjadi: ' . $user->username . ' (' . $user->nama . ', Role: ' . $user->role . ')'
        ]);

        return redirect()->route('user.index')->with('success', 'Users Berhasil Diedit');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::find($id);
        if (!$user) {
            return redirect()->route('user.index')->with('error', 'User tidak ditemukan');
        }

        $logM = LogM::create([
            'id_user' => Auth::user()->id,
            'activity' => 'User ' . Auth::user()->username . ' (' . Auth::user()->nama . ') Menghapus User: ' . $user->username . ' (' . $user->nama . ', Role: ' . $user->role . ')'
        ]);

        $user->delete();

        return redirect()->route('user.index')->with('success', 'User Berhasil Dihapus');
    }

    public function pdf()
    {
        $logM = logM::create([
            'id_user' => Auth::user()->id,
            'activity' => 'User ' . Auth::user()->username . ' (' . Auth::user()->nama . ') Mengunduh PDF Daftar User'
        ]);

        $user = User::all();
        $pdf = PDF::loadview('user/user_pdf', ['user' => $user]);
        return $pdf->stream('user.pdf');
    }

    public function changepassword($id)
    {
        $logM = logM::create([
            'id_user' => Auth::user()->id,
            'activity' => 'User ' . Auth::user()->username . ' (' . Auth::user()->nama . ') Melihat Halaman Change Password'
        ]);

        $subtittle = "Edit Password User";
        $user = User::find($id);
        return view('user/user_changepassword', compact('user', 'subtittle'));
    }

    public function change(Request $request, $id)
    {
        $request->validate([
            'new_password' => 'required',
            'password_confirm' => 'required|same:new_password',
        ]);

        $user = User::where("id", $id)->first();
        $user->update([
            'password' => Hash::make($request->new_password),
        ]);

        $logM = LogM::create([
            'id_user' => Auth::user()->id,
            'activity' => 'User ' . Auth::user()->username . ' (' . Auth::user()->nama . ') Mengubah Password User: ' . $user->username . ' (' . $user->nama . ')'
        ]);

        return redirect()->route('user.index')->with('success', 'Password Berhasil Diedit');
    }

}
