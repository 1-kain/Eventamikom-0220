<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Exception;

class GoogleController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            
            // 1. Cek User via Google ID
            $user = User::where('google_id', $googleUser->id)->first();
            if ($user) {
                Auth::login($user);
                return $this->redirectBasedOnRole($user);
            }

            // 2. Cek User via Email (Jika sebelumnya daftar manual)
            $existingUser = User::where('email', $googleUser->email)->first();
            if ($existingUser) {
                $existingUser->update(['google_id' => $googleUser->id]);
                Auth::login($existingUser);
                return $this->redirectBasedOnRole($existingUser);
            }

            // 3. Buat Akun Baru jika belum terdaftar sama sekali
            $newUser = User::create([
                'name' => $googleUser->name,
                'email' => $googleUser->email,
                'google_id' => $googleUser->id,
                'role' => 'buyer', 
            ]);

            Auth::login($newUser);
            return $this->redirectBasedOnRole($newUser);

        } catch (Exception $e) {
            return redirect('/login')->with('error', 'Autentikasi Google gagal.');
        }
    }

   private function redirectBasedOnRole($user)
{
    if ($user->role === 'superadmin') {
        return redirect()->route('admin.dashboard');
    } elseif ($user->role === 'organizer') {
        return redirect()->route('organizer.dashboard');
    }
    
    // 🌟 JANGKAR PENYELAMAT: 
    // Mengembalikan buyer ke halaman checkout (jika dia dicegat pas beli tiket),
    // atau ke halaman utama '/' jika dia login sukarela via navbar depan.
    return redirect()->intended('/');
}
}