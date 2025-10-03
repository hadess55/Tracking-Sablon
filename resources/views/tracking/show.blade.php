{{-- resources/views/tracking/show.blade.php --}}
@extends('layouts.app') {{-- atau layout apa pun yg kamu pakai --}}

@section('content')
  <div class="max-w-4xl mx-auto p-6">
    <h1 class="text-xl font-semibold mb-4">Lacak Produksi</h1>

    <div class="rounded-xl border bg-white p-4">
      <p><b>Nomor</b>: {{ $produksi->nomor_produksi }}</p>
      <p><b>Pelanggan</b>: {{ $produksi->pelanggan->nama_lengkap ?? '-' }}</p>
      <p><b>Produk</b>: {{ $produksi->produk }}</p>
      <p><b>Jumlah</b>: {{ $produksi->jumlah }}</p>
      <p><b>Status</b>: {{ $produksi->status_sekarang }}</p>
    </div>

    <div class="rounded-xl border bg-white p-4 mt-4">
      <h2 class="font-medium mb-2">Riwayat</h2>
      <table class="w-full text-sm">
        <thead>
          <tr class="text-left">
            <th class="py-2">Waktu</th>
            <th class="py-2">Status</th>
            <th class="py-2">Catatan</th>
          </tr>
        </thead>
        <tbody>
          @forelse($produksi->riwayat as $r)
            <tr class="border-t">
              <td class="py-2">{{ $r->dilakukan_pada?->format('d M Y H:i') }}</td>
              <td class="py-2">{{ $r->tahapan }}</td>
              <td class="py-2">{{ $r->catatan ?: '—' }}</td>
            </tr>
          @empty
            <tr><td colspan="3" class="py-4 text-slate-500">Belum ada riwayat.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
@endsection
