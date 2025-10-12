@extends('layouts.app')
@section('title','Detail Pesanan')

@section('content')
<div class="fixed inset-0 -z-10">
    <div class="absolute inset-x-0 top-0 h-64 bg-gradient-to-b from-indigo-50 to-transparent"></div>
    <div class="absolute inset-0 bg-[radial-gradient(rgba(99,102,241,.12)_1px,transparent_1px)] [background-size:16px_16px]"></div>
  </div>
@php
  $status = strtolower($pesanan->status);
  $badgeClass = match($status){
    'menunggu' => 'bg-amber-50 text-amber-700 ring-amber-200',
    'disetujui'=> 'bg-emerald-50 text-emerald-700 ring-emerald-200',
    'ditolak'  => 'bg-rose-50 text-rose-700 ring-rose-200',
    default    => 'bg-slate-50 text-slate-700 ring-slate-200',
  };

  // ukuran = array: s,m,l,xl,xxl
  $uk = collect($pesanan->ukuran ?? [])->map(fn($v)=>(int)$v)->filter();
  $total = (int) $pesanan->jumlah;
@endphp

<div class="max-w-5xl mx-auto">
  {{-- Back --}}
  <div class="mb-3">
    <a href="{{ route('pesanan.index') }}"
       class="inline-flex items-center gap-2 text-sm text-slate-600 hover:text-slate-800">
      <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
      Kembali
    </a>
  </div>

  {{-- Header --}}
  <div class="rounded-2xl border border-white/60 bg-gradient-to-tr from-indigo-500/15 via-indigo-300/10 to-transparent p-6">
    <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
      <div>
        <h1 class="text-lg font-semibold">Detail Pesanan</h1>
        <div class="mt-1 text-sm text-slate-600">
          Dibuat: {{ $pesanan->created_at->format('d M Y H:i') }}
        </div>
        <div class="mt-2 flex flex-wrap items-center gap-2">
          <div class="text-[11px] text-slate-500">Nomor Resi</div>
          <div class="flex items-center gap-2">
            <span class="font-mono text-xs">{{ $pesanan->nomor_resi ?: '—' }}</span>
            @if($pesanan->nomor_resi)
              <button x-data
                      @click="navigator.clipboard.writeText('{{ $pesanan->nomor_resi }}'); $el.innerText='Tersalin'; setTimeout(()=>{$el.innerText='Copy'},1300)"
                      class="rounded-md border border-slate-200 bg-white px-2 py-0.5 text-[11px] text-slate-600 hover:bg-slate-50">
                Copy
              </button>
            @endif
          </div>
        </div>
      </div>

      <div class="flex items-center gap-2">
        <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs ring-1 {{ $badgeClass }}">
          {{ strtoupper($pesanan->status) }}
        </span>

        @if($pesanan->nomor_resi)
          <a href="{{ route('tracking.show', $pesanan->nomor_resi) }}"
             class="inline-flex items-center gap-2 rounded-xl bg-indigo-600 px-3 py-2 text-white hover:bg-indigo-700">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Lacak
          </a>
        @endif
      </div>
    </div>
  </div>

  {{-- Content --}}
  <div class="mt-4 grid gap-4 md:grid-cols-3">
    {{-- Ringkasan --}}
    <div class="md:col-span-2 rounded-2xl border border-white/60 bg-white/80 backdrop-blur p-5 shadow-sm">
      <div class="grid gap-4 sm:grid-cols-2">
        <div>
          <div class="text-xs text-slate-500">Produk</div>
          <div class="text-base font-medium">{{ $pesanan->produk }}</div>
        </div>
        <div>
          <div class="text-xs text-slate-500">Bahan</div>
          <div class="text-base font-medium">{{ $pesanan->bahan }}</div>
        </div>
        <div>
          <div class="text-xs text-slate-500">Warna</div>
          <div class="text-base font-medium">{{ $pesanan->warna }}</div>
        </div>
        <div>
          <div class="text-xs text-slate-500">Jumlah Total</div>
          <div class="text-base font-medium">{{ $total }}</div>
        </div>
      </div>

      {{-- Ukuran per size --}}
      @if($uk->isNotEmpty())
      <div class="mt-4">
        <div class="text-xs text-slate-500 mb-1">Rincian Ukuran</div>
        <div class="flex flex-wrap gap-2">
          @foreach($uk as $key => $val)
            @php
              $label = strtoupper($key);
            @endphp
            <span class="inline-flex items-center rounded-full bg-slate-50 px-3 py-1 text-xs ring-1 ring-slate-200">
              {{ $label }}: <span class="ml-1 font-semibold">{{ $val }}</span>
            </span>
          @endforeach
        </div>
      </div>
      @endif

      {{-- Link drive --}}
      @if($pesanan->drive_link)
      <div class="mt-4">
        <div class="text-xs text-slate-500 mb-1">Link Drive</div>
        <a href="{{ $pesanan->drive_link }}" target="_blank" rel="noopener"
           class="inline-flex items-center gap-2 rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-sm text-slate-700 hover:bg-slate-50">
          <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
          Buka tautan
        </a>
      </div>
      @endif

      {{-- Deskripsi --}}
      <div class="mt-4">
        <div class="text-xs text-slate-500 mb-1">Deskripsi</div>
        <div class="rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700">
          {{ $pesanan->deskripsi ?: '—' }}
        </div>
      </div>
    </div>

    {{-- Status / Catatan --}}
    <div class="rounded-2xl border border-white/60 bg-white/80 backdrop-blur p-5 shadow-sm">
      <div class="text-sm font-medium mb-2">Informasi Status</div>

      @if($status === 'menunggu')
        <p class="text-sm text-slate-600">
          Pesanan Anda sedang menunggu persetujuan admin. Anda akan mendapatkan nomor resi setelah disetujui.
        </p>
      @elseif($status === 'disetujui')
        <p class="text-sm text-slate-600">
          Pesanan telah disetujui. Silakan gunakan tombol <b>Lacak</b> untuk memantau proses produksi menggunakan nomor resi.
        </p>
      @elseif($status === 'ditolak')
        <div class="space-y-2">
          <p class="text-sm text-slate-600">
            Maaf, pesanan Anda ditolak. Silakan buat pesanan baru bila diperlukan.
          </p>
          @if(!empty($pesanan->alasan_ditolak))
            <div>
              <div class="text-xs text-slate-500 mb-1">Alasan Penolakan</div>
              <div class="rounded-xl border border-rose-200 bg-rose-50 px-3 py-2 text-sm text-rose-700">
                {{ $pesanan->alasan_ditolak }}
              </div>
            </div>
          @endif
        </div>
      @else
        <p class="text-sm text-slate-600">Status pesanan: {{ strtoupper($pesanan->status) }}.</p>
      @endif

      {{-- CTA kecil --}}
      <div class="mt-4 flex flex-wrap gap-2">
        <a href="{{ route('pesanan.index') }}"
           class="rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700 hover:bg-slate-50">
          Kembali ke daftar
        </a>
        @if($pesanan->nomor_resi)
          <a href="{{ route('tracking.show', $pesanan->nomor_resi) }}"
             class="rounded-xl bg-indigo-600 px-3 py-2 text-sm text-white hover:bg-indigo-700">
            Lacak Produksi
          </a>
        @endif
      </div>
    </div>
  </div>
</div>
@endsection
