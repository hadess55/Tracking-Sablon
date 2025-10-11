@extends('layouts.admin')

@section('header', 'Tambah Customer')

@section('content')
  {{-- Header bar dengan tombol Kembali --}}
  <div class="mb-4 flex items-center justify-between">
    <div>
      <h1 class="text-xl md:text-2xl font-semibold text-gray-900">Tambah Customer</h1>
      <p class="text-sm text-gray-500">Lengkapi identitas & alamat customer.</p>
    </div>
    <a href="{{ route('admin.customer.index') }}"
       class="rounded-lg border border-gray-300 bg-indigo-600 px-3 py-2 text-sm text-white hover:bg-indigo-700">
       ← Kembali
    </a>
  </div>

  <form action="{{ route('admin.customer.store') }}" method="POST" class="max-w-8xl">
    @csrf
    @include('admin.customer._form')
  </form>
@endsection
