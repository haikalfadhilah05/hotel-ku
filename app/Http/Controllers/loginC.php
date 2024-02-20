<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\logM;

class loginC extends Controller
{
    public function login()
    {
        return view('log/login');
    }

    public function login_action(Request $request)
    {

        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);
        if (Auth::attempt(['username' => $request->username, 'password' => $request->password])) {
            $log = logM::create([
                'id_user' => Auth::user()->id,
                'activity' => 'User ' . Auth::user()->username . ' (' . Auth::user()->nama . ') Melakukan Login'
            ]);

            $request->session()->put('log', $log);
            $request->session()->regenerate();
            return redirect()->intended('/dashboard');
        }

        return back()->withErrors([
            'password' => 'Username Atau Password Salah',
        ]);

    }

    public function logout(Request $request)
    {
        $log = logM::create([
            'id_user' => Auth::user()->id,
            'activity' => 'User ' . Auth::user()->username . ' (' . Auth::user()->nama . ') Melakukan Logout'
        ]);

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}
