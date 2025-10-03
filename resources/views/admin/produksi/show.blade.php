@extends('layouts.admin')
@section('header', 'Detail Produksi')

@section('content')
@if (session('sukses'))
  <div class="rounded-xl border border-emerald-200 bg-emerald-50 text-emerald-700 px-4 py-3 text-sm mb-3">{{ session('sukses') }}</div>
@endif

@php $statusLabels = ['Antri','Desain','Cetak','Finishing','Selesai']; @endphp

{{-- Ringkas --}}
<div class="grid lg:grid-cols-3 gap-4">
  <div class="lg:col-span-1 rounded-2xl bg-white/80 border border-white/60 shadow-soft p-4 space-y-3">
    <div class="text-xs uppercase tracking-wide text-slate-500">Info Produksi</div>
    <div class="text-sm"><span class="text-slate-500">Nomor</span>:
      <span class="font-semibold">{{ $produksi->nomor_produksi }}</span>
    </div>
    <div class="text-sm"><span class="text-slate-500">Pelanggan</span>:
      <span class="font-semibold">{{ $produksi->pelanggan->nama_lengkap ?? '-' }}</span>
    </div>
    <div class="text-sm"><span class="text-slate-500">Produk</span>: {{ $produksi->produk }}</div>
    <div class="text-sm"><span class="text-slate-500">Jumlah</span>: {{ $produksi->jumlah }}</div>
    <div class="text-sm"><span class="text-slate-500">Status</span>:
      <span class="px-2 py-1 rounded-lg bg-slate-100">{{ ucfirst($produksi->status_sekarang) }}</span>
    </div>
    @if($produksi->catatan)
      <div class="text-sm"><span class="text-slate-500">Catatan</span>: {{ $produksi->catatan }}</div>
    @endif

    <div class="pt-2 flex gap-2">
      <a href="{{ route('admin.produksi.edit', $produksi) }}" class="px-3 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700">Edit</a>
    </div>
  </div>

  {{-- Timeline / Riwayat --}}
  <div class="lg:col-span-2 rounded-2xl bg-white/80 border border-white/60 shadow-soft p-4">
    <div class="flex items-center justify-between mb-3">
      <h3 class="text-sm font-semibold">Riwayat Produksi</h3>
      {{-- Form tambah langkah singkat --}}
      <form method="POST" action="{{ route('admin.produksi.riwayat.store', $produksi) }}" class="flex items-center gap-2">
        @csrf


         <div class="w-56">
    <x-combobox
      name="tahapan"
      placeholder="Status baru…"
      :options="$statusLabels"
    />
  </div>
        <input name="catatan" placeholder="Catatan (opsional)" class="h-10 w-64 rounded-xl bg-white/80 border border-slate-200 outline outline-1 outline-slate-200
               focus:outline-2 focus:outline-brand-600/60 focus:border-transparent px-3 transition">
        <button class="px-3 py-2 rounded-xl bg-brand-600 hover:bg-brand-700 text-white">Tambah</button>
      </form>
    </div>

    @if($produksi->riwayat->count())
      <div class="overflow-x-auto">
        <table class="min-w-full text-sm">
          <thead class="bg-white/60 text-slate-600">
          <tr>
            <th class="text-left px-3 py-2">Waktu</th>
            <th class="text-left px-3 py-2">Status</th>
            <th class="text-left px-3 py-2">Catatan</th>
          </tr>
          </thead>
          <tbody class="divide-y divide-slate-100/70">
          @foreach($produksi->riwayat as $r)
            <tr>
              <td class="px-3 py-2 text-slate-500">{{ optional($r->dilakukan_pada)->format('d M Y H:i') }}</td>
              <td class="px-3 py-2"><span class="px-2 py-1 rounded-md bg-slate-100">{{ ucfirst($r->tahapan) }}</span></td>
              <td class="px-3 py-2">{{ $r->catatan ?: '—' }}</td>
            </tr>
          @endforeach
          </tbody>
        </table>
      </div>
    @else
      <div class="text-slate-500">Belum ada riwayat.</div>
    @endif
  </div>
</div>
@endsection
