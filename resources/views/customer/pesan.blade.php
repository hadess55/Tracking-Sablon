@extends('layouts.app')

@section('header', 'Buat Pesanan')

@section('content')
<form method="POST" action="{{ route('pesanan.simpan') }}" class="max-w-3xl bg-white p-5 rounded-xl shadow-sm border border-gray-200">
  @csrf

  <div class="grid md:grid-cols-2 gap-4">
    <div class="md:col-span-2">
      <label class="block text-sm font-medium text-gray-700 mb-1">Produk <span class="text-red-600">*</span></label>
      <input name="produk" value="{{ old('produk') }}" class="w-full rounded-lg border-gray-300 px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
      @error('produk')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
    </div>

    <div>
      <label class="block text-sm font-medium text-gray-700 mb-1">Bahan</label>
      <input name="bahan" value="{{ old('bahan') }}" placeholder="Cotton combed 30s / PE / Fleece ..."
             class="w-full rounded-lg border-gray-300 px-3 py-2">
      @error('bahan')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
    </div>

    <div>
      <label class="block text-sm font-medium text-gray-700 mb-1">Warna</label>
      <input name="warna" value="{{ old('warna') }}" placeholder="Hitam / Putih / Merah ..."
             class="w-full rounded-lg border-gray-300 px-3 py-2">
      @error('warna')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
    </div>

    <div class="md:col-span-2">
      <label class="block text-sm font-medium text-gray-700 mb-1">Tautan Drive (brief / desain)</label>
      <input type="url" name="tautan_drive" value="{{ old('tautan_drive') }}" placeholder="https://drive.google.com/..."
             class="w-full rounded-lg border-gray-300 px-3 py-2">
      @error('tautan_drive')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
    </div>

    <div class="md:col-span-2">
      <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
      <textarea name="deskripsi" rows="4" class="w-full rounded-lg border-gray-300 px-3 py-2">{{ old('deskripsi') }}</textarea>
      @error('deskripsi')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
    </div>
  </div>

  <hr class="my-5">

  <h3 class="font-semibold text-gray-900 mb-2">Ukuran Kaos (isi jumlah per ukuran)</h3>
  <div class="grid grid-cols-5 gap-3 max-w-xl">
    @foreach (['S','M','L','XL','XXL'] as $sz)
      <div>
        <label class="block text-sm text-gray-600 mb-1">{{ $sz }}</label>
        <input type="number" min="0" name="ukuran_kaos[{{ $sz }}]"
               value="{{ old('ukuran_kaos.'.$sz, 0) }}"
               class="w-full rounded-lg border-gray-300 px-3 py-2 text-center">
      </div>
    @endforeach
  </div>
  <p class="text-xs text-gray-500 mt-2">Biarkan 0 jika tidak ada. Total akan dihitung otomatis saat disimpan.</p>

  {{-- fallback jumlah manual jika semua 0 (opsional tampilkan) --}}
  <div class="mt-4">
    <label class="block text-xs text-gray-500">Atau isi jumlah manual (opsional, dipakai jika semua ukuran 0)</label>
    <input type="number" min="1" name="jumlah" value="{{ old('jumlah') }}" class="w-40 rounded-lg border-gray-300 px-3 py-2">
  </div>

  <div class="mt-6 flex items-center gap-2">
    <button class="rounded-lg bg-blue-600 px-4 py-2 text-white hover:bg-blue-700">Kirim</button>
    <a href="{{ route('pesanan.index') }}" class="rounded-lg border border-gray-300 bg-white px-4 py-2 text-gray-700 hover:bg-gray-50">Batal</a>
  </div>
</form>
@endsection
