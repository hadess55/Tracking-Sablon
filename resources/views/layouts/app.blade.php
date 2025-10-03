<!doctype html>
<html lang="id" class="h-full scroll-smooth">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>{{ $title ?? 'Tracking Produksi Sablon' }}</title>

  <script src="https://cdn.tailwindcss.com"></script>

  <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            brand: {
              50: '#eef2ff',
              100: '#e0e7ff',
              500: '#6366f1',
              600: '#4f46e5',
              700: '#4338ca',
            }
          },
          boxShadow: {
            soft: '0 8px 30px rgba(0,0,0,.06)',
          },
          keyframes: {
            fadeIn: { '0%': { opacity: 0 }, '100%': { opacity: 1 } },
            slideUp: { '0%': { transform: 'translateY(8px)', opacity: .0 }, '100%': { transform: 'translateY(0)', opacity: 1 } },
            pulseOnce: { '0%,100%': { transform:'scale(1)' }, '50%': { transform:'scale(1.03)' } },
          },
          animation: {
            'fade-in': 'fadeIn .4s ease-out both',
            'slide-up': 'slideUp .45s cubic-bezier(.22,1,.36,1) both',
            'pulse-once': 'pulseOnce .3s ease-out 1',
          }
        }
      }
    }
  </script>
</head>
<body class="min-h-full bg-gray-50 text-gray-800 antialiased">
  {{-- Navbar --}}
  <header x-data="{ open:false }" class="sticky top-0 z-40 bg-white/80 backdrop-blur border-b border-gray-200">
  <div class="max-w-6xl mx-auto px-4">
    <div class="flex items-center justify-between h-14">
      {{-- Logo --}}
      <a href="{{ url('/') }}" class="inline-flex items-center gap-2 font-semibold text-gray-900">
        <span class="inline-block w-2.5 h-2.5 rounded-full bg-brand-600 animate-pulse"></span>
        Tracking Produksi
      </a>

      {{-- Tombol Hamburger (mobile) --}}
      <button @click="open=!open" class="sm:hidden inline-flex items-center justify-center w-10 h-10 rounded-md hover:bg-gray-100 focus:outline-none">
        <svg x-show="!open" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
        </svg>
        <svg x-show="open" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
        </svg>
      </button>

      {{-- Menu Desktop --}}
      <nav class="hidden sm:flex items-center gap-6 text-sm">
        <a href="{{ route('tracking.form') }}" class="hover:text-brand-600 transition">Lacak</a>
        <a href="{{ route('customers.registrasi.form') }}" class="hover:text-brand-600 transition">Daftar Pelanggan</a>
        <a href="{{ url('/admin/login') }}" class="hover:text-brand-600 transition">Admin</a>
      </nav>
    </div>
  </div>

  {{-- Menu Mobile --}}
  <div x-show="open" x-transition class="sm:hidden border-t border-gray-200 bg-white shadow-inner">
    <nav class="px-4 py-3 space-y-2">
      <a href="{{ route('tracking.form') }}" class="block px-2 py-2 rounded-md hover:bg-gray-50">Lacak</a>
      <a href="{{ route('customers.registrasi.form') }}" class="block px-2 py-2 rounded-md hover:bg-gray-50">Daftar Pelanggan</a>
      <a href="{{ url('/admin/login') }}" class="block px-2 py-2 rounded-md hover:bg-gray-50">Admin</a>
    </nav>
  </div>
</header>


  {{-- Flash / Error --}}
  <div class="max-w-6xl mx-auto px-4 mt-4 space-y-3">
    @if(session('sukses'))
      <div x-data="{show:true}" x-show="show" x-transition
           class="rounded-lg border border-green-200 bg-green-50 text-green-700 px-4 py-3 text-sm flex items-start justify-between">
        <div class="font-medium">{{ session('sukses') }}</div>
        <button class="ml-3 text-green-700/70 hover:text-green-800" @click="show=false">✕</button>
      </div>
    @endif

    @if($errors->any())
      <div class="rounded-lg border border-red-200 bg-red-50 text-red-700 px-4 py-3 text-sm">
        <div class="font-semibold mb-1">Terjadi kesalahan:</div>
        <ul class="list-disc pl-5 space-y-0.5">
          @foreach($errors->all() as $e) <li>{{ $e }}</li> @endforeach
        </ul>
      </div>
    @endif
  </div>

  {{-- Konten --}}
  <main class="max-w-6xl mx-auto px-4 py-8">
    @yield('content')
  </main>

  {{-- Footer --}}
  <footer class="mt-10 py-8 text-center text-xs text-gray-500">
    © {{ date('Y') }} Tracking Produksi Sablon • by OlympusProject
  </footer>
</body>
</html>
