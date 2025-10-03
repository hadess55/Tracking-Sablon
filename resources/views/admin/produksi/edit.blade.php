@extends('layouts.admin')
@section('header', 'Edit Produksi')

@section('content')
<form action="{{ route('admin.produksi.update', $produksi) }}" method="POST" class="space-y-4">
  @csrf @method('PUT')

  @if (session('sukses'))
    <div class="rounded-xl border border-emerald-200 bg-emerald-50 text-emerald-700 px-4 py-3 text-sm">{{ session('sukses') }}</div>
  @endif
  @if ($errors->any())
    <div class="rounded-xl border border-rose-200 bg-rose-50 text-rose-700 px-4 py-3 text-sm">
      <b>Terjadi kesalahan:</b>
      <ul class="list-disc pl-5">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
  @endif

  @include('admin.produksi._form', ['produksi'=>$produksi,'customers'=>$customers,'statuses'=>$statuses])

  <div class="flex items-center gap-2">
    <a href="{{ route('admin.produksi.show', $produksi) }}" class="px-3 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700">Kembali</a>
    <button class="px-4 py-2 rounded-xl bg-brand-600 hover:bg-brand-700 text-white">Update</button>
  </div>
</form>
@endsection
