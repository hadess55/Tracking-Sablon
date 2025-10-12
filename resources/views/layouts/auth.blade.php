<!doctype html>
<html lang="id" class="h-full">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>{{ $title ?? 'Masuk' }} — Tracking Produksi</title>

  {{-- Tailwind CDN --}}
  <script src="https://cdn.tailwindcss.com"></script>
  {{-- Alpine --}}
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
              700: '#4338ca'
            }
          },
          boxShadow: {
            soft: '0 8px 30px rgba(2,6,23,.08)',
            glass: 'inset 0 1px 0 rgba(255,255,255,.35), 0 15px 45px rgba(2,6,23,.10)'
          },
          backgroundImage: {
            dots: 'radial-gradient(rgba(99,102,241,.12) 1px, transparent 1px)',
            diag: 'linear-gradient(135deg, rgba(99,102,241,.10), rgba(14,165,233,.10))'
          },
          backgroundSize: { dots: '18px 18px' }
        }
      }
    }
  </script>
</head>

<body class="h-full antialiased text-slate-800 bg-slate-50">
  <div class="fixed inset-0 -z-10 bg-dots"></div>

  <div class="min-h-full flex items-center justify-center px-4 py-10">
    <div class="w-full max-w-md">
      <div class="mb-6 text-center">
        <div class="mx-auto mb-3 flex h-12 w-12 items-center justify-center rounded-2xl bg-brand-100 text-brand-600 shadow-soft">
          {{-- Logo --}}
          <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor">
            <path stroke-linecap="round" stroke-width="2" d="M3 6h18M3 12h18M3 18h18"/>
          </svg>
        </div>
        <h1 class="text-xl font-semibold">{{ $title ?? 'Masuk' }}</h1>
        @isset($subtitle)
          <p class="mt-1 text-sm text-slate-500">{{ $subtitle }}</p>
        @endisset
      </div>

      <div class="rounded-2xl border border-white/60 bg-white/70 backdrop-blur shadow-glass">
        <div class="p-6">
          {{-- SLOT KONTEN --}}
          {{ $slot }}
        </div>
      </div>

      <p class="mt-6 text-center text-xs text-slate-500">
        © {{ date('Y') }} Tracking Produksi Sablon • by OlympusProject
      </p>
    </div>
  </div>
</body>
</html>
