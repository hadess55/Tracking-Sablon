@extends('layouts.app')
@section('title','Pesanan Saya')

@section('content')
<div class="fixed inset-0 -z-10">
    <div class="absolute inset-x-0 top-0 h-64 bg-gradient-to-b from-indigo-50 to-transparent"></div>
    <div class="absolute inset-0 bg-[radial-gradient(rgba(99,102,241,.12)_1px,transparent_1px)] [background-size:16px_16px]"></div>
  </div>
@php
  // Ambil status aktif dari query (?status=menunggu/disetujui/semua)
  $aktif = strtolower(request('status', 'semua'));

  // Warna badge status
  $badge = function($s){
    return match(strtolower($s)){
      'menunggu' => 'bg-amber-50 text-amber-700 ring-amber-200',
      'disetujui'=> 'bg-emerald-50 text-emerald-700 ring-emerald-200',
      'ditolak'  => 'bg-rose-50 text-rose-700 ring-rose-200',
      default    => 'bg-slate-50 text-slate-700 ring-slate-200',
    };
  };

  // Warna aksen kartu (garis kiri) per status
  $accent = function($s){
    return match(strtolower($s)){
      'menunggu' => 'before:bg-amber-400',
      'disetujui'=> 'before:bg-emerald-500',
      'ditolak'  => 'before:bg-rose-500',
      default    => 'before:bg-slate-400',
    };
  };

  $tab = fn($key) => $aktif===$key
      ? 'text-white bg-indigo-600 shadow-sm'
      : 'text-slate-600 hover:text-slate-800 bg-white/70 hover:bg-white';

$aktif = $aktif ?? request('status', 'semua');
  $tabCls = fn($k) => $aktif === $k
      ? 'text-white bg-indigo-600 shadow-sm'
      : 'text-slate-600 hover:text-slate-800 bg-white/70 hover:bg-white';
  // helper gabung query string selain page
  $qs = request()->except('page','status');
@endphp

{{-- Header Hero --}}
<div class=" max-w-6xl mx-auto relative overflow-hidden rounded-3xl border border-white/60 bg-gradient-to-tr from-indigo-500/20 via-indigo-300/10 to-transparent p-6">
  <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
    <div>
      <h1 class="text-xl font-semibold text-slate-900">Pesanan Saya</h1>
      <p class="text-sm text-slate-600">Kelola semua pesanan yang Anda buat.</p>
    </div>
    <a href="{{ route('pesanan.buat') }}"
       class="inline-flex items-center gap-2 rounded-xl bg-indigo-600 px-4 py-2 text-white shadow-sm hover:bg-indigo-700">
      <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-width="2" d="M12 5v14m7-7H5"/></svg>
      Pesan
    </a>
  </div>

  {{-- Tab Filter --}}
  <div class="mt-4 flex flex-wrap items-center gap-2">
  <a href="{{ route('pesanan.index', array_merge($qs, ['status'=>'semua'])) }}"
     class="inline-flex items-center gap-2 rounded-full px-3 py-1.5 text-sm ring-1 ring-white/60 transition {{ $tabCls('semua') }}">
    Semua
  </a>

  <a href="{{ route('pesanan.index', array_merge($qs, ['status'=>'menunggu'])) }}"
     class="inline-flex items-center gap-2 rounded-full px-3 py-1.5 text-sm ring-1 ring-white/60 transition {{ $tabCls('menunggu') }}">
    <span class="h-2 w-2 rounded-full bg-amber-500"></span> Menunggu
  </a>

  <a href="{{ route('pesanan.index', array_merge($qs, ['status'=>'disetujui'])) }}"
     class="inline-flex items-center gap-2 rounded-full px-3 py-1.5 text-sm ring-1 ring-white/60 transition {{ $tabCls('disetujui') }}">
    <span class="h-2 w-2 rounded-full bg-emerald-500"></span> Disetujui
  </a>

  <a href="{{ route('pesanan.index', array_merge($qs, ['status'=>'ditolak'])) }}"
     class="inline-flex items-center gap-2 rounded-full px-3 py-1.5 text-sm ring-1 ring-white/60 transition {{ $tabCls('ditolak') }}">
    <span class="h-2 w-2 rounded-full bg-rose-500"></span> Ditolak
  </a>
</div>
{{-- Flash --}}
@if(session('berhasil'))
  <div class="mt-4 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-emerald-700">
    {{ session('berhasil') }}
  </div>
@endif

