@extends('layouts.app')

@section('content')
  <div class="grid gap-6 lg:grid-cols-3">
    {{-- Ringkasan --}}
    <x-card class="p-5 sm:p-6 lg:col-span-1 animate-fade-in">
      <div class="flex items-start justify-between">
        <div>
          <h2 class="text-lg font-semibold text-gray-900">Nomor Produksi</h2>
          <p class="text-2xl font-bold text-gray-900">{{ $produksi->nomor_produksi }}</p>
        </div>
        <x-status-badge :status="$produksi->status_sekarang" class="animate-pulse-once"/>
      </div>

      <dl class="mt-5 grid grid-cols-1 gap-3 text-sm">
        <div class="flex justify-between">
          <dt class="text-gray-600">Customer</dt>
          <dd class="font-medium text-gray-900">{{ $produksi->pelanggan->nama_lengkap ?? '-' }}</dd>
        </div>
        <div class="flex justify-between">
          <dt class="text-gray-600">Produk</dt>
          <dd class="font-medium text-gray-900">{{ $produksi->produk }} ({{ $produksi->jumlah }})</dd>
        </div>
        <div>
          <dt class="text-gray-600 mb-1">Catatan</dt>
          <dd class="text-gray-800">{{ $produksi->catatan ?? '-' }}</dd>
        </div>
      </dl>

      <div class="mt-6">
        <x-button as="a" href="{{ route('tracking.form') }}" variant="secondary">Kembali</x-button>
      </div>
    </x-card>

    {{-- Timeline --}}
    <x-card class="p-5 sm:p-6 lg:col-span-2 animate-slide-up">
      <h3 class="text-lg font-semibold text-gray-900 mb-4">Riwayat Produksi</h3>
      <ol class="space-y-5">
        @forelse($produksi->riwayat as $r)
          <x-timeline-item
            :tahapan="$r->tahapan"
            :waktu="optional($r->dilakukan_pada)->format('d M Y H:i') ?? $r->created_at->format('d M Y H:i')"
            :keterangan="$r->keterangan"
          />
        @empty
          <li class="text-sm text-gray-600">Belum ada riwayat.</li>
        @endforelse
      </ol>
    </x-card>
  </div>
@endsection
