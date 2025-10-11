<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /** Tampilkan form login (GET /login) */
    public function create(): View
    {
        return view('auth.login'); // pastikan view ini ada
    }

    /** Proses login (POST /login) */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();
        $request->session()->regenerate();

        $user = $request->user();

        // Admin -> dashboard admin, Customer -> pesanan
        if ($user && $user->role === 'admin') {
            return redirect()->intended(route('dashboard'));
        }

        return redirect()->intended(route('pesanan.index'));
    }

    /** Logout (POST /logout) */
    public function destroy(Request $request): RedirectResponse
    {
        auth()->guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
