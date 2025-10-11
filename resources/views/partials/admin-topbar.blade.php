@props(['title' => 'Dashboard'])

<header class="sticky top-0 z-40 bg-white/80 backdrop-blur border-b border-slate-200/70">
  <div class="max-w-7xl mx-auto px-4 h-14 flex items-center justify-between">
    {{-- Kiri: Burger (mobile) + Judul --}}
    <div class="flex items-center gap-2 min-w-0">
      <button class="lg:hidden inline-flex items-center justify-center w-10 h-10 rounded-md hover:bg-slate-100"
              @click="$root.__x.$data.open = true" aria-label="Buka menu">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
        </svg>
      </button>
      <h1 class="font-semibold truncate">{{ $title }}</h1>
    </div>

    <div class="hidden md:block flex-1 max-w-xl mx-4">
      <form action="#" method="GET">
        <label class="relative block">
          <span class="absolute inset-y-0 left-3 flex items-center">
            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-width="2" d="M21 21l-4.35-4.35M11 18a7 7 0 1 1 0-14 7 7 0 0 1 0 14z"/>
            </svg>
          </span>
          <input name="q" placeholder="Cari nomor produksi, pelanggan..."
                 class="w-full pl-9 pr-3 h-10 rounded-xl bg-white/70 border border-white/60 shadow-soft
                        focus:outline-none focus:ring-2 focus:ring-brand-600/30"
                 autocomplete="off">
        </label>
      </form>
    </div>

    {{-- Kanan: Aksi cepat + Profil --}}
    <div class="flex items-center gap-2">

      <button class="inline-flex items-center justify-center w-10 h-10 rounded-xl hover:bg-slate-100"
              aria-label="Notifikasi">
        <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-width="2" d="M15 17h5l-1.4-1.4A2 2 0 0 1 18 14v-3a6 6 0 1 0-12 0v3a2 2 0 0 1-.6 1.4L4 17h5m6 0v1a3 3 0 1 1-6 0v-1m6 0H9"/>
        </svg>
      </button>
      

      {{-- Dropdown user --}}
      <div class="relative" x-data="{open:false}">
        <button @click="open=!open"
                class="inline-flex items-center gap-2 h-10 px-2 rounded-xl hover:bg-slate-100">
          <div class="w-7 h-7 rounded-full bg-brand-600 text-white grid place-items-center">
            {{ strtoupper(substr(auth()->user()->name ?? 'A',0,1)) }}
          </div>
          <span class="hidden sm:block text-sm text-slate-700 max-w-[10rem] truncate">
            {{ auth()->user()->name ?? 'Admin' }}
          </span>
        </button>

        <div x-show="open" x-transition @click.outside="open=false"
             class="absolute right-0 mt-2 w-56 rounded-xl bg-white shadow-soft border border-slate-200/60 overflow-hidden">
          <div class="px-4 py-3">
            <div class="text-sm font-medium truncate">{{ auth()->user()->name ?? 'Admin' }}</div>
            <div class="text-xs text-slate-500 truncate">{{ auth()->user()->email ?? '' }}</div>
          </div>
          <div class="py-1 text-sm">
            <div class="border-t border-slate-100 my-1"></div>
            <form method="POST" action="{{ route('admin.logout') }}">
              @csrf
              <button type="submit" class="w-full text-left px-4 py-2 hover:bg-slate-50">Keluar</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</header>
