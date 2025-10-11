<?php

// app/Http/Controllers/Admin/CustomerAdminController.php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class CustomerAdminController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->q);
        $pelanggan = User::where('role','customer')
            ->when($q, fn($x)=>$x->where(function($y) use ($q){
                $y->where('name','like',"%$q%")
                ->orWhere('email','like',"%$q%")
                ->orWhere('no_hp','like',"%$q%")
                ->orWhere('jalan','like',"%$q%")
                ->orWhere('kelurahan','like',"%$q%")
                ->orWhere('kecamatan','like',"%$q%")
                ->orWhere('kota_kab','like',"%$q%")
                ->orWhere('provinsi','like',"%$q%");
            }))
            ->latest()->paginate(12)->withQueryString();
        return view('admin.customer.index', compact('pelanggan','q'));

    }

    public function create()
    {
        return view('admin.customer.create');
    }

    // app/Http/Controllers/Admin/CustomerAdminController.php

    public function store(Request $request)
    {
        $request->validate([
            'name'     => ['required','string','max:150'],
            'email'    => ['required','email','max:150','unique:users,email'],
            'password' => ['required','string','min:6'],
            'no_hp'    => ['nullable','string','max:30','unique:users,no_hp'],

            'jalan' => ['nullable','string','max:255'],
            'rt'           => ['nullable','string','max:5'],
            'rw'           => ['nullable','string','max:5'],
            'kelurahan'    => ['nullable','string','max:100'],
            'kecamatan'    => ['nullable','string','max:100'],
            'kota_kab'     => ['nullable','string','max:100'],
            'provinsi'     => ['nullable','string','max:100'],
            'kode_pos'     => ['nullable','string','max:10'],
        ]);

        User::create([
            'name'  => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role'  => 'customer',
            'no_hp' => $request->no_hp,
            'jalan' => $request->jalan,
            'rt'           => $request->rt,
            'rw'           => $request->rw,
            'kelurahan'    => $request->kelurahan,
            'kecamatan'    => $request->kecamatan,
            'kota_kab'     => $request->kota_kab,
            'provinsi'     => $request->provinsi,
            'kode_pos'     => $request->kode_pos,
        ]);

        return redirect()->route('admin.customer.index')->with('berhasil','Customer berhasil didaftarkan.');
    }


    public function edit(User $user)
    {
        abort_unless($user->role === 'customer', 404);
        return view('admin.customer.edit', ['customer' => $user]);
    }

    public function update(Request $request, User $user)
    {
        abort_unless($user->role === 'customer', 404);

        $request->validate([
            'name'     => ['required','string','max:150'],
            'email'    => ['required','email','max:150','unique:users,email,'.$user->id],
            'password' => ['nullable','string','min:6'],
            'no_hp'    => ['nullable','string','max:30','unique:users,no_hp,'.$user->id],

            'jalan' => ['nullable','string','max:255'],
            'rt'           => ['nullable','string','max:5'],
            'rw'           => ['nullable','string','max:5'],
            'kelurahan'    => ['nullable','string','max:100'],
            'kecamatan'    => ['nullable','string','max:100'],
            'kota_kab'     => ['nullable','string','max:100'],
            'provinsi'     => ['nullable','string','max:100'],
            'kode_pos'     => ['nullable','string','max:10'],
        ]);

        $data = $request->only([
            'name','email','no_hp',
            'jalan','rt','rw','kelurahan','kecamatan','kota_kab','provinsi','kode_pos',
        ]);
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }
        $user->update($data);

        return redirect()->route('admin.customer.index')->with('berhasil','Data Customer diperbarui.');
    }


    public function destroy(User $user)
    {
        abort_unless($user->role === 'customer', 404);
        $user->delete();
        return back()->with('berhasil','Customer dihapus.');
    }
}
