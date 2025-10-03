<!doctype html>
<html lang="id" class="h-full">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Login Admin — Tracking Produksi</title>

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
              700: '#4338ca'
            }
          },
          boxShadow: {
            soft: '0 8px 30px rgba(2,6,23,.08)',
            glass: 'inset 0 1px 0 rgba(255,255,255,.35), 0 15px 45px rgba(2,6,23,.10)'
          },
          backgroundImage: {
            'dots': 'radial-gradient(rgba(99,102,241,.15) 1px, transparent 1px)',
            'diag': 'linear-gradient(135deg, rgba(99,102,241,.10), rgba(14,165,233,.10))'
          },
          backgroundSize: { 'dots': '18px 18px' }
        }
      }
    }
  </script>
</head>

<body class="h-full antialiased text-slate-800 bg-slate-50">

  <div class="fixed inset-0 -z-10 bg-dots bg-dots"></div>
  {{-- <div class="fixed inset-x-0 -top-24 -z-10 h-56 bg-diag blur-md"></div> --}}

  <div class="min-h-full flex items-center justify-center px-4 py-10">
    <div class="w-full max-w-md">

      <div class="mb-6 text-center">
        <div class="mx-auto mb-3 flex h-12 w-12 items-center justify-center rounded-2xl bg-brand-100 text-brand-600 shadow-soft">
          {{-- logo --}}
          <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor">
            <path stroke-linecap="round" stroke-width="2" d="M3 6h18M3 12h18M3 18h18"/>
          </svg>
        </div>
        <h1 class="text-xl font-semibold">Login Admin</h1>
        <p class="mt-1 text-sm text-slate-500">
          Kelola produksi & persetujuan pelanggan dengan aman.
        </p>
      </div>

      <div class="rounded-2xl border border-white/60 bg-white/70 backdrop-blur shadow-glass">
        <div class="p-6" x-data="{ showPass:false, submitting:false }">
          {{-- Flash status / error --}}
          @if (session('status'))
            <div class="mb-3 rounded-lg border border-emerald-200 bg-emerald-50 px-3 py-2 text-sm text-emerald-700">
              {{ session('status') }}
            </div>
          @endif

          @if ($errors->any())
            <div class="mb-3 rounded-lg border border-rose-200 bg-rose-50 px-3 py-2 text-sm text-rose-700">
              <b>Terjadi kesalahan:</b>
              <ul class="mt-1 list-disc pl-5">
                @foreach ($errors->all() as $e)
                  <li>{{ $e }}</li>
                @endforeach
              </ul>
            </div>
          @endif

          <form
            method="POST"
            action="{{ route('admin.login.submit') }}"
            @submit="submitting = true"
            class="space-y-4"
          >
            @csrf


            <div>
              <label for="email" class="block text-sm font-medium text-slate-700">Email</label>
              <div class="mt-1 relative">
                <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                  <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-width="2" d="M16 12H8m8 4H8m10-8H6a2 2 0 00-2 2v8a2 2 0 002 2h12a2 2 0 002-2V10a2 2 0 00-2-2z"/>
                  </svg>
                </span>
                <input
                  id="email" name="email" type="email" required
                  value="{{ old('email') }}"
                  placeholder="email"
                  class="w-full rounded-xl border border-slate-200 bg-white/70 pl-10 pr-3 py-2.5 outline-none transition focus:border-brand-500 focus:ring-2 focus:ring-brand-100"
                />
              </div>
            </div>

            <div>
              <label for="password" class="block text-sm font-medium text-slate-700">Kata Sandi</label>
              <div class="mt-1 relative">
                <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                  <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-width="2" d="M12 11c1.657 0 3-1.343 3-3S13.657 5 12 5s-3 1.343-3 3 1.343 3 3 3z"/>
                    <path stroke-linecap="round" stroke-width="2" d="M19.4 15a8 8 0 10-14.8 0"/>
                  </svg>
                </span>
                <input
                  :type="showPass ? 'text' : 'password'"
                  id="password" name="password" required
                  placeholder="••••••••"
                  class="w-full rounded-xl border border-slate-200 bg-white/70 pl-10 pr-10 py-2.5 outline-none transition focus:border-brand-500 focus:ring-2 focus:ring-brand-100"
                />
                <button type="button"
                  @click="showPass = !showPass"
                  class="absolute inset-y-0 right-0 flex items-center pr-3 text-slate-400 hover:text-slate-600">
                  <svg x-show="!showPass" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    <path stroke-linecap="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                  </svg>
                  <svg x-show="showPass" x-cloak class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.477 0-8.268-2.943-9.542-7a10.01 10.01 0 012.676-4.204M9.88 9.88A3 3 0 0114.12 14.12M3 3l18 18"/>
                  </svg>
                </button>
              </div>
            </div>

            <div class="flex items-center justify-between">
              <label class="inline-flex items-center gap-2 text-sm text-slate-600">
                <input type="checkbox" name="remember" class="rounded border-slate-300 text-brand-600 focus:ring-brand-500">
                Ingat saya
              </label>
            </div>

            <div class="flex items-center gap-3 pt-1">
              <a href="{{ url('/') }}"
                 class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white/70 px-4 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-50">
                Kembali
              </a>
              <button type="submit"
                :class="submitting ? 'opacity-60 cursor-not-allowed' : ''"
                class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-brand-600 px-4 py-2.5 text-sm font-semibold text-white shadow-soft hover:bg-brand-700 focus:outline-none focus:ring-2 focus:ring-brand-200">
                <svg x-show="submitting" class="h-4 w-4 animate-spin" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                  <circle class="opacity-30" cx="12" cy="12" r="10" stroke-width="4"></circle>
                  <path class="opacity-90" stroke-width="4" d="M4 12a8 8 0 018-8"/>
                </svg>
                <span>Masuk</span>
              </button>
            </div>
          </form>
        </div>
      </div>

      <p class="mt-6 text-center text-xs text-slate-500">
        © {{ date('Y') }} Tracking Produksi Sablon • by OlympusProject
      </p>
    </div>
  </div>
</body>
</html>
