<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\User;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login_admin');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // Redirect berdasarkan role
            $user = Auth::user();
            $role = $user->role;

            if ($role) {
                switch ($role->nama_role) {
                    case 'Administrator':
                        return redirect()->route('admin.dashboard');
                    case 'Dokter':
                        return redirect()->route('dokter.dashboard');
                    case 'Perawat':
                        return redirect()->route('perawat.dashboard');
                    case 'Resepsionis':
                        return redirect()->route('resepsionis.dashboard');
                    case 'Pemilik':
                        return redirect()->route('pemilik.dashboard');
                    default:
                        return redirect()->route('admin.dashboard');
                }
            }

            return redirect()->route('admin.dashboard');
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->withInput($request->only('email'));
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
