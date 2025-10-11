@extends('layouts.admin')
@section('header','Status Produksi')

@section('content')
<div class="mb-4 flex justify-between items-center">
  <h1 class="text-lg font-semibold">Daftar Status Produksi</h1>
  <a href="{{ route('admin.produksi-status.create') }}" class="rounded-lg bg-blue-600 text-white px-3 py-2">+ Tambah Status</a>
</div>

<div class="rounded-2xl border bg-white shadow-sm overflow-hidden">
  <table class="min-w-full text-sm">
    <thead class="bg-gray-50">
      <tr>
        <th class="px-4 py-2 text-left">Urutan</th>
        <th class="px-4 py-2 text-left">Key</th>
        <th class="px-4 py-2 text-left">Label</th>
        <th class="px-4 py-2 text-left">Final?</th>
        <th class="px-4 py-2 text-right">Aksi</th>
      </tr>
    </thead>
    <tbody class="divide-y">
      @forelse($statuses as $s)
      <tr>
        <td class="px-4 py-2">{{ $s->urutan }}</td>
        <td class="px-4 py-2 font-mono text-xs">{{ $s->key }}</td>
        <td class="px-4 py-2">{{ $s->label }}</td>
        <td class="px-4 py-2">{{ $s->is_final ? 'Ya' : 'Tidak' }}</td>
        <td class="px-4 py-2 text-right space-x-2">
          <a class="rounded border px-3 py-1.5" href="{{ route('admin.produksi-status.edit',$s) }}">Edit</a>
          <form class="inline" method="POST" action="{{ route('admin.produksi-status.destroy',$s) }}">
            @csrf @method('DELETE')
            <button onclick="return confirm('Hapus status ini?')" class="rounded bg-rose-600 text-white px-3 py-1.5">Hapus</button>
          </form>
        </td>
      </tr>
      @empty
      <tr><td colspan="5" class="px-4 py-6 text-center text-gray-500">Belum ada status.</td></tr>
      @endforelse
    </tbody>
  </table>
</div>
@endsection
