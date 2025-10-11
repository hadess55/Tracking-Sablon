<div class="h-full bg-white/60 backdrop-blur-xl border-r border-white/40 shadow-glass flex flex-col">
  <div class="h-16 px-2 lg:px-3 flex items-center gap-2 lg:gap-3 border-b border-white/50">
    <span class="inline-flex items-center justify-center w-10 h-10 rounded-lg bg-brand-600 text-white shadow-soft">TP</span>

    <div class="overflow-hidden" x-show="openSidebar" x-transition>
      <div class="font-semibold leading-5 truncate">Tracking Produksi</div>
      <div class="text-[11px] text-slate-500 truncate">Admin</div>
    </div>

    <button class="ml-auto hidden lg:inline-flex items-center justify-center w-9 h-9 rounded-lg hover:bg-white/70"
            @click="openSidebar = !openSidebar" aria-label="Collapse / Expand">
      <svg x-show="openSidebar" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
      </svg>
      <svg x-show="!openSidebar" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-width="2" d="M9 5l7 7-7 7"/>
      </svg>
    </button>
  </div>

  <div class="grid grid-cols-2 gap-3 px-4 pt-3 "x-show="openSidebar" x-transition>
    <div class="rounded-xl border bg-white/70 backdrop-blur p-3">
      <div class="text-xs text-slate-500">Sedang Proses</div>
      <div class="mt-1 text-md font-semibold leading-none">{{ $sedangProses ?? 0 }}</div>
    </div>
    <div class="rounded-xl border bg-white/70 backdrop-blur p-3">
      <div class="text-xs text-slate-500">Selesai</div>
      <div class="mt-1 text-md font-semibold leading-none">{{ $selesai ?? 0 }}</div>
    </div>
  </div>


 @php
  $activeCls = 'bg-white/80 text-slate-900 shadow-soft ring-1 ring-white/60';
  $idleCls   = 'text-slate-700 hover:bg-white/60';

  $isDash  = request()->routeIs('dashboard') || request()->is('dashboard');
  $isProd  = request()->routeIs('admin.produksi.*');
  $isOrder = request()->routeIs('admin.pesanan.*');
  $isCust  = request()->routeIs('admin.customer.*');
@endphp

