<!doctype html>
<html lang="id" class="h-full">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>{{ $title ?? 'Panel Admin' }}</title>

  <script src="https://cdn.tailwindcss.com"></script>
  <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors:{ brand:{50:'#eef2ff',600:'#4f46e5',700:'#4338ca'}, base:{50:'#f8fafc'} },
          boxShadow:{ soft:'0 8px 30px rgba(0,0,0,.06)', glass:'inset 0 1px 0 rgba(255,255,255,.25), 0 10px 30px rgba(2,6,23,.08)'},
          backgroundImage:{ grid:'radial-gradient(rgba(99,102,241,.12) 1px, transparent 1px)'},
          backgroundSize:{ grid:'16px 16px' },
        }
      }
    }
  </script>

  <style>[x-cloak]{ display:none !important; }</style>
</head>
<body class="h-full bg-base-50 text-slate-800 antialiased"
      x-data="{
        openSidebar: true,  // collapse (desktop)
        drawer: false,      // drawer (mobile)
        init(){
          const saved = localStorage.getItem('admin_sidebar_open');
          if(saved !== null) this.openSidebar = JSON.parse(saved);
          this.$watch('openSidebar', v => localStorage.setItem('admin_sidebar_open', JSON.stringify(v)));
        }
      }">

  <div class="fixed inset-0 -z-10 bg-grid"></div>
  <div class="fixed inset-x-0 -top-24 -z-10 h-52 bg-gradient-to-b from-brand-50/80 to-transparent"></div>

  {{-- Sidebar desktop (width mengikuti state) --}}
  <aside class="hidden lg:block fixed inset-y-0 z-40 transition-[width] duration-300"
         :class="openSidebar ? 'w-72' : 'w-20'">
    @include('partials.admin-sidebar')
  </aside>

  {{-- Drawer mobile --}}
  <div x-show="drawer" x-transition.opacity class="lg:hidden fixed inset-0 z-40 bg-black/30" @click="drawer=false"></div>
  <aside x-show="drawer" x-transition class="lg:hidden fixed inset-y-0 left-0 z-50 w-72">
    @include('partials.admin-sidebar')
  </aside>

  {{-- Wrapper: padding-kiri menyesuaikan lebar sidebar --}}
  <div class="min-h-full transition-[padding] duration-300"
       :class="{'lg:pl-72': openSidebar, 'lg:pl-20': !openSidebar}">

    {{-- NAVBAR ATAS (tanpa tombol collapse) --}}
    @php $adminTitle = trim($__env->yieldContent('header')) ?: ($header ?? 'Dashboard'); @endphp
    <header class="sticky top-0 z-30 bg-white/80 backdrop-blur border-b border-slate-200/70">
      <div class="max-w-7xl mx-auto px-4 h-14 flex items-center justify-between">
        <div class="flex items-center gap-2 min-w-0">
          <button class="lg:hidden inline-flex items-center justify-center w-10 h-10 rounded-md hover:bg-slate-100"
                  @click="drawer=true" aria-label="Buka menu">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
          </button>
          <h1 class="font-semibold truncate">{{ $adminTitle }}</h1>
        </div>

        {{-- <div class="hidden md:block flex-1 max-w-xl mx-4">
          <label class="relative block">
            <span class="absolute inset-y-0 left-3 flex items-center">
              <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-width="2" d="M21 21l-4.35-4.35M11 18a7 7 0 1 1 0-14 7 7 0 0 1 0 14z"/>
              </svg>
            </span>
            <input class="w-full pl-9 pr-3 h-10 rounded-xl bg-white/70 border border-white/60 shadow-soft focus:outline-none focus:ring-2 focus:ring-brand-600/30"
                   placeholder="Cari nomor produksi / pelanggan..." autocomplete="off">
          </label>
        </div> --}}

        <div class="flex items-center gap-2">
          {{-- <button class="inline-flex items-center justify-center w-10 h-10 rounded-xl hover:bg-slate-100" aria-label="Notifikasi">
            <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-width="2" d="M15 17h5l-1.4-1.4A2 2 0 0 1 18 14v-3a6 6 0 1 0-12 0v3a2 2 0 0 1-.6 1.4L4 17h5m6 0v1a3 3 0 1 1-6 0v-1"/></svg>
          </button> --}}
          {{-- Bell Notifikasi Pelanggan Menunggu --}}
        <div
          x-data="{
            open:false,
            count: 0,
            items: [],
            async refresh() {
              try {
                const res = await fetch('{{ route('admin.notif.customers') }}', {
                  headers: { 'X-Requested-With': 'XMLHttpRequest' }
                });
                if (!res.ok) return;
                const data = await res.json();
                this.count = data.count;
                this.items = data.items;
              } catch (_) {}
            }
          }"
          x-init="
            refresh();
            setInterval(() => refresh(), 15000); // polling tiap 15 detik
          "
          class="relative"
        >
          <button @click="open = !open"
                  class="relative inline-flex items-center justify-center w-9 h-9 rounded-full hover:bg-slate-100 focus:outline-none">

            <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-width="2"
                    d="M15 17h5l-1.4-1.4A2 2 0 0118 14.172V11a6 6 0 10-12 0v3.172a2 2 0 01-.6 1.428L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
            </svg>


            <span x-show="count > 0"
                  x-transition
                  class="absolute -top-1 -right-1 min-w-[18px] h-[18px] px-1 rounded-full bg-rose-500 text-white text-[10px] leading-[18px] text-center font-semibold">
              <span x-text="count"></span>
            </span>
          </button>


          <div x-show="open"
              x-transition
              @click.outside="open = false"
              class="absolute right-0 mt-2 w-80 rounded-xl border border-slate-200 bg-white shadow-xl overflow-hidden z-50">
            <div class="px-3 py-2 border-b bg-slate-50">
              <div class="text-sm font-semibold">Notifikasi</div>
              <div class="text-xs text-slate-500">Pelanggan menunggu persetujuan</div>
            </div>

            <template x-if="count === 0">
              <div class="p-4 text-sm text-slate-500">Tidak ada permintaan baru.</div>
            </template>

            <template x-for="c in items" :key="c.id">
              <a :href="c.url" class="flex items-start justify-between gap-3 px-3 py-2 hover:bg-slate-50">
                <div class="min-w-0">
                  <div class="text-sm font-medium truncate" x-text="c.nama"></div>
                  <div class="text-xs text-slate-500 truncate" x-text="c.email"></div>
                </div>
                <span class="text-[11px] text-slate-400 shrink-0" x-text="c.since"></span>
              </a>
            </template>

            <div class="border-t"></div>
            <a href="{{ route('admin.customers.index', ['status' => 'menunggu']) }}"
              class="block px-3 py-2 text-sm text-brand-600 hover:text-brand-700">
              Lihat semua yang menunggu
            </a>
          </div>
        </div>

          <div class="relative" x-data="{open:false}">
            <button @click="open=!open" class="inline-flex items-center gap-2 h-10 px-2 rounded-xl hover:bg-slate-100">
              <div class="w-7 h-7 rounded-full bg-brand-600 text-white grid place-items-center">{{ strtoupper(substr(auth()->user()->name ?? 'A',0,1)) }}</div>
              <span class="hidden sm:block text-sm text-slate-700 max-w-[10rem] truncate">{{ auth()->user()->name ?? 'Admin' }}</span>
            </button>
            <div x-show="open" x-transition @click.outside="open=false"
                 class="absolute right-0 mt-2 w-56 rounded-xl bg-white shadow-soft border border-slate-200/60 overflow-hidden">
              <div class="px-4 py-3">
                <div class="text-sm font-medium truncate">{{ auth()->user()->name ?? 'Admin' }}</div>
                <div class="text-xs text-slate-500 truncate">{{ auth()->user()->email ?? '' }}</div>
              </div>
              <div class="py-1 text-sm">
                <a href="{{ route('admin.produksi.index') }}" class="block px-4 py-2 hover:bg-slate-50">Dashboard</a>
                <div class="border-t border-slate-100 my-1"></div>
                <form method="POST" action="{{ route('admin.logout') }}">@csrf
                  <button type="submit" class="w-full text-left px-4 py-2 hover:bg-slate-50">Keluar</button>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </header>

    <main class="max-w-7xl mx-auto px-4 pb-8 pt-5">
      @yield('content')
    </main>
  </div>
  <script>
document.addEventListener('alpine:init', () => {
  Alpine.data('combobox', ({ options, initial }) => ({
    options,
    open: false,
    value: initial || '',
    query: initial || '',
    results: [],
    activeIndex: 0,

    init() {
      this.filter();
      document.addEventListener('click', (e) => {
        if (!this.$el.contains(e.target)) this.open = false;
      });
    },
    filter() {
      const q = (this.query || '').toLowerCase();
      this.results = q
        ? this.options.filter(o => o.toLowerCase().includes(q))
        : this.options;
      this.activeIndex = 0;
      this.open = true;
    },
    move(dir) {
      if (!this.open) { this.open = true; return; }
      const len = this.results.length;
      if (!len) return;
      this.activeIndex = (this.activeIndex + dir + len) % len;
    },
    choose(i) {
      if (this.results[i]) {
        this.value = this.results[i];
        this.query = this.results[i];
      } else {
        // tidak ada hasil, pakai teks apa adanya
        this.value = this.query;
      }
      this.open = false;
    },
  }));
});
</script>

</body>
</html>
