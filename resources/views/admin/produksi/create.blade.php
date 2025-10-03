@extends('layouts.admin')
@section('header', 'Tambah Produksi')

@section('content')
<form action="{{ route('admin.produksi.store') }}" method="POST" class="space-y-4">
  @csrf

  @if ($errors->any())
    <div class="rounded-xl border border-rose-200 bg-rose-50 text-rose-700 px-4 py-3 text-sm">
      <b>Terjadi kesalahan:</b>
      <ul class="list-disc pl-5">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
  @endif

  @include('admin.produksi._form', ['produksi'=>$produksi,'customers'=>$customers,'statuses'=>$statuses])

  <div class="flex items-center gap-2">
    <a href="{{ route('admin.customers.index') }}" class="px-3 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700">Batal</a>
    <button class="px-4 py-2 rounded-xl bg-brand-600 hover:bg-brand-700 text-white">Simpan</button>
  </div>
</form>
@endsection
