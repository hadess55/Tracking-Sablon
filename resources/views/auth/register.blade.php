@php
  $title = 'Daftar';
  $subtitle = 'Buat akun untuk membuat pesanan & melacak progres.';
@endphp

<x-layouts.auth :title="$title" :subtitle="$subtitle">
  <div x-data="{ submitting:false, showPass:false, showPass2:false }">
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

    <form method="POST" action="{{ route('register') }}" @submit="submitting = true" class="space-y-5">
      @csrf

      {{-- Identitas --}}
      <div class="grid grid-cols-1 gap-4">
        <div>
          <label for="name" class="block text-sm font-medium text-slate-700">Nama Lengkap</label>
          <input id="name" name="name" type="text" required value="{{ old('name') }}"
                 class="mt-1 w-full rounded-xl border border-slate-200 bg-white/70 px-3 py-2.5 outline-none transition focus:border-brand-500 focus:ring-2 focus:ring-brand-100"/>
        </div>
        <div>
          <label for="email" class="block text-sm font-medium text-slate-700">Email</label>
          <input id="email" name="email" type="email" required value="{{ old('email') }}"
                 class="mt-1 w-full rounded-xl border border-slate-200 bg-white/70 px-3 py-2.5 outline-none transition focus:border-brand-500 focus:ring-2 focus:ring-brand-100"/>
        </div>
        <div>
          <label for="no_hp" class="block text-sm font-medium text-slate-700">No. HP</label>
          <input id="no_hp" name="no_hp" type="text" value="{{ old('no_hp') }}"
                 class="mt-1 w-full rounded-xl border border-slate-200 bg-white/70 px-3 py-2.5 outline-none transition focus:border-brand-500 focus:ring-2 focus:ring-brand-100"/>
        </div>
      </div>

      {{-- Alamat --}}
      <div>
        <div class="text-sm font-semibold text-slate-700">Alamat</div>
        <div class="mt-2 grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div class="sm:col-span-2">
            <label for="jalan" class="block text-sm text-slate-700">Jalan</label>
            <input id="jalan" name="jalan" type="text" value="{{ old('jalan') }}"
                   class="mt-1 w-full rounded-xl border border-slate-200 bg-white/70 px-3 py-2.5 outline-none transition focus:border-brand-500 focus:ring-2 focus:ring-brand-100"/>
          </div>
          <div>
            <label for="rt" class="block text-sm text-slate-700">RT</label>
            <input id="rt" name="rt" type="text" value="{{ old('rt') }}"
                   class="mt-1 w-full rounded-xl border border-slate-200 bg-white/70 px-3 py-2.5 outline-none transition focus:border-brand-500 focus:ring-2 focus:ring-brand-100"/>
          </div>
          <div>
            <label for="rw" class="block text-sm text-slate-700">RW</label>
            <input id="rw" name="rw" type="text" value="{{ old('rw') }}"
                   class="mt-1 w-full rounded-xl border border-slate-200 bg-white/70 px-3 py-2.5 outline-none transition focus:border-brand-500 focus:ring-2 focus:ring-brand-100"/>
          </div>
          <div>
            <label for="kelurahan" class="block text-sm text-slate-700">Kelurahan</label>
            <input id="kelurahan" name="kelurahan" type="text" value="{{ old('kelurahan') }}"
                   class="mt-1 w-full rounded-xl border border-slate-200 bg-white/70 px-3 py-2.5 outline-none transition focus:border-brand-500 focus:ring-2 focus:ring-brand-100"/>
          </div>
          <div>
            <label for="kecamatan" class="block text-sm text-slate-700">Kecamatan</label>
            <input id="kecamatan" name="kecamatan" type="text" value="{{ old('kecamatan') }}"
                   class="mt-1 w-full rounded-xl border border-slate-200 bg-white/70 px-3 py-2.5 outline-none transition focus:border-brand-500 focus:ring-2 focus:ring-brand-100"/>
          </div>
          <div>
            <label for="kota_kab" class="block text-sm text-slate-700">Kota/Kabupaten</label>
            <input id="kota_kab" name="kota_kab" type="text" value="{{ old('kota_kab') }}"
                   class="mt-1 w-full rounded-xl border border-slate-200 bg-white/70 px-3 py-2.5 outline-none transition focus:border-brand-500 focus:ring-2 focus:ring-brand-100"/>
          </div>
          <div>
            <label for="provinsi" class="block text-sm text-slate-700">Provinsi</label>
            <input id="provinsi" name="provinsi" type="text" value="{{ old('provinsi') }}"
                   class="mt-1 w-full rounded-xl border border-slate-200 bg-white/70 px-3 py-2.5 outline-none transition focus:border-brand-500 focus:ring-2 focus:ring-brand-100"/>
          </div>
          <div>
            <label for="kode_pos" class="block text-sm text-slate-700">Kode Pos</label>
            <input id="kode_pos" name="kode_pos" type="text" value="{{ old('kode_pos') }}"
                   class="mt-1 w-full rounded-xl border border-slate-200 bg-white/70 px-3 py-2.5 outline-none transition focus:border-brand-500 focus:ring-2 focus:ring-brand-100"/>
          </div>
        </div>
      </div>

      {{-- Password --}}
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
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
  </div>
</x-layouts.auth>
