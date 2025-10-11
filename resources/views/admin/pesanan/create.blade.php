@extends('layouts.admin')
@section('header','Buat Pesanan')

@section('content')
  <div class="mb-4 flex justify-between items-center">
    <div>
      <h1 class="text-xl font-semibold">Buat Pesanan</h1>
      <p class="text-sm text-gray-500">Buat pesanan atas nama customer.</p>
    </div>
    <a href="{{ route('admin.pesanan.index') }}" class="rounded-lg border px-3 py-2 text-sm text-white bg-indigo-600 hover:bg-indigo-700">← Kembali</a>
  </div>

  <form action="{{ route('admin.pesanan.store') }}" method="POST" class="max-w-8xl">
    @csrf
    @include('admin.pesanan._form', ['customers'=>$customers])
  </form>
@endsection
