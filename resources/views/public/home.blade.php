@extends('layouts.app')

@section('content')

  {{-- background lembut --}}
  <div class="fixed inset-0 -z-10">
    <div class="absolute inset-x-0 top-0 h-64 bg-gradient-to-b from-indigo-50 to-transparent"></div>
    <div class="absolute inset-0 bg-[radial-gradient(rgba(99,102,241,.12)_1px,transparent_1px)] [background-size:16px_16px]"></div>
  </div>

  @php
    use Illuminate\Support\Str;
@endphp

{{-- HERO --}}
<section class="max-w-6xl mx-auto px-4 pt-10 pb-6 sm:pt-16 sm:pb-10 mt-10 sm:py-12">
  <h1 class="text-center">
    <span class="text-3xl sm:text-4xl font-semibold tracking-tight text-slate-900">
      Lacak Progres Produksi Sablon Anda
    </span>
  </h1>
  <p class="mt-3 text-slate-600 text-center">
    Masukkan <b>nomor resi</b> untuk melihat status terkini, riwayat, dan catatan pekerjaan.
  </p>
</section>

<div class="mx-auto max-w-3xl">
  <form method="GET" action="{{ url('tracking') }}"
        class="flex items-stretch gap-2 rounded-2xl border border-slate-200 bg-white p-2 shadow-sm ring-1 ring-transparent focus-within:ring-indigo-200">
    <div class="flex items-center px-3 text-slate-400">
      <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M21 21l-3-3m-4 1a7 7 0 110-14 7 7 0 010 14z"/>
      </svg>
    </div>

    <input
      type="text"
      name="nomor"
      class="w-full rounded-xl border-0 px-2 py-3 text-[15px] placeholder:text-slate-400 focus:outline-none"
      placeholder="Masukkan nomor resi…"
      value="{{ old('nomor', $nomor ?? '') }}"
      autocomplete="off"
      required
    />

    <button type="submit"
            class="shrink-0 rounded-xl bg-indigo-600 px-5 py-3 text-white font-medium hover:bg-indigo-700 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500">
      Lacak
    </button>
  </form>

  @error('nomor')
    <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
  @enderror
</div>

{{-- HASIL TRACKING --}}
@if(!empty($nomor))
  @php

    // Mapping badge & dot warna
    $badge = [
      'Antri'      => 'bg-slate-100 text-slate-700 ring-1 ring-slate-200',
      'Desain'     => 'bg-sky-100 text-sky-700 ring-1 ring-sky-200',
      'Cetak'      => 'bg-indigo-100 text-indigo-700 ring-1 ring-indigo-200',
      'Finishing'  => 'bg-amber-100 text-amber-800 ring-1 ring-amber-200',
      'Packaging'  => 'bg-teal-100 text-teal-700 ring-1 ring-teal-200',
      'Selesai'    => 'bg-emerald-100 text-emerald-700 ring-1 ring-emerald-200',
    ];
    $dot = [
      'Antri'      => 'bg-slate-400',
      'Desain'     => 'bg-sky-500',
      'Cetak'      => 'bg-indigo-500',
      'Finishing'  => 'bg-amber-500',
      'Packaging'  => 'bg-teal-500',
      'Selesai'    => 'bg-emerald-600',
    ];

    $statusTitle   = Str::title($statusNow ?? '-');
    $statusBadge   = $badge[$statusTitle] ?? 'bg-slate-100 text-slate-700 ring-1 ring-slate-200';
    $currentIdx    = array_search($statusTitle, $steps, true);
  @endphp

  <div class="max-w-5xl mx-auto mt-8">
    @if($produksi)
      <div class="rounded-2xl border border-slate-200 bg-white/80 backdrop-blur p-6 shadow-soft">


<div class="mb-6 grid grid-cols-1 sm:grid-cols-3 gap-6 items-start">
  {{-- Nomor Produksi --}}
  <div>
    <div class="text-xs uppercase tracking-wide text-slate-500">Nomor Produksi</div>
    <div class="mt-1 flex items-center gap-3">
      <p class="text-lg font-semibold text-slate-900">
        {{ $produksi->nomor_resi }}
      </p>
      <button
        x-data
        x-on:click.prevent="
          navigator.clipboard.writeText('{{ $produksi->nomor_resi }}');
          $el.innerText = 'Disalin';
          setTimeout(()=>{$el.innerText='Salin Nomor'},1200)
        "
        class="text-xs rounded-lg bg-slate-50 px-2.5 py-1 text-slate-600 ring-1 ring-slate-200 hover:bg-slate-100"
      >
        Salin Nomor
      </button>
    </div>
  </div>


  <div>
    <div class="text-xs uppercase tracking-wide text-slate-500">Pelanggan</div>
    <div class="mt-1 font-medium text-slate-900">
      {{ $produksi->pesanan->pengguna->name ?? $produksi->pesanan->customer->nama_lengkap ?? '-' }}
    </div>
  </div>


  <div class="sm:text-right sm:justify-self-end">
    <div class="text-xs uppercase tracking-wide text-slate-500">Status Sekarang</div>
    <div class="mt-1 inline-flex items-center gap-2">
      <span class="h-2 w-2 rounded-full {{ ($statusNow ?? '') === 'Selesai' ? 'bg-emerald-600' : 'bg-indigo-500' }}"></span>
      <span class="rounded-full px-2.5 py-1 text-xs font-medium bg-slate-100 text-slate-700 ring-1 ring-slate-200">
        {{ \Illuminate\Support\Str::title($statusNow ?? '-') }}
      </span>
    </div>
  </div>
