<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCustomerRequest;
use App\Http\Requests\UpdateCustomerRequest;
use App\Models\Customers;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
{
    public function create()
    {
        $customer = new Customers(['status_persetujuan' => 'menunggu']);
        return view('admin.customers.create', compact('customer'));
    }

    public function store(StoreCustomerRequest $request)
    {
        $data = $request->validated();

        // metadata persetujuan
        if ($data['status_persetujuan'] === 'disetujui') {
            $data['disetujui_oleh'] = Auth::id();
            $data['disetujui_pada'] = now();
        } else {
            $data['disetujui_oleh'] = null;
            $data['disetujui_pada'] = null;
        }

        $customer = Customers::create($data);

        return redirect()->route('admin.customers.show', $customer)
            ->with('sukses', 'Pelanggan berhasil ditambahkan.');
    }

    public function show(Customers $customer)
    {
        $produksi = $customer->produksi()->latest()->paginate(10);
        return view('admin.customers.show', compact('customer','produksi'));
    }

    public function edit(Customers $customer)
    {
        return view('admin.customers.edit', compact('customer'));
    }

    public function update(UpdateCustomerRequest $request, Customers $customer)
    {
        $data = $request->validated();

        if ($data['status_persetujuan'] === 'disetujui') {
            $data['disetujui_oleh'] = $customer->disetujui_oleh ?: Auth::id();
            $data['disetujui_pada'] = $customer->disetujui_pada ?: now();
        }
        if ($data['status_persetujuan'] === 'menunggu') {
            $data['disetujui_oleh'] = null;
            $data['disetujui_pada'] = null;
        }

        $customer->update($data);

        return redirect()->route('admin.customers.show', $customer)
            ->with('sukses', 'Data pelanggan diperbarui.');
    }

    public function destroy(Customers $customer)
    {
        $customer->delete();
        return redirect()->route('admin.customers.index')->with('sukses', 'Pelanggan dihapus.');
    }
}
