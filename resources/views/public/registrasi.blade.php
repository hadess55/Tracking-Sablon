@extends('layouts.app')

@section('content')

@if(session('sukses'))
  <div class="mb-4 rounded-xl border border-emerald-200 bg-emerald-50 text-emerald-700 px-4 py-3">
    {{ session('sukses') }}
  </div>
@endif

<x-section-title title="Daftar Pelanggan" desc="Daftar untuk membuat pesanan produksi sablon." />



<x-card class="p-5 sm:p-6 animate-slide-up">
  <form
    method="POST"
    action="{{ route('customers.registrasi.simpan') }}"
    class="grid gap-4 sm:grid-cols-2"
    x-data="{ onlyNum(e){ e.target.value = e.target.value.replace(/[^0-9]/g,'') } }"
  >
    @csrf

    {{-- Nama lengkap --}}
    <x-input
      name="nama_lengkap"
      label="Nama Lengkap"
      required
      placeholder="Nama sesuai KTP"
      :value="old('nama_lengkap')"
      :error="$errors->first('nama_lengkap')"
    />

    {{-- Email --}}
    <x-input
      type="email"
      name="email"
      label="Email"
      required
      placeholder="nama@contoh.com"
      :value="old('email')"
      :error="$errors->first('email')"
    />

    {{-- Nomor HP (angka saja) --}}
    <x-input
      name="nomor_hp"
      label="Nomor HP"
      inputmode="numeric"
      placeholder="08xxxxxxxxxx"
      :value="old('nomor_hp')"
      :error="$errors->first('nomor_hp')"
      x-on:input="onlyNum"
    />

    {{-- Kota --}}
    <x-input
      name="kota"
      label="Kota"
      placeholder="Kota domisili"
      :value="old('kota')"
      :error="$errors->first('kota')"
    />

    {{-- Provinsi --}}
    <x-input
      name="provinsi"
      label="Provinsi"
      placeholder="Provinsi domisili"
      :value="old('provinsi')"
      :error="$errors->first('provinsi')"
    />


    <x-input
      name="kode_pos"
      label="Kode Pos"
      inputmode="numeric"
      maxlength="6"
      placeholder="Kode pos (opsional)"
      :value="old('kode_pos')"
      :error="$errors->first('kode_pos')"
      x-on:input="onlyNum"
    />


    <div class="sm:col-span-2">
      <x-textarea
        name="alamat"
        label="Alamat"
        rows="4"
        placeholder="Nama jalan, RT/RW, Kel/Desa, Kec."
        :value="old('alamat')"
        :error="$errors->first('alamat')"
      />
    </div>

    {{-- Actions --}}
    <div class="sm:col-span-2 flex items-center justify-end gap-3">
      <a href="{{ url('/') }}" class="text-sm text-slate-600 hover:text-slate-800">Kembali</a>
      <x-button type="submit" class="animate-pulse-once">
        Daftar
      </x-button>
    </div>
  </form>
</x-card>
@endsection
