@extends('layouts.admin')
@section('header','Pesanan')

@section('content')

@php
  // style tombol tab
  $tabBase  = 'inline-flex items-center rounded-full border px-3 py-1.5 text-sm whitespace-nowrap';
  $tabIdle  = 'bg-white text-slate-600 border-slate-200 hover:bg-slate-50';
  $tabAct   = 'bg-indigo-600 text-white border-indigo-600';
  $now = $status ?? 'semua'; // 'menunggu' | 'disetujui' | 'ditolak' | 'semua'
@endphp

<div class="flex flex-col gap-3 sm:gap-4">

  {{-- Baris 1: Tabs (scrollable di mobile) --}}
  <div class="-mx-2 px-2 overflow-x-auto">
    <div class="inline-flex gap-2">
        <a href="{{ route('admin.pesanan.index') }}"
         class="{{ $tabBase }} {{ $now==='semua' ? $tabAct : $tabIdle }}">
         Semua
      </a>
      <a href="{{ route('admin.pesanan.index',['status'=>'menunggu']) }}"
         class="{{ $tabBase }} {{ $now==='menunggu' ? $tabAct : $tabIdle }}">
         Menunggu
      </a>
      <a href="{{ route('admin.pesanan.index',['status'=>'disetujui']) }}"
         class="{{ $tabBase }} {{ $now==='disetujui' ? $tabAct : $tabIdle }}">
         Disetujui
      </a>
      <a href="{{ route('admin.pesanan.index',['status'=>'ditolak']) }}"
         class="{{ $tabBase }} {{ $now==='ditolak' ? $tabAct : $tabIdle }}">
         Ditolak
      </a>
      
    </div>
  </div>

  {{-- Baris 2: Search + Buat Pesanan (stack di mobile) --}}
  <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between mb-5">
    <form action="{{ route('admin.pesanan.index') }}" method="GET" class="flex w-full gap-2 sm:w-auto">
      {{-- kirim status aktif saat search --}}
      @if($now && $now!=='semua') <input type="hidden" name="status" value="{{ $now }}"> @endif

      <div class="flex w-full sm:w-80 items-center gap-2">
        <input name="q" value="{{ request('q') }}"
               placeholder="Cari..."
               class="flex-1 rounded-xl border border-slate-200 bg-white/80 px-3 py-2
                      focus:border-indigo-500 focus:outline focus:outline-2 focus:outline-indigo-500">
        <button class="rounded-xl border border-slate-200 bg-white/80 px-3 py-2 text-slate-700 hover:bg-white">
          Cari
        </button>
      </div>
    </form>

    <a href="{{ route('admin.pesanan.create') }}"
       class="rounded-xl bg-indigo-600 px-4 py-2 text-center text-white hover:bg-indigo-700
              w-full sm:w-auto">
      + Buat Pesanan
    </a>
  </div>
</div>




<div class="overflow-x-auto bg-white border rounded-xl shadow-sm">
<table class="min-w-full text-sm">
    <thead class="bg-gray-50 text-left">
        <tr>
            <th class="px-3 py-2">ID</th>
            <th class="px-3 py-2">Customer</th>
            <th class="px-3 py-2">Produk</th>
            <th class="px-3 py-2">Bahan</th>
            <th class="px-3 py-2">Ukuran</th>
            <th class="px-3 py-2">Jumlah</th>
            <th class="px-3 py-2">Warna</th>
            <th class="px-3 py-2">Nomor Resi</th>
            <th class="px-3 py-2">Aksi</th>
        </tr>
    </thead>
    <tbody>
        @forelse($pesanan as $o)
        <tr class="border-t">
            <td class="px-3 py-2">{{ $o->id }}</td>
            <td class="px-3 py-2">
                <div class="font-medium">{{ $o->pengguna->name ?? '-' }}</div>
                <div class="text-xs text-gray-500">{{ $o->pengguna->email ?? '' }}</div>
            </td>
            <td class="px-3 py-2">
                <div class="font-medium">{{ $o->produk }}</div>

                @if($o->tautan_drive)
                    <div class="text-xs">
                        <a class="text-indigo-600 underline" href="{{ $o->tautan_drive }}" target="_blank" rel="noopener">Link drive</a>
                    </div>
                @endif

                @if($o->deskripsi)
                    <div class="text-xs text-gray-500" title="{{ $o->deskripsi }}">
                        {{ Str::limit($o->deskripsi, 20) }}
                        {{-- {{ Str::words($o->deskripsi, 3, '…') }} --}}
                    </div>
                @endif
            </td>

            <td class="px-3 py-2">{{ $o->bahan ?: '—' }}</td>
            <td class="px-3 py-2">{{ $o->ukuran_ringkas }}</td>
            <td class="px-3 py-2">{{ $o->jumlah }}</td>
            <td class="px-3 py-2">{{ $o->warna ?: '—' }}</td>
            <td class="px-3 py-2">
                @if($o->nomor_resi)<code class="text-xs">{{ $o->nomor_resi }}</code>@else<span class="text-gray-400">—</span>@endif
            </td>
            <td class="px-3 py-2">
                <div class="flex items-center gap-2">
                    <a href="{{ route('admin.pesanan.show',$o) }}" class="underline">Lihat</a>
                    @if($o->status==='menunggu')
                        <a href="{{ route('admin.pesanan.edit',$o) }}" class="underline">Edit</a>
                        <form method="POST" class="inline" action="{{ route('admin.pesanan.setujui',$o) }}">
                            @csrf <button class="px-2 py-1 bg-green-600 text-white rounded">Setujui</button>
                        </form>
                        <details class="inline-block">
                            <summary class="px-2 py-1 bg-red-600 text-white rounded cursor-pointer">Tolak</summary>
                            <form method="POST" action="{{ route('admin.pesanan.tolak',$o) }}" class="mt-2">
                                @csrf
                                <textarea name="alasan" rows="2" class="border p-1 w-56" placeholder="Alasan penolakan" required></textarea>
                                <button class="mt-1 px-2 py-1 bg-red-600 text-white rounded">Kirim</button>
                            </form>
                        </details>
                    @endif
                </div>
            </td>
        </tr>
        @empty
        <tr><td colspan="9" class="px-3 py-10 text-center text-gray-500">Belum ada data.</td></tr>
        @endforelse
    </tbody>
</table>
</div>

@if($pesanan->hasPages())
<div class="mt-3">{{ $pesanan->links() }}</div>
@endif
@endsection