</div>


        {{-- Progress Steps --}}
        <div class="mt-8">
          <div class="text-xs uppercase tracking-wide text-slate-500 mb-2">Progress</div>

          <div class="relative">
            <div class="flex items-center justify-between">
              @foreach($steps as $i => $step)
                @php $active = $currentIdx !== false && $i <= $currentIdx; @endphp

                <div class="flex-1 first:flex-none last:flex-none">
                  <div class="flex items-center gap-3">
                    <span class="block h-2.5 w-2.5 rounded-full
                      {{ $active ? 'bg-indigo-600 ring-4 ring-indigo-100' : 'bg-slate-300 ring-4 ring-slate-100' }}">
                    </span>
                    <span class="text-[13px] {{ $active ? 'text-slate-900 font-medium' : 'text-slate-400' }}">
                      {{ $step }}
                    </span>
                  </div>
                </div>

                @if(!$loop->last)
                  <div class="mx-1 h-0.5 flex-1
                    {{ $currentIdx !== false && $i < $currentIdx ? 'bg-indigo-200' : 'bg-slate-200' }}">
                  </div>
                @endif
              @endforeach
            </div>
          </div>
        </div>

        {{-- Timeline Riwayat --}}
        <div class="mt-8">
          <div class="text-xs uppercase tracking-wide text-slate-500 mb-3">Riwayat</div>

          <ul role="list" class="space-y-4">
            @forelse($produksi->logs as $r)
              @php
                $t      = Str::title($r->tahapan);
                $dotCol = $dot[$t] ?? 'bg-slate-400';
                $waktu  = $r->created_at ?? now();
              @endphp

              <li class="relative pl-7">
                <span class="absolute left-1.5 top-2 -ml-px h-full w-px bg-slate-200"></span>
                <span class="absolute left-0 top-1.5 h-3 w-3 rounded-full ring-4 ring-white {{ $dotCol }}"></span>

                <div class="flex flex-wrap items-center gap-x-3">
                  <span class="font-medium text-slate-900">{{ $t }}</span>
                  <span class="text-xs text-slate-500">
                    {{ \Carbon\Carbon::parse($waktu)->format('d M Y H:i') }}
                  </span>
                </div>

                @if($r->catatan)
                  <p class="mt-1 text-sm text-slate-600">{{ $r->catatan }}</p>
                @endif
              </li>
            @empty
              <li class="text-sm text-slate-500">Belum ada riwayat.</li>
            @endforelse
          </ul>
        </div>
      </div>
    @else
      <div class="rounded-2xl border border-amber-200 bg-amber-50 p-4 text-amber-800 shadow-soft mt-6">
        Nomor <b>{{ $nomor }}</b> tidak ditemukan.
      </div>
    @endif
  </div>
@endif



  {{-- SECTION 2 --}}
  <section class="max-w-6xl mx-auto px-4 py-8 sm:py-12">
    <div class="text-center mb-8">
      <h2 class="text-xl sm:text-2xl font-semibold text-slate-900">Cara Kerja</h2>
      <p class="mt-2 text-slate-600">Tiga langkah sederhana memantau pesanan Anda.</p>
    </div>

    <div class="grid gap-4 sm:grid-cols-3">
      <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
        <div class="mb-3 inline-flex h-10 w-10 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600">
          <svg class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2L2 7l10 5 10-5-10-5Zm0 7l10 5-10 5L2 14l10-5Z"/></svg>
        </div>
        <h3 class="font-semibold text-slate-900">1. Dapatkan Nomor</h3>
        <p class="mt-1 text-sm text-slate-600">Nomor produksi otomatis dibuat saat order Anda tercatat.</p>
      </div>

      <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
        <div class="mb-3 inline-flex h-10 w-10 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600">
          <svg class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor"><path d="M4 6h16v2H4V6Zm0 5h16v2H4v-2Zm0 5h16v2H4v-2Z"/></svg>
        </div>
        <h3 class="font-semibold text-slate-900">2. Masukkan di Form</h3>
        <p class="mt-1 text-sm text-slate-600">Tempel nomor pada kolom di atas, lalu tekan tombol <b>Lacak</b>.</p>
      </div>

      <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
        <div class="mb-3 inline-flex h-10 w-10 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600">
          <svg class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor"><path d="M9 12l2 2 4-4 1.5 1.5-5.5 5.5L7.5 13.5 9 12Z"/></svg>
        </div>
        <h3 class="font-semibold text-slate-900">3. Pantau Progres</h3>
        <p class="mt-1 text-sm text-slate-600">Lihat status terkini, riwayat tahapan, dan catatan pekerjaan.</p>
      </div>
    </div>
  </section>

  {{-- SECTION 3 --}}
  <section class="max-w-6xl mx-auto px-4 pb-16 sm:pb-20">
    <div class="grid gap-4 sm:grid-cols-3">
      <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
        <h4 class="font-semibold text-slate-900">Update Real-time</h4>
        <p class="mt-1 text-sm text-slate-600">Status diperbarui setiap ada perubahan tahapan produksi.</p>
      </div>
      <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
        <h4 class="font-semibold text-slate-900">Notifikasi WhatsApp</h4>
        <p class="mt-1 text-sm text-slate-600">Opsional otomatis kirim pesan saat progres penting.</p>
      </div>
      <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
        <h4 class="font-semibold text-slate-900">Arsip & Riwayat</h4>
        <p class="mt-1 text-sm text-slate-600">Riwayat produksi tersimpan rapi untuk referensi di masa depan.</p>
      </div>
    </div>

  </section>

  {{-- SECTION: 4 --}}
