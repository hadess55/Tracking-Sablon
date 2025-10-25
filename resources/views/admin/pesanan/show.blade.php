@extends('layouts.admin')
@section('header','Detail Pesanan')

@section('content')
@php
    $badge = match($pesanan->status) {
        'disetujui' => 'bg-green-100 text-green-800 border-green-200',
        'ditolak'   => 'bg-red-100 text-red-800 border-red-200',
        default     => 'bg-amber-100 text-amber-800 border-amber-200',
    };
@endphp

{{-- Header actions --}}
<div class="mb-4 flex items-center justify-between">
  <a href="{{ route('admin.pesanan.index', ['status'=>request('status')]) }}"
     class="rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-700 hover:bg-gray-50">← Kembali</a>

  <div class="flex items-center gap-2">
    <a href="{{ route('admin.pesanan.edit',$pesanan) }}"
       class="rounded-lg bg-amber-500 px-3 py-2 text-white hover:bg-amber-600">Edit</a>

    @if($pesanan->status === 'menunggu')
      <form method="POST" action="{{ route('admin.pesanan.setujui',$pesanan) }}">
        @csrf
        <button class="rounded-lg bg-green-600 px-3 py-2 text-white hover:bg-green-700">Setujui</button>
      </form>

      <details class="relative">
        <summary class="cursor-pointer rounded-lg bg-red-600 px-3 py-2 text-white hover:bg-red-700 list-none">Tolak</summary>
        <form method="POST" action="{{ route('admin.pesanan.tolak',$pesanan) }}"
              class="absolute right-0 z-10 mt-2 w-72 rounded-lg border border-gray-200 bg-white p-3 shadow-lg">
          @csrf
          <label class="mb-1 block text-sm text-gray-700">Alasan penolakan</label>
          <textarea name="alasan" rows="3" required class="w-full rounded-md border-gray-300"></textarea>
          <div class="mt-2 text-right">
            <button class="rounded-md bg-red-600 px-3 py-1.5 text-white hover:bg-red-700">Kirim</button>
          </div>
        </form>
      </details>
    @endif
  </div>
</div>

<div class="grid gap-4 lg:grid-cols-3">
  {{-- Ringkasan --}}
  <div class="lg:col-span-2 rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">
    <div class="mb-4 flex items-start justify-between">
      <div>
        <h2 class="text-lg font-semibold text-gray-900">{{ $pesanan->produk }}</h2>
        <div class="mt-1 text-sm text-gray-500">ID #{{ $pesanan->id }}</div>
      </div>
      <span class="rounded-full border px-3 py-1 text-xs font-medium {{ $badge }}">
        {{ strtoupper($pesanan->status) }}
      </span>
    </div>

    <dl class="grid grid-cols-1 gap-4 md:grid-cols-2 text-sm">
      <div>
        <dt class="text-gray-500">Nomor Resi</dt>
        <dd class="mt-1">{{ $pesanan->nomor_resi ?: '—' }}</dd>
      </div>
      <div>
        <dt class="text-gray-500">Tanggal Disetujui</dt>
        <dd class="mt-1">
          {{ $pesanan->tanggal_disetujui ? $pesanan->tanggal_disetujui->format('d M Y H:i') : '—' }}
        </dd>
      </div>

      <div>
        <dt class="text-gray-500">Bahan</dt>
        <dd class="mt-1">{{ $pesanan->bahan ?: '—' }}</dd>
      </div>
      <div>
        <dt class="text-gray-500">Warna</dt>
        <dd class="mt-1">{{ $pesanan->warna ?: '—' }}</dd>
      </div>

      <div>
        <dt class="text-gray-500">Ukuran</dt>
        <dd class="mt-1">{{ $pesanan->ukuran_ringkas }}</dd>
      </div>
      <div>
        <dt class="text-gray-500">Jumlah</dt>
        <dd class="mt-1 font-medium text-gray-900">{{ $pesanan->jumlah }}</dd>
      </div>
      <div>
        <dt class="text-gray-500">Alasan ditolak</dt>
        <dd class="mt-1">{{ $pesanan->alasan_ditolak ?: '—' }}</dd>
      </div>
    </dl>
  </div>

  {{-- Customer --}}
  <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">
    <h3 class="mb-3 text-base font-semibold text-gray-900">Customer</h3>
    <div class="text-sm">
      <div class="font-medium text-gray-900">{{ $pesanan->pengguna->name ?? '—' }}</div>
      <div class="text-gray-600">{{ $pesanan->pengguna->email ?? '—' }}</div>
      <div class="mt-2 text-gray-700">
        {{ $pesanan->pengguna->no_hp ?? '—' }}
      </div>
      @if(optional($pesanan->pengguna)->alamat_lengkap)
        <div class="mt-2 text-gray-700">
          <span class="text-gray-500">Alamat:</span>
          {{ $pesanan->pengguna->alamat_lengkap }}
        </div>
      @endif
    </div>
  </div>

  {{-- Lampiran & Deskripsi --}}
  <div class="lg:col-span-3 rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">
    <div class="mb-4 flex items-center justify-between">
      <h3 class="text-base font-semibold text-gray-900">Lampiran & Deskripsi</h3>
      @if($pesanan->tautan_drive)
        <div class="flex items-center gap-2">
          <a href="{{ $pesanan->tautan_drive }}" target="_blank" rel="noopener"
             class="rounded-md bg-indigo-600 px-3 py-1.5 text-sm text-white hover:bg-indigo-700">Buka Link</a>
          <button id="copyLinkBtn" data-link="{{ $pesanan->tautan_drive }}"
                  class="rounded-md border border-gray-300 bg-white px-3 py-1.5 text-sm text-gray-700 hover:bg-gray-50">
            Salin Link
          </button>
        </div>
      @endif
    </div>

    <div class="space-y-3 text-sm">
      <div>
        <div class="text-gray-500">Tautan Drive</div>
        <div class="mt-1">
          @if($pesanan->tautan_drive)
            <a href="{{ $pesanan->tautan_drive }}" target="_blank" class="text-indigo-600 underline">
              {{ $pesanan->tautan_drive }}
            </a>
          @else
            <span class="text-gray-500">—</span>
          @endif
        </div>
      </div>

      <div>
        <div class="text-gray-500">Deskripsi</div>
        <div class="mt-1 whitespace-pre-line">{{ $pesanan->deskripsi ?: '—' }}</div>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
  const btn = document.getElementById('copyLinkBtn');
  if (btn) {
    btn.addEventListener('click', async () => {
      try {
        await navigator.clipboard.writeText(btn.dataset.link);
        btn.textContent = 'Tersalin ✓';
        setTimeout(() => btn.textContent = 'Salin Link', 1500);
      } catch (e) {
        alert('Gagal menyalin link');
      }
    });
  }
</script>
@endpush
