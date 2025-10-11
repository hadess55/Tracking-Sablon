@extends('layouts.admin')
@section('header', 'Dashboard')

@section('content')
{{-- KPI Cards --}}
<div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">

  {{-- Pesanan Menunggu --}}
  <div class="rounded-2xl border border-white/60 bg-white/80 backdrop-blur p-4 shadow-sm">
    <div class="flex items-start justify-between">
      <div>
        <div class="text-sm text-slate-500">Pesanan Menunggu</div>
        <div class="mt-1 text-2xl font-semibold">{{ $menunggu ?? 0 }}</div>
      </div>
      <div class="rounded-xl bg-indigo-50 p-2 text-indigo-600">
        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-width="1.8" d="M8 6h13M8 12h13M8 18h13M3 6h.01M3 12h.01M3 18h.01"/>
        </svg>
      </div>
    </div>
    <a href="{{ route('admin.pesanan.index', ['status' => 'menunggu']) }}"
       class="mt-3 inline-block text-xs text-indigo-600 hover:text-indigo-700">Lihat</a>
  </div>

  {{-- Pesanan Disetujui --}}
  <div class="rounded-2xl border border-white/60 bg-white/80 backdrop-blur p-4 shadow-sm">
    <div class="flex items-start justify-between">
      <div>
        <div class="text-sm text-slate-500">Pesanan Disetujui</div>
        <div class="mt-1 text-2xl font-semibold">{{ $disetujui ?? 0 }}</div>
      </div>
      <div class="rounded-xl bg-emerald-50 p-2 text-emerald-600">
        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-width="1.8" d="M5 13l4 4L19 7"/>
        </svg>
      </div>
    </div>
    <a href="{{ route('admin.pesanan.index', ['status' => 'disetujui']) }}"
       class="mt-3 inline-block text-xs text-indigo-600 hover:text-indigo-700">Lihat</a>
  </div>

  {{-- Produksi Sedang Proses --}}
  <div class="rounded-2xl border border-white/60 bg-white/80 backdrop-blur p-4 shadow-sm">
    <div class="flex items-start justify-between">
      <div>
        <div class="text-sm text-slate-500">Produksi Sedang Proses</div>
        <div class="mt-1 text-2xl font-semibold">{{ $sedangProses ?? 0 }}</div>
      </div>
      <div class="rounded-xl bg-amber-50 p-2 text-amber-600">
        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-width="1.8" d="M12 6v6l4 2"/>
        </svg>
      </div>
    </div>
    <a href="{{ route('admin.produksi.index', ['filter'=>'proses']) }}"
       class="mt-3 inline-block text-xs text-indigo-600 hover:text-indigo-700">Lihat</a>
  </div>

  {{-- Produksi Selesai --}}
  <div class="rounded-2xl border border-white/60 bg-white/80 backdrop-blur p-4 shadow-sm">
    <div class="flex items-start justify-between">
      <div>
        <div class="text-sm text-slate-500">Produksi Selesai</div>
        <div class="mt-1 text-2xl font-semibold">{{ $selesai ?? 0 }}</div>
      </div>
      <div class="rounded-xl bg-sky-50 p-2 text-sky-600">
        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-width="1.8" d="M5 13l4 4L19 7"/>
        </svg>
      </div>
    </div>
    <a href="{{ route('admin.produksi.index', ['filter'=>'selesai']) }}"
       class="mt-3 inline-block text-xs text-indigo-600 hover:text-indigo-700">Lihat</a>
  </div>
</div>