<section class="max-w-5xl mx-auto mt-16">
  <x-section-title title="Pertanyaan Umum" desc="Beberapa jawaban singkat yang sering ditanyakan." />

  <div x-data="{ open: 1 }" class="space-y-3">

    <div class="rounded-xl border border-slate-200 bg-white/80 shadow-sm">
      <button
        type="button"
        class="w-full flex items-center justify-between px-5 py-4"
        @click="open === 1 ? open = null : open = 1">
        <span class="text-left font-medium text-slate-900">
          Darimana saya mendapatkan nomor produksi?
        </span>
        <svg class="h-5 w-5 text-slate-500 transition-transform"
             :class="open === 1 ? 'rotate-180' : ''" viewBox="0 0 24 24" fill="none" stroke="currentColor">
          <path stroke-linecap="round" stroke-width="2" d="m6 9 6 6 6-6"/>
        </svg>
      </button>
      <div x-show="open === 1" x-transition class="px-5 pb-5 text-slate-600">
        Nomor produksi dibuat otomatis saat pesanan Anda dicatat. Minta kepada admin/operator setelah mendaftar.
      </div>
    </div>


    <div class="rounded-xl border border-slate-200 bg-white/80 shadow-sm">
      <button
        type="button"
        class="w-full flex items-center justify-between px-5 py-4"
        @click="open === 2 ? open = null : open = 2">
        <span class="text-left font-medium text-slate-900">
          Nomor saya tidak ditemukan, apa yang harus dilakukan?
        </span>
        <svg class="h-5 w-5 text-slate-500 transition-transform"
             :class="open === 2 ? 'rotate-180' : ''" viewBox="0 0 24 24" fill="none" stroke="currentColor">
          <path stroke-linecap="round" stroke-width="2" d="m6 9 6 6 6-6"/>
        </svg>
      </button>
      <div x-show="open === 2" x-transition class="px-5 pb-5 text-slate-600">
        Pastikan format nomor benar dan tanpa spasi. Jika masih tidak muncul, hubungi admin agar data Anda di-sinkronkan.
      </div>
    </div>

    <div class="rounded-xl border border-slate-200 bg-white/80 shadow-sm">
      <button
        type="button"
        class="w-full flex items-center justify-between px-5 py-4"
        @click="open === 3 ? open = null : open = 3">
        <span class="text-left font-medium text-slate-900">
          Seberapa sering status diperbarui?
        </span>
        <svg class="h-5 w-5 text-slate-500 transition-transform"
             :class="open === 3 ? 'rotate-180' : ''" viewBox="0 0 24 24" fill="none" stroke="currentColor">
          <path stroke-linecap="round" stroke-width="2" d="m6 9 6 6 6-6"/>
        </svg>
      </button>
      <div x-show="open === 3" x-transition class="px-5 pb-5 text-slate-600">
        Status diperbarui setiap kali tahapan selesai (Antri, Desain, Cetak, Finishing, Packaging, Selesai).
      </div>
    </div>


    <div class="rounded-xl border border-slate-200 bg-white/80 shadow-sm">
      <button
        type="button"
        class="w-full flex items-center justify-between px-5 py-4"
        @click="open === 4 ? open = null : open = 4">
        <span class="text-left font-medium text-slate-900">
          Bisakah saya menerima notifikasi via WhatsApp?
        </span>
        <svg class="h-5 w-5 text-slate-500 transition-transform"
             :class="open === 4 ? 'rotate-180' : ''" viewBox="0 0 24 24" fill="none" stroke="currentColor">
          <path stroke-linecap="round" stroke-width="2" d="m6 9 6 6 6-6"/>
        </svg>
      </button>
      <div x-show="open === 4" x-transition class="px-5 pb-5 text-slate-600">
        Ya, admin dapat mengaktifkan notifikasi otomatis untuk setiap perubahan status pesanan Anda.
      </div>
    </div>

  </div>
</section>

@endsection
