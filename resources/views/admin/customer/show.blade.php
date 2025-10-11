@extends('layouts.admin')
@section('header', 'Detail Pelanggan')

@section('content')
@if (session('sukses'))
  <div class="rounded-xl border border-emerald-200 bg-emerald-50 text-emerald-700 px-4 py-3 text-sm mb-3">{{ session('sukses') }}</div>
@endif

<div class="grid lg:grid-cols-3 gap-4">
  {{-- Kartu Profil --}}
  <div class="lg:col-span-1 rounded-2xl bg-white/80 border border-white/60 shadow-soft p-4">
    <div class="flex items-center gap-3">
      <div class="w-12 h-12 rounded-xl bg-brand-600 text-white grid place-items-center text-lg">
        {{ strtoupper(substr($customer->nama_lengkap,0,1)) }}
      </div>
      <div>
        <div class="font-semibold">{{ $customer->nama_lengkap }}</div>
        <div class="text-sm text-slate-500">{{ $customer->email }}</div>
      </div>
    </div>

    <div class="mt-4 space-y-2 text-sm">
      <div class="flex justify-between"><span class="text-slate-500">No HP</span><span class="font-medium">{{ $customer->nomor_hp ?? '-' }}</span></div>
      <div class="flex justify-between"><span class="text-slate-500">Status</span>
        <span>
          @php $s = strtolower($customer->status_persetujuan); @endphp
          @if(in_array($s,['menunggu','pending']))
            <span class="px-2 py-1 rounded-lg text-xs bg-yellow-100 text-yellow-700">Menunggu</span>
          @elseif($s==='disetujui')
            <span class="px-2 py-1 rounded-lg text-xs bg-emerald-100 text-emerald-700">Disetujui</span>
          @else
            <span class="px-2 py-1 rounded-lg text-xs bg-rose-100 text-rose-700">Ditolak</span>
          @endif
        </span>
      </div>
      @if($customer->disetujui_pada)
        <div class="flex justify-between"><span class="text-slate-500">Disetujui pada</span><span class="font-medium">{{ $customer->disetujui_pada->format('d M Y H:i') }}</span></div>
      @endif
    </div>

    <div class="mt-4 text-sm text-slate-600">
      <div class="text-xs uppercase tracking-wide text-slate-500 mb-1">Alamat</div>
      {{ $customer->alamat ?? '-' }}<br>
      {{ $customer->kota ?? '' }}{{ $customer->kota && $customer->provinsi ? ', ' : '' }}{{ $customer->provinsi ?? '' }}<br>
      {{ $customer->kode_pos ?? '' }}
    </div>

    <div class="mt-5 flex gap-2">
      <a href="{{ route('admin.customers.edit', $customer) }}" class="px-3 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700">Edit</a>

      <form action="{{ route('admin.customers.destroy', $customer) }}" method="POST"
            onsubmit="return confirm('Hapus pelanggan ini?')">
        @csrf @method('DELETE')
        <button class="px-3 py-2 rounded-xl bg-rose-600 text-white hover:bg-rose-700">Hapus</button>
      </form>
    </div>
  </div>

  {{-- Kartu Riwayat Produksi --}}
  <div class="lg:col-span-2 rounded-2xl bg-white/80 border border-white/60 shadow-soft p-4">
    <div class="flex items-center justify-between mb-3">
      <h3 class="text-sm font-semibold">Riwayat Produksi</h3>
      <div class="text-xs text-slate-500">Total: {{ $produksi->total() }}</div>
    </div>

    @if($produksi->count())
      <div class="overflow-x-auto">
        <table class="min-w-full text-sm">
          <thead class="bg-white/60 text-slate-600">
          <tr>
            <th class="text-left px-3 py-2">Nomor</th>
            <th class="text-left px-3 py-2">Produk</th>
            <th class="text-left px-3 py-2">Jumlah</th>
            <th class="text-left px-3 py-2">Status</th>
            <th class="text-left px-3 py-2">Dibuat</th>
          </tr>
          </thead>
          <tbody class="divide-y divide-slate-100/70">
          @foreach($produksi as $p)
            <tr>
              <td class="px-3 py-2 font-medium">{{ $p->nomor_produksi ?? '-' }}</td>
              <td class="px-3 py-2">{{ $p->produk ?? '-' }}</td>
              <td class="px-3 py-2">{{ $p->jumlah ?? '-' }}</td>
              <td class="px-3 py-2"><span class="px-2 py-1 rounded-md bg-slate-100">{{ $p->status_sekarang ?? '-' }}</span></td>
              <td class="px-3 py-2 text-slate-500">{{ optional($p->created_at)->diffForHumans() }}</td>
            </tr>
          @endforeach
          </tbody>
        </table>
      </div>

      <div class="mt-3">{{ $produksi->links() }}</div>
    @else
      <div class="text-slate-500">Belum ada produksi.</div>
    @endif
  </div>
</div>
@endsection
