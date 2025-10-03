<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminAuthController extends Controller
{
    /** Tampilkan form login admin */
    public function showLoginForm()
    {
        // Kalau sudah login, langsung ke dashboard
        if (Auth::check()) {
            return redirect()->route('admin.produksi.index');
        }

        return view('auth.admin-login');
    }

    /** Proses login */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required','email'],
            'password' => ['required'],
            'remember' => ['nullable','boolean'],
        ]);

        $remember = (bool) ($credentials['remember'] ?? false);
        unset($credentials['remember']);

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            // Hanya izinkan akun admin
            if (! Auth::user()->is_admin) {
                Auth::logout();

                return back()
                    ->withErrors(['email' => 'Akun ini tidak memiliki akses admin.'])
                    ->onlyInput('email');
            }

            return redirect()->intended(route('admin.produksi.index'))
                ->with('sukses', 'Selamat datang!');
        }

        return back()
            ->withErrors(['email' => 'Email atau password salah.'])
            ->onlyInput('email');
    }

    /** Logout */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login')
            ->with('sukses', 'Anda telah keluar.');
    }
}

