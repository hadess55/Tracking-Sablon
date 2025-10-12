<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;

class RegisteredUserController extends Controller
{
    /**
     * Tampilkan form register.
     */
    public function create()
    {
        return view('auth.register');
    }

    /**
     * Simpan user baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'      => ['required', 'string', 'max:191'],
            'email'     => ['required', 'string', 'email', 'max:191', 'unique:users'],
            'password'  => ['required', 'confirmed', Rules\Password::defaults()],
            'no_hp'     => ['required', 'string', 'max:30'],

            // alamat
            'jalan'     => ['nullable', 'string', 'max:191'],
            'rt'        => ['nullable', 'string', 'max:10'],
            'rw'        => ['nullable', 'string', 'max:10'],
            'kelurahan' => ['nullable', 'string', 'max:191'],
            'kecamatan' => ['nullable', 'string', 'max:191'],
            'kota_kab'  => ['nullable', 'string', 'max:191'],
            'provinsi'  => ['nullable', 'string', 'max:191'],
            'kode_pos'  => ['nullable', 'string', 'max:10'],
        ]);

        $user = User::create([
            'name'      => $validated['name'],
            'email'     => $validated['email'],
            'password'  => Hash::make($validated['password']),
            'role'      => 'customer',

            'no_hp'     => $validated['no_hp'],
            'jalan'     => $validated['jalan'] ?? null,
            'rt'        => $validated['rt'] ?? null,
            'rw'        => $validated['rw'] ?? null,
            'kelurahan' => $validated['kelurahan'] ?? null,
            'kecamatan' => $validated['kecamatan'] ?? null,
            'kota_kab'  => $validated['kota_kab'] ?? null,
            'provinsi'  => $validated['provinsi'] ?? null,
            'kode_pos'  => $validated['kode_pos'] ?? null,
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect()->intended(
    Auth::user()->role === 'admin'
        ? route('dashboard')       
        : route('pesanan.index')    
);
    }
}
