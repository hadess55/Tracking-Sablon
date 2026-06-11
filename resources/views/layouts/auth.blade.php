<!doctype html>
<html lang="id" class="h-full">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>{{ $title ?? 'Masuk' }} — Tracking Produksi</title>

  <link rel="icon" type="image/png" href="{{ asset('Favicon/fa    vicon-96x96.png') }}" sizes="96x96" />
        <link rel="icon" type="image/svg+xml" href="{{ asset('Favicon/favicon.svg') }}" />
        <link rel="shortcut icon" href="{{ asset('Favicon/favicon.ico') }}" />
        <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('Favicon/apple-touch-icon.png') }}" />
        <link rel="manifest" href="{{ asset('Favicon/site.webmanifest') }}" />

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
        <div class="mx-auto mb-3 flex h-12 w-12 items-center justify-center rounded-2xl bg-white/70 text-brand-600 shadow-soft">
          {{-- Logo --}}
          <img src="{{ asset('Favicon/favicon-96x96.png') }}" alt="Logo" >
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
        © {{ date('Y') }} Tracking Produksi Sablon 
      </p>
    </div>
  </div>
</body>
</html>
