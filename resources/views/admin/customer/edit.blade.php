@extends('layouts.admin')
@section('header','Edit Customer')

@section('content')
@if ($errors->any())
  <div class="mb-4 p-3 rounded bg-red-100 text-red-800">
    <ul class="list-disc ml-5">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
  </div>
@endif

<form action="{{ route('admin.customer.update', $customer) }}" method="POST" class="bg-white p-4 rounded shadow max-w-3xl">
  @csrf @method('PUT')

  <div class="grid md:grid-cols-2 gap-4">
    <div>
      <label class="block font-medium mb-1">Nama</label>
      <input name="name" value="{{ old('name',$customer->name) }}" class="border p-2 w-full rounded" required>
    </div>
    <div>
      <label class="block font-medium mb-1">Email</label>
      <input type="email" name="email" value="{{ old('email',$customer->email) }}" class="border p-2 w-full rounded" required>
    </div>
    <div>
      <label class="block font-medium mb-1">Password (opsional)</label>
      <input type="password" name="password" class="border p-2 w-full rounded" placeholder="Biarkan kosong jika tidak diganti">
    </div>
    <div>
      <label class="block font-medium mb-1">No HP</label>
      <input name="no_hp" value="{{ old('no_hp',$customer->no_hp) }}" class="border p-2 w-full rounded">
    </div>
  </div>

  <hr class="my-4">

  <h3 class="font-semibold mb-2">Alamat Lengkap</h3>
  <div class="grid md:grid-cols-2 gap-4">
    <div class="md:col-span-2">
      <label class="block text-sm mb-1">Jalan / No / Blok</label>
      <input name="alamat_jalan" value="{{ old('alamat_jalan',$customer->alamat_jalan) }}" class="border p-2 w-full rounded">
    </div>

    <div>
      <label class="block text-sm mb-1">RT</label>
      <input name="rt" value="{{ old('rt',$customer->rt) }}" class="border p-2 w-full rounded">
    </div>
    <div>
      <label class="block text-sm mb-1">RW</label>
      <input name="rw" value="{{ old('rw',$customer->rw) }}" class="border p-2 w-full rounded">
    </div>

    <div>
      <label class="block text-sm mb-1">Kelurahan/Desa</label>
      <input name="kelurahan" value="{{ old('kelurahan',$customer->kelurahan) }}" class="border p-2 w-full rounded">
    </div>
    <div>
      <label class="block text-sm mb-1">Kecamatan</label>
      <input name="kecamatan" value="{{ old('kecamatan',$customer->kecamatan) }}" class="border p-2 w-full rounded">
    </div>

    <div>
      <label class="block text-sm mb-1">Kota/Kabupaten</label>
      <input name="kota_kab" value="{{ old('kota_kab',$customer->kota_kab) }}" class="border p-2 w-full rounded">
    </div>
    <div>
      <label class="block text-sm mb-1">Provinsi</label>
      <input name="provinsi" value="{{ old('provinsi',$customer->provinsi) }}" class="border p-2 w-full rounded">
    </div>

    <div>
      <label class="block text-sm mb-1">Kode Pos</label>
      <input name="kode_pos" value="{{ old('kode_pos',$customer->kode_pos) }}" class="border p-2 w-full rounded">
    </div>
  </div>

  <div class="mt-4 flex gap-2">
    <button class="px-4 py-2 rounded bg-blue-600 text-white">Update</button>
    <a href="{{ route('admin.customer.index') }}" class="px-4 py-2 rounded bg-gray-200">Kembali</a>
  </div>
</form>
@endsection