{{-- Desktop: Tabel --}}
<div class="max-w-6xl mx-auto mt-4 hidden md:block rounded-2xl border border-white/60 bg-white/80 backdrop-blur shadow-sm overflow-hidden">
  @if($items->isEmpty())
    <div class="p-6 text-sm text-slate-500">
      Belum ada pesanan pada filter ini. Klik <b>Pesan</b> untuk membuat pesanan pertama.
    </div>
  @else
    <div class="overflow-x-auto max-w-6xl mx-auto">
      <table class=" min-w-full text-sm">
        <thead class="bg-white/60 text-slate-600">
          <tr>
            <th class="px-4 py-3 text-left font-medium">Nomor Resi</th>
            <th class="px-4 py-3 text-left font-medium">Produk</th>
            <th class="px-4 py-3 text-left font-medium">Jumlah</th>
            <th class="px-4 py-3 text-left font-medium">Status</th>
            <th class="px-4 py-3 text-right font-medium">Aksi</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-100/80">
          @foreach($items as $p)
          <tr class="hover:bg-white/50 transition">
            <td class="px-4 py-3">
              <div class="flex items-center gap-2">
                <span class="font-mono text-xs">{{ $p->nomor_resi ?: '—' }}</span>
                @if($p->nomor_resi)
                  <button x-data
                          @click="navigator.clipboard.writeText('{{ $p->nomor_resi }}'); $el.innerText='Tersalin'; setTimeout(()=>{$el.innerText='Copy'}, 1300)"
                          class="rounded-md border border-slate-200 bg-white px-2 py-0.5 text-[11px] text-slate-600 hover:bg-slate-50">
                    Copy
                  </button>
                @endif
              </div>
              <div class="text-[11px] text-slate-400">{{ $p->created_at->format('d M Y H:i') }}</div>
            </td>
            <td class="px-4 py-3">{{ $p->produk }}</td>
            <td class="px-4 py-3">{{ $p->jumlah }}</td>
            <td class="px-4 py-3">
              <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs ring-1 {{ $badge($p->status) }}">
                {{ strtoupper($p->status) }}
              </span>
            </td>
            <td class="px-4 py-3 text-right">
              <a href="{{ route('pesanan.tampil', ['pesanan' => $p->id]) }}"
                class="inline-flex items-center gap-1 rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-slate-700 hover:bg-slate-50">
                Detail
                </a>

            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
    <div class="border-t border-white/60 px-4 py-3">
      {{ $items->withQueryString()->links() }}
    </div>
  @endif
</div>

{{-- Mobile: Kartu --}}
<div class="max-w-6xl mx-auto mt-4 space-y-3 lg:hidden">
  @forelse($items as $p)
    <div class="relative rounded-2xl border border-white/60 bg-white/85 backdrop-blur p-4 shadow-sm
                before:absolute before:inset-y-0 before:left-0 before:w-1 before:rounded-l-2xl {{ $accent($p->status) }}">
      <div class="flex items-start justify-between gap-2">
        <div>
          <div class="text-[11px] text-slate-500">Nomor Resi</div>
          <div class="flex items-center gap-2">
            <span class="font-mono text-xs">{{ $p->nomor_resi ?: '—' }}</span>
            @if($p->nomor_resi)
            <button x-data
                    @click="navigator.clipboard.writeText('{{ $p->nomor_resi }}'); $el.innerText='Tersalin'; setTimeout(()=>{$el.innerText='Copy'}, 1300)"
                    class="rounded-md border border-slate-200 bg-white px-2 py-0.5 text-[11px] text-slate-600 hover:bg-slate-50">
              Copy
            </button>
            @endif
          </div>
        </div>
        <span class="inline-flex items-center rounded-full px-2 py-0.5 text-[11px] ring-1 {{ $badge($p->status) }}">
          {{ strtoupper($p->status) }}
        </span>
      </div>

      <div class="mt-2 grid grid-cols-2 gap-3 text-sm">
        <div>
          <div class="text-[11px] text-slate-500">Produk</div>
          <div class="font-medium">{{ $p->produk }}</div>
        </div>
        <div>
          <div class="text-[11px] text-slate-500">Jumlah</div>
          <div class="font-medium">{{ $p->jumlah }}</div>
        </div>
      </div>

      <div class="mt-3 flex items-center justify-between">
        <div class="text-[11px] text-slate-400">{{ $p->created_at->format('d M Y H:i') }}</div>
        <a href="{{ route('pesanan.tampil', ['pesanan' => $p->id]) }}"
   class="rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-sm text-slate-700 hover:bg-slate-50">
  Detail
</a>

      </div>
    </div>
  @empty
    <div class="rounded-2xl border border-white/60 bg-white/80 p-6 text-slate-600 shadow-sm">
      Belum ada pesanan. Ketuk tombol <b>Pesan</b> di atas.
    </div>
  @endforelse

  @if($items->hasPages())
    <div class="mt-2">{{ $items->withQueryString()->links() }}</div>
  @endif
</div>
@endsection