<nav class="p-2 space-y-1">

  {{-- Dashboard --}}
  <a href="{{ route('dashboard') }}"
     aria-label="Dashboard"
     class="group relative flex items-center rounded-xl transition {{ $isDash ? $activeCls : $idleCls }}"
     :class="openSidebar ? 'gap-3 px-3 py-2.5 justify-start' : 'gap-0 px-2 py-2.5 justify-center'">
    {{-- icon: squares-2x2 --}}
    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-width="1.8"
            d="M3.75 4.5h6.5v6.5h-6.5zM13.75 4.5h6.5v4.5h-6.5zM13.75 13.75h6.5v6.5h-6.5zM3.75 12.75h6.5v7.5h-6.5z"/>
    </svg>
    <span class="truncate" x-show="openSidebar" x-transition>Dashboard</span>
    <span x-show="!openSidebar"
          class="absolute left-full top-1/2 -translate-y-1/2 ml-2 whitespace-nowrap rounded-lg bg-gray-800 text-white text-xs px-2 py-1 opacity-0 group-hover:opacity-100 pointer-events-none transition">
      Dashboard
    </span>
  </a>

  {{-- Produksi --}}
  <a href="{{ route('admin.produksi.index') }}"
     aria-label="Produksi"
     class="group relative flex items-center rounded-xl transition {{ $isProd ? $activeCls : $idleCls }}"
     :class="openSidebar ? 'gap-3 px-3 py-2.5 justify-start' : 'gap-0 px-2 py-2.5 justify-center'">
    {{-- icon: wrench-screwdriver --}}
    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-width="1.8"
            d="M15.2 5.3a3.75 3.75 0 0 0-5.3 5.3l8.5 8.5a1.5 1.5 0 0 0 2.1-2.1l-8.5-8.5M8.75 15.25 4.5 19.5m2-6.25-2.5 2.5m5 0-2.5 2.5"/>
    </svg>
    <span class="truncate" x-show="openSidebar" x-transition>Produksi</span>
    <span x-show="!openSidebar"
          class="absolute left-full top-1/2 -translate-y-1/2 ml-2 whitespace-nowrap rounded-lg bg-gray-800 text-white text-xs px-2 py-1 opacity-0 group-hover:opacity-100 pointer-events-none transition">
      Produksi
    </span>
  </a>

  {{-- Pesanan --}}
  <a href="{{ route('admin.pesanan.index') }}"
     aria-label="Pesanan"
     class="group relative flex items-center rounded-xl transition {{ $isOrder ? $activeCls : $idleCls }}"
     :class="openSidebar ? 'gap-3 px-3 py-2.5 justify-start' : 'gap-0 px-2 py-2.5 justify-center'">
    {{-- icon: clipboard-document-list --}}
    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-width="1.8"
            d="M9 4.5h6a1.5 1.5 0 0 1 1.5 1.5V6A2.5 2.5 0 0 1 18 8.5V19a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2V8.5A2.5 2.5 0 0 1 7.5 6v0a1.5 1.5 0 0 1 1.5-1.5Zm1.5 6.5h5m-5 3h5m-5 3h3"/>
      <path stroke-linecap="round" stroke-width="1.8"
            d="M9 6h6M9 6a1.5 1.5 0 0 1-3 0"/>
    </svg>
    <span class="truncate" x-show="openSidebar" x-transition>Pesanan</span>
    <span x-show="!openSidebar"
          class="absolute left-full top-1/2 -translate-y-1/2 ml-2 whitespace-nowrap rounded-lg bg-gray-800 text-white text-xs px-2 py-1 opacity-0 group-hover:opacity-100 pointer-events-none transition">
      Pesanan
    </span>
  </a>

  {{-- Customer --}}
  <a href="{{ route('admin.customer.index') }}"
     aria-label="Customer"
     class="group relative flex items-center rounded-xl transition {{ $isCust ? $activeCls : $idleCls }}"
     :class="openSidebar ? 'gap-3 px-3 py-2.5 justify-start' : 'gap-0 px-2 py-2.5 justify-center'">
    {{-- icon: users --}}
    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-width="1.8"
            d="M16 11a4 4 0 1 0-4-4 4 4 0 0 0 4 4Zm-8 0a4 4 0 1 0-4-4 4 4 0 0 0 4 4Zm8 2c-2.5 0-4.5 2-4.5 4.5V20h9v-2.5c0-2.5-2-4.5-4.5-4.5Zm-8 0C5.5 13 3.5 15 3.5 17.5V20h9v-2.5C12.5 15 10.5 13 8 13Z"/>
    </svg>
    <span class="truncate" x-show="openSidebar" x-transition>Customer</span>
    <span x-show="!openSidebar"
          class="absolute left-full top-1/2 -translate-y-1/2 ml-2 whitespace-nowrap rounded-lg bg-gray-800 text-white text-xs px-2 py-1 opacity-0 group-hover:opacity-100 pointer-events-none transition">
      Customer
    </span>
  </a>

</nav>



  <div class="mt-auto p-2 lg:p-3 border-t border-white/50">
    @php

  $logoutAction = \Illuminate\Support\Facades\Route::has('logout')
      ? route('logout')
      : url('/logout');
@endphp

<form method="POST" action="{{ $logoutAction }}" class="w-full">
  @csrf
  <button type="submit"
          class="w-full flex items-center gap-2 rounded-xl border border-white/60 bg-white/70 text-slate-800 shadow-soft transition hover:bg-white"
          :class="openSidebar ? 'justify-center px-3 py-2.5' : 'justify-center p-2.5 rounded-full w-10 h-10 mx-auto'">
    {{-- icon logout --}}
    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-width="2"
            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a2 2 0 01-2 2H6a2 2 0 01-2-2V7a2 2 0 012-2h5a2 2 0 012 2v1"/>
    </svg>
    <span x-show="openSidebar" x-transition>Keluar</span>
  </button>
</form>

    <a href="{{ url('/') }}"
       class="mt-2 block text-center text-[11px] text-slate-500 hover:text-slate-700"
       x-show="openSidebar" x-transition>
      ← Kembali ke situs publik
    </a>
  </div>
</div>
