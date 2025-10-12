@php
  $title = 'Masuk';
  $subtitle = 'Kelola produksi & persetujuan pelanggan dengan aman.';
@endphp

<x-layouts.auth :title="$title" :subtitle="$subtitle">
  <div x-data="{ showPass:false, submitting:false }">
    {{-- Flash status (Breeze pakai session "status") --}}
    @if (session('status'))
      <div class="mb-3 rounded-lg border border-emerald-200 bg-emerald-50 px-3 py-2 text-sm text-emerald-700">
        {{ session('status') }}
      </div>
    @endif

    {{-- Error validasi --}}
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

    <form method="POST" action="{{ route('login') }}" @submit="submitting = true" class="space-y-4">
      @csrf

      <div>
        <label for="email" class="block text-sm font-medium text-slate-700">Email</label>
        <div class="mt-1 relative">
          <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-width="2" d="M16 12H8m8 4H8m10-8H6a2 2 0 00-2 2v8a2 2 0 002 2h12a2 2 0 002-2V10a2 2 0 00-2-2z"/>
            </svg>
          </span>
          <input id="email" name="email" type="email" required
                 value="{{ old('email') }}" placeholder="email@domain.com"
                 class="w-full rounded-xl border border-slate-200 bg-white/70 pl-10 pr-3 py-2.5 outline-none transition focus:border-brand-500 focus:ring-2 focus:ring-brand-100"/>
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
          <input :type="showPass ? 'text' : 'password'" id="password" name="password" required
                 placeholder="••••••••"
                 class="w-full rounded-xl border border-slate-200 bg-white/70 pl-10 pr-10 py-2.5 outline-none transition focus:border-brand-500 focus:ring-2 focus:ring-brand-100"/>
          <button type="button" @click="showPass = !showPass"
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

        {{-- @if (Route::has('password.request'))
          <a class="text-sm text-brand-600 hover:text-brand-700" href="{{ route('password.request') }}">
            Lupa kata sandi?
          </a>
        @endif --}}
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

    <p class="mt-4 text-center text-sm text-slate-600">
      Belum punya akun?
      <a class="text-brand-600 hover:text-brand-700 font-medium" href="{{ route('register') }}">Daftar</a>
    </p>
  </div>
</x-layouts.auth>
