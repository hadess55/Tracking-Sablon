@extends('layouts.admin')

@section('header', 'Edit Customer')

@section('content')
  <div class="mb-4 flex items-center justify-between">
    <div>
      <h1 class="text-xl md:text-2xl font-semibold text-gray-900">Edit Customer</h1>
      <p class="text-sm text-gray-500">Perbarui data customer berikut.</p>
    </div>
    <a href="{{ route('admin.customer.index') }}"
       class="rounded-lg border border-gray-300 bg-indigo-600 px-3 py-2 text-sm text-white hover:bg-indigo-700">
       ← Kembali
    </a>
  </div>

  <form action="{{ route('admin.customer.update', $customer) }}" method="POST" class="max-w-8xl">
    @csrf @method('PUT')
    @include('admin.customer._form', ['customer' => $customer])
  </form>
@endsection
