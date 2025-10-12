@php
  $title = 'Daftar';
  $subtitle = 'Buat akun untuk membuat pesanan & melacak progres.';
@endphp

<x-layouts.auth :title="$title" :subtitle="$subtitle">
  <div x-data="{ showPass:false, showPass2:false, submitting:false }">
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

    <form method="POST" action="{{ route('register') }}" @submit="submitting = true" class="space-y-4">
      @csrf

      <div>
        <label for="name" class="block text-sm font-medium text-slate-700">Nama</label>
        <input id="name" name="name" type="text" required value="{{ old('name') }}"
               class="mt-1 w-full rounded-xl border border-slate-200 bg-white/70 px-3 py-2.5 outline-none transition focus:border-brand-500 focus:ring-2 focus:ring-brand-100"/>
      </div>

      <div>
        <label for="email" class="block text-sm font-medium text-slate-700">Email</label>
        <input id="email" name="email" type="email" required value="{{ old('email') }}"
               class="mt-1 w-full rounded-xl border border-slate-200 bg-white/70 px-3 py-2.5 outline-none transition focus:border-brand-500 focus:ring-2 focus:ring-brand-100"/>
      </div>

      <div>
        <label for="password" class="block text-sm font-medium text-slate-700">Kata Sandi</label>
        <div class="mt-1 relative">
          <input :type="showPass ? 'text' : 'password'" id="password" name="password" required
                 class="w-full rounded-xl border border-slate-200 bg-white/70 pr-10 px-3 py-2.5 outline-none transition focus:border-brand-500 focus:ring-2 focus:ring-brand-100"/>
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

      <div>
        <label for="password_confirmation" class="block text-sm font-medium text-slate-700">Ulangi Kata Sandi</label>
        <div class="mt-1 relative">
          <input :type="showPass2 ? 'text' : 'password'" id="password_confirmation" name="password_confirmation" required
                 class="w-full rounded-xl border border-slate-200 bg-white/70 pr-10 px-3 py-2.5 outline-none transition focus:border-brand-500 focus:ring-2 focus:ring-brand-100"/>
          <button type="button" @click="showPass2 = !showPass2"
                  class="absolute inset-y-0 right-0 flex items-center pr-3 text-slate-400 hover:text-slate-600">
            <svg x-show="!showPass2" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
              <path stroke-linecap="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
            </svg>
            <svg x-show="showPass2" x-cloak class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.477 0-8.268-2.943-9.542-7a10.01 10.01 0 012.676-4.204M9.88 9.88A3 3 0 0114.12 14.12M3 3l18 18"/>
            </svg>
          </button>
        </div>
      </div>

      <div class="flex items-center gap-3 pt-1">
        <a href="{{ route('login') }}"
           class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white/70 px-4 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-50">
          Sudah punya akun?
        </a>
        <button type="submit"
                :class="submitting ? 'opacity-60 cursor-not-allowed' : ''"
                class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-brand-600 px-4 py-2.5 text-sm font-semibold text-white shadow-soft hover:bg-brand-700 focus:outline-none focus:ring-2 focus:ring-brand-200">
          <svg x-show="submitting" class="h-4 w-4 animate-spin" viewBox="0 0 24 24" fill="none" stroke="currentColor">
            <circle class="opacity-30" cx="12" cy="12" r="10" stroke-width="4"></circle>
            <path class="opacity-90" stroke-width="4" d="M4 12a8 8 0 018-8"/>
          </svg>
          <span>Daftar</span>
        </button>
      </div>
    </form>

    <p class="mt-4 text-center text-sm text-slate-600">
      Dengan mendaftar, kamu menyetujui ketentuan & kebijakan privasi kami.
    </p>
  </div>
</x-layouts.auth>
