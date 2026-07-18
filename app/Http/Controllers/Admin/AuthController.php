<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin() {
        return view('auth.login');
    }

    public function login(Request $request) {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            
            $role = Auth::user()->role;
            
            // 🌟 REDIRECT MULTI-TENANT BERDASARKAN RUANGAN
            if ($role === 'superadmin') {
                return redirect()->route('admin.dashboard');
            } elseif ($role === 'organizer') {
                return redirect()->route('organizer.dashboard'); 
            }
            
            // Pengamanan tambahan jika ada Role di luar skenario masuk ke area admin
            Auth::logout();
            return back()->withErrors([
                'email' => 'Akses ditolak. Akun Anda tidak memiliki otoritas di area ini.',
            ]);
        }

        return back()->withErrors([
            'email' => 'Email atau Password yang Anda berikan tidak terdaftar di database kami.',
        ]);
    }

    public function logout(Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}