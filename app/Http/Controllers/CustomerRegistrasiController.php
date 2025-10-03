<?php

namespace App\Http\Controllers;

use App\Models\Customers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class CustomerRegistrasiController extends Controller
{
    /** Form registrasi pelanggan */
    public function form()
    {
        return view('public.registrasi');
    }


    /** Simpan pendaftaran pelanggan (status: menunggu) */
    public function simpan(Request $request)
    {
        $data = $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'email'        => 'required|email:rfc,dns|unique:customers,email',
            'nomor_hp'     => 'nullable|string|max:30',
            'alamat'       => 'nullable|string|max:255',
            'kota'         => 'nullable|string|max:100',
            'provinsi'     => 'nullable|string|max:100',
            'kode_pos'     => 'nullable|string|max:10',
        ]);

        $data['status_persetujuan'] = 'menunggu';

        Customers::create($data);

        return redirect()->route('customers.registrasi.form')
            ->with('sukses', 'Pendaftaran berhasil. Menunggu persetujuan admin.');
    }
}
