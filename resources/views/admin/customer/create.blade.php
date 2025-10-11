@extends('layouts.admin')
@section('header','Tambah Customer')

@section('content')
@if ($errors->any())
  <div class="mb-4 p-3 rounded bg-red-100 text-red-800">
    <ul class="list-disc ml-5">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
  </div>
@endif

<form action="{{ route('admin.customer.store') }}" method="POST" class="bg-white p-4 rounded shadow max-w-3xl">
  @csrf

  {{-- Identitas --}}
  <div class="grid md:grid-cols-2 gap-4">
    <div>
      <label class="block font-medium mb-1">Nama</label>
      <input name="name" value="{{ old('name') }}" class="border p-2 w-full rounded" required>
    </div>
    <div>
      <label class="block font-medium mb-1">Email</label>
      <input type="email" name="email" value="{{ old('email') }}" class="border p-2 w-full rounded" required>
    </div>
    <div>
      <label class="block font-medium mb-1">Password</label>
      <input type="password" name="password" class="border p-2 w-full rounded" required>
      <p class="text-xs text-gray-500 mt-1">Min. 6 karakter</p>
    </div>
    <div>
      <label class="block font-medium mb-1">No HP</label>
      <input name="no_hp" value="{{ old('no_hp') }}" class="border p-2 w-full rounded" placeholder="08xxxxxxxxxx">
    </div>
  </div>

  <hr class="my-4">

  {{-- Alamat Lengkap --}}
  <h3 class="font-semibold mb-2">Alamat Lengkap</h3>
  <div class="grid md:grid-cols-2 gap-4">
    <div class="md:col-span-2">
      <label class="block text-sm mb-1">Jalan / No / Blok</label>
      <input name="alamat_jalan" value="{{ old('alamat_jalan') }}" class="border p-2 w-full rounded" placeholder="Jl. Mawar No. 10 Blok B">
    </div>

    <div>
      <label class="block text-sm mb-1">RT</label>
      <input name="rt" value="{{ old('rt') }}" class="border p-2 w-full rounded" placeholder="001">
    </div>
    <div>
      <label class="block text-sm mb-1">RW</label>
      <input name="rw" value="{{ old('rw') }}" class="border p-2 w-full rounded" placeholder="002">
    </div>

    <div>
      <label class="block text-sm mb-1">Kelurahan/Desa</label>
      <input name="kelurahan" value="{{ old('kelurahan') }}" class="border p-2 w-full rounded">
    </div>
    <div>
      <label class="block text-sm mb-1">Kecamatan</label>
      <input name="kecamatan" value="{{ old('kecamatan') }}" class="border p-2 w-full rounded">
    </div>

    <div>
      <label class="block text-sm mb-1">Kota/Kabupaten</label>
      <input name="kota_kab" value="{{ old('kota_kab') }}" class="border p-2 w-full rounded">
    </div>
    <div>
      <label class="block text-sm mb-1">Provinsi</label>
      <input name="provinsi" value="{{ old('provinsi') }}" class="border p-2 w-full rounded">
    </div>

    <div>
      <label class="block text-sm mb-1">Kode Pos</label>
      <input name="kode_pos" value="{{ old('kode_pos') }}" class="border p-2 w-full rounded" placeholder="65123">
    </div>
  </div>

  <div class="mt-4 flex gap-2">
    <button class="px-4 py-2 rounded bg-blue-600 text-white">Simpan</button>
    <a href="{{ route('admin.customer.index') }}" class="px-4 py-2 rounded bg-gray-200">Batal</a>
  </div>
</form>
@endsection
