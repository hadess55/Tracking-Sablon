@extends('layouts.admin')
@section('header','Edit Pesanan')

@section('content')
  <div class="mb-4 flex justify-between items-center">
    <div>
      <h1 class="text-xl font-semibold">Edit Pesanan #{{ $pesanan->id }}</h1>
      <p class="text-sm text-gray-500">Perbarui detail pesanan.</p>
    </div>
    <a href="{{ route('admin.pesanan.show',$pesanan) }}" class="rounded-lg border px-3 py-2 text-sm text-white bg-indigo-600 hover:bg-indigo-700">← Detail</a>
  </div>

  <form action="{{ route('admin.pesanan.update',$pesanan) }}" method="POST" class="max-w-8xl">
    @csrf @method('PUT')
    @include('admin.pesanan._form', ['customers'=>$customers, 'pesanan'=>$pesanan])
  </form>
@endsection
