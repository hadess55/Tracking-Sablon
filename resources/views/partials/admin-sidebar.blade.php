<div class="h-full bg-white/60 backdrop-blur-xl border-r border-white/40 shadow-glass flex flex-col">
  <div class="h-16 px-2 lg:px-3 flex items-center gap-2 lg:gap-3 border-b border-white/50">
    <span class="inline-flex items-center justify-center w-10 h-10 rounded-lg bg-brand-600 text-white shadow-soft">TP</span>

    <div class="overflow-hidden" x-show="openSidebar" x-transition>
      <div class="font-semibold leading-5 truncate">Tracking Produksi</div>
      <div class="text-[11px] text-slate-500 truncate">Panel Admin</div>
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
    {{-- <div class="mt-1 text-md font-semibold leading-none">{{ $sedangProses ?? 0 }}</div> --}}
  </div>
  <div class="rounded-xl border bg-white/70 backdrop-blur p-3">
    <div class="text-xs text-slate-500">Selesai</div>
    {{-- <div class="mt-1 text-md font-semibold leading-none">{{ $selesai ?? 0 }}</div> --}}
  </div>
</div>


  <nav class="p-2 space-y-1">
    @php
      $activeCls = 'bg-white/80 text-slate-900 shadow-soft ring-1 ring-white/60';
      $idleCls   = 'text-slate-700 hover:bg-white/60';
    @endphp

    <a href="{{ url('dashboard') }}" aria-label="Dashboard"
       class="group relative flex items-center rounded-xl transition
              {{ request()->routeIs('admin.produksi.*') ? $activeCls : $idleCls }}"
       :class="openSidebar ? 'gap-3 px-3 py-2.5 justify-start' : 'gap-0 px-2 py-2.5 justify-center'">
      <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-width="2" d="M3 6h18M3 12h18M3 18h18"/></svg>
      <span class="truncate" x-show="openSidebar" x-transition>Dashboard</span>
      <span x-show="!openSidebar"
            class="absolute left-full top-1/2 -translate-y-1/2 ml-2 whitespace-nowrap rounded-lg bg-gray-800 text-white text-xs px-2 py-1 opacity-0 group-hover:opacity-100 pointer-events-none transition">
        Dashboard
      </span>
    </a>
    <a href="{{ url('#') }}" aria-label="Produksi"
        class="group relative flex items-center rounded-xl transition
        {{ request()->routeIs('admin.produksi.*') ? $activeCls : $idleCls }}"
        :class="openSidebar ? 'gap-3 px-3 py-2.5 justify-start' : 'gap-0 px-2 py-2.5 justify-center'">
        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-width="2" d="M3 6h18M3 12h18M3 18h18"/></svg>
        <span class="truncate" x-show="openSidebar" x-transition>Produksi</span>
        <span x-show="!openSidebar"
        class="absolute left-full top-1/2 -translate-y-1/2 ml-2 whitespace-nowrap rounded-lg bg-gray-800 text-white text-xs px-2 py-1 opacity-0 group-hover:opacity-100 pointer-events-none transition">
        Produksi
      </span>
    </a>

<a href="{{ route('pesanan.index') }}" aria-label="Pesanan"
   class="group relative flex items-center rounded-xl transition
          {{ request()->routeIs('admin.produksi.*') ? $activeCls : $idleCls }}"
   :class="openSidebar ? 'gap-3 px-3 py-2.5 justify-start' : 'gap-0 px-2 py-2.5 justify-center'">
  <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-width="2" d="M3 6h18M3 12h18M3 18h18"/></svg>
  <span class="truncate" x-show="openSidebar" x-transition>Pesanan</span>
  <span x-show="!openSidebar"
        class="absolute left-full top-1/2 -translate-y-1/2 ml-2 whitespace-nowrap rounded-lg bg-gray-800 text-white text-xs px-2 py-1 opacity-0 group-hover:opacity-100 pointer-events-none transition">
    Pesanan
  </span>
</a>

      <a href="{{ route('admin.customer.index') }}" aria-label="Customer"
      class="group relative flex items-center rounded-xl transition
      {{ request()->routeIs('admin.customers.*') ? $activeCls : $idleCls }}"
      :class="openSidebar ? 'gap-3 px-3 py-2.5 justify-start' : 'gap-0 px-2 py-2.5 justify-center'">
      <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-width="2"
              d="M16 11c1.7 0 3-1.8 3-4s-1.3-4-3-4-3 1.8-3 4 1.3 4 3 4zM8 11c1.7 0 3-1.8 3-4S9.7 3 8 3 5 4.8 5 7s1.3 4 3 4zm8 2c-2.2 0-4 1.8-4 4v3h8v-3c0-2.2-1.8-4-4-4zM8 13c-2.2 0-4 1.8-4 4v3h8v-3c0-2.2-1.8-4-4-4z"/>
      </svg>
      <span class="truncate" x-show="openSidebar" x-transition>Customer</span>
      <span x-show="!openSidebar"
            class="absolute left-full top-1/2 -translate-y-1/2 ml-2 whitespace-nowrap rounded-lg bg-gray-800 text-white text-xs px-2 py-1 opacity-0 group-hover:opacity-100 pointer-events-none transition">
        Customer
      </span>
    </a>

    <a href="{{ url('#') }}" aria-label="Tambah Produksi"
       class="group relative flex items-center rounded-xl transition
              {{ request()->routeIs('admin.produksi.create') ? $activeCls : $idleCls }}"
       :class="openSidebar ? 'gap-3 px-3 py-2.5 justify-start' : 'gap-0 px-2 py-2.5 justify-center'">
      <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
      <span class="truncate" x-show="openSidebar" x-transition>Tambah Produksi</span>
      <span x-show="!openSidebar"
            class="absolute left-full top-1/2 -translate-y-1/2 ml-2 whitespace-nowrap rounded-lg bg-gray-800 text-white text-xs px-2 py-1 opacity-0 group-hover:opacity-100 pointer-events-none transition">
        Tambah Produksi
      </span>
    </a>
  </nav>


  <div class="mt-auto p-2 lg:p-3 border-t border-white/50">
    <form method="POST" action="{{ url('#') }}" class="w-full">
      @csrf
      <button
        class="w-full flex items-center gap-2 rounded-xl border border-white/60 bg-white/70 text-slate-800 shadow-soft transition hover:bg-white"
        :class="openSidebar ? 'justify-center px-3 py-2.5' : 'justify-center p-2.5 rounded-full w-10 h-10 mx-auto'">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a2 2 0 01-2 2H6a2 2 0 01-2-2V7a2 2 0 012-2h5a2 2 0 012 2v1"/></svg>
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