{{-- 2 kolom: kiri daftar, kanan aktivitas + aksi cepat --}}
<div class="mt-5 grid gap-4 lg:grid-cols-3">
  {{-- Kolom kiri --}}
  <div class="lg:col-span-2 space-y-4">

    {{-- Pesanan Terbaru --}}
    <div class="rounded-2xl border border-white/60 bg-white/80 backdrop-blur shadow-sm">
      <div class="flex items-center justify-between px-4 py-3 border-b">
        <div class="text-sm font-semibold">Pesanan Terbaru</div>
        <a href="{{ route('admin.pesanan.index') }}" class="text-xs text-indigo-600 hover:text-indigo-700">Semua Pesanan</a>
      </div>

      @if($recentPesanan->isEmpty())
        <div class="p-6 text-sm text-slate-500">Belum ada pesanan.</div>
      @else
        <div class="overflow-x-auto">
          <table class="min-w-full text-sm">
            <thead class="bg-white/60 text-slate-600">
              <tr>
                <th class="px-4 py-2 text-left font-medium">Resi</th>
                <th class="px-4 py-2 text-left font-medium">Customer</th>
                <th class="px-4 py-2 text-left font-medium">Status</th>
                <th class="px-4 py-2 text-right font-medium">Tanggal</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-slate-100/80">
              @foreach($recentPesanan as $p)
                <tr class="hover:bg-white/50">
                  <td class="px-4 py-2 font-mono text-xs">{{ $p->nomor_resi ?? '-' }}</td>
                  <td class="px-4 py-2">
                    <div class="font-medium">{{ $p->pengguna->name ?? '-' }}</div>
                    <div class="text-xs text-slate-500">{{ $p->pengguna->email ?? '' }}</div>
                  </td>
                  <td class="px-4 py-2">
                    <span class="rounded-full bg-slate-100 px-2 py-0.5 text-xs text-slate-700">
                      {{ strtoupper($p->status) }}
                    </span>
                  </td>
                  <td class="px-4 py-2 text-right text-xs text-slate-500">
                    {{ $p->created_at?->format('d M Y H:i') }}
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      @endif
    </div>

    {{-- Ringkas Total --}}
    <div class="grid gap-4 sm:grid-cols-3">
      <div class="rounded-2xl border border-white/60 bg-white/80 backdrop-blur p-4 shadow-sm">
        <div class="text-sm text-slate-500">Total Pesanan</div>
        <div class="mt-1 text-xl font-semibold">{{ $totalPesanan ?? 0 }}</div>
      </div>
      <div class="rounded-2xl border border-white/60 bg-white/80 backdrop-blur p-4 shadow-sm">
        <div class="text-sm text-slate-500">Total Produksi</div>
        <div class="mt-1 text-xl font-semibold">{{ $totalProduksi ?? 0 }}</div>
      </div>
      <div class="rounded-2xl border border-white/60 bg-white/80 backdrop-blur p-4 shadow-sm">
        <div class="text-sm text-slate-500">Total Customer</div>
        <div class="mt-1 text-xl font-semibold">{{ $totalCustomer ?? 0 }}</div>
      </div>
    </div>

  </div>

  {{-- Kolom kanan --}}
  <div class="space-y-4">

    {{-- Aktivitas Produksi Terakhir --}}
    {{-- <div class="rounded-2xl border border-white/60 bg-white/80 backdrop-blur shadow-sm">
      <div class="flex items-center justify-between px-4 py-3 border-b">
        <div class="text-sm font-semibold">Aktivitas Produksi Terakhir</div>
        <a href="{{ route('admin.produksi.index') }}" class="text-xs text-indigo-600 hover:text-indigo-700">Ke Produksi</a>
      </div>

      @if($recentLogs->isEmpty())
        <div class="p-6 text-sm text-slate-500">Belum ada aktivitas.</div>
      @else
        <ol class="relative ml-4 mt-3 border-l-2 border-slate-200">
          @foreach($recentLogs as $log)
            <li class="mb-4 ml-4">
              <div class="absolute -left-1.5 mt-1.5 h-3 w-3 rounded-full bg-indigo-500"></div>
              <div class="text-xs text-slate-500">{{ $log->created_at->format('d M Y H:i') }}</div>
              <div class="font-medium">#{{ $log->produksi->nomor_resi ?? '-' }} — {{ strtoupper($log->status_key) }}</div>
              @if($log->catatan)
                <div class="text-sm text-slate-700">{{ $log->catatan }}</div>
              @endif
              @if($log->author)
                <div class="mt-0.5 text-xs text-slate-400">oleh {{ $log->author->name }}</div>
              @endif
            </li>
          @endforeach
        </ol>
      @endif
    </div> --}}

    {{-- Aksi Cepat --}}
    <div class="rounded-2xl border border-white/60 bg-white/80 backdrop-blur p-4 shadow-sm">
      <div class="text-sm font-semibold">Aksi Cepat</div>
      <div class="mt-3 grid gap-2">
        <a href="{{ route('admin.pesanan.create') }}"
           class="rounded-xl bg-indigo-600 px-4 py-2 text-center text-white hover:bg-indigo-700">
          + Buat Pesanan
        </a>
        <a href="{{ route('admin.produksi.index', ['filter'=>'proses']) }}"
           class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-center text-slate-700 hover:bg-white/60">
          Lihat Produksi Berjalan
        </a>
        <a href="{{ route('admin.customer.index') }}"
           class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-center text-slate-700 hover:bg-white/60">
          Kelola Customer
        </a>
      </div>
    </div>

  </div>
</div>
@endsection
