@php
    $user       = auth()->user();
    $isAdmin    = $user && ($user->role === 'admin');
    $isCustomer = $user && ($user->role === 'customer');

    $routePesanCreate = \Illuminate\Support\Facades\Route::has('customer.pesanan.create')
        ? route('customer.pesanan.create')
        : (\Illuminate\Support\Facades\Route::has('pesanan.buat') ? route('pesanan.buat') : url('/pesanan/buat'));

    $routePesanIndex = \Illuminate\Support\Facades\Route::has('customer.pesanan.index')
        ? route('customer.pesanan.index')
        : (\Illuminate\Support\Facades\Route::has('pesanan.index') ? route('pesanan.index') : url('/pesanan'));
@endphp

<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex items-center h-16">
      {{-- LEFT: Logo unik --}}
      <a href="{{ url('/') }}" class="flex items-center gap-2">
        {{-- Ikon kaos (SVG custom) --}}
        <span class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600">
          <svg viewBox="0 0 24 24" class="h-6 w-6" fill="none" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7"
              d="M6 4l3-2h6l3 2 3 2-3 4v8a2 2 0 01-2 2h-8a2 2 0 01-2-2V10L3 6l3-2z"/>
          </svg>
        </span>
        <span class="font-semibold tracking-tight text-slate-800">Tracking Produksi</span>
      </a>

      {{-- Spacer untuk dorong menu ke kanan --}}
      <div class="flex-1"></div>

      {{-- RIGHT: Desktop links + user dropdown --}}
      <div class="hidden sm:flex items-center gap-6">
        @guest
          <x-nav-link :href="url('/')" :active="request()->is('/')">
            {{ __('Home') }}
          </x-nav-link>
          <x-nav-link :href="route('login')" :active="request()->routeIs('login')">
            {{ __('Login') }}
          </x-nav-link>
          @if (Route::has('register'))
            <x-nav-link :href="route('register')" :active="request()->routeIs('register')">
              {{ __('Register') }}
            </x-nav-link>
          @endif
        @else
          @if ($isAdmin)
            <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
              {{ __('Dashboard') }}
            </x-nav-link>
          @elseif ($isCustomer)
            <x-nav-link :href="url('/')" :active="request()->is('/')">
              {{ __('Home') }}
            </x-nav-link>
            <x-nav-link :href="$routePesanIndex"
                        :active="request()->routeIs('customer.pesanan.*') || request()->routeIs('pesanan.index')">
              {{ __('Pesanan Saya') }}
            </x-nav-link>
            <x-nav-link :href="$routePesanCreate"
                        :active="request()->routeIs('customer.pesanan.create') || request()->routeIs('pesanan.buat')">
              {{ __('Pesan') }}
            </x-nav-link>
          @endif

          {{-- User dropdown --}}
          <x-dropdown align="right" width="48">
            <x-slot name="trigger">
              <button class="inline-flex items-center gap-2 px-3 py-2 text-sm font-medium rounded-md border border-transparent text-gray-600 hover:text-gray-800 transition">
                <div>{{ $user->name ?? 'User' }}</div>
                <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.186l3.71-3.955a.75.75 0 111.08 1.04l-4.243 4.52a.75.75 0 01-1.08 0L5.25 8.27a.75.75 0 01-.02-1.06z" clip-rule="evenodd"/>
                </svg>
              </button>
            </x-slot>
            <x-slot name="content">
              <form method="POST" action="{{ route('logout') }}">
                @csrf
                <x-dropdown-link :href="route('logout')"
                                 onclick="event.preventDefault(); this.closest('form').submit();">
                  {{ __('Log Out') }}
                </x-dropdown-link>
              </form>
            </x-slot>
          </x-dropdown>
        @endguest
      </div>

      {{-- Hamburger (mobile) --}}
      <div class="sm:hidden ml-2">
        <button @click="open = !open"
                class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-600 hover:bg-gray-100 focus:outline-none transition">
          <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
            <path :class="{'hidden': open, 'inline-flex': ! open}" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M4 6h16M4 12h16M4 18h16"/>
            <path :class="{'hidden': ! open, 'inline-flex': open}" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M6 18L18 6M6 6l12 12"/>
          </svg>
        </button>
      </div>
    </div>
  </div>

  {{-- Mobile menu --}}
  <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
    <div class="pt-2 pb-3 space-y-1 border-t">
      @guest
        <x-responsive-nav-link :href="url('/')" :active="request()->is('/')">
          {{ __('Home') }}
        </x-responsive-nav-link>
        <x-responsive-nav-link :href="route('login')" :active="request()->routeIs('login')">
          {{ __('Login') }}
        </x-responsive-nav-link>
        @if (Route::has('register'))
          <x-responsive-nav-link :href="route('register')" :active="request()->routeIs('register')">
            {{ __('Register') }}
          </x-responsive-nav-link>
        @endif
      @else
        @if ($isAdmin)
          <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
            {{ __('Dashboard') }}
          </x-responsive-nav-link>
        @elseif ($isCustomer)
          <x-responsive-nav-link :href="url('/')" :active="request()->is('/')">
            {{ __('Home') }}
          </x-responsive-nav-link>
          <x-responsive-nav-link :href="$routePesanIndex"
                                 :active="request()->routeIs('customer.pesanan.*') || request()->routeIs('pesanan.index')">
            {{ __('Pesanan Saya') }}
          </x-responsive-nav-link>
          <x-responsive-nav-link :href="$routePesanCreate"
                                 :active="request()->routeIs('customer.pesanan.create') || request()->routeIs('pesanan.buat')">
            {{ __('Pesan') }}
          </x-responsive-nav-link>
        @endif

        <div class="pt-2 pb-1 border-t">
          <div class="px-4">
            <div class="font-medium text-base text-gray-800">{{ $user->name }}</div>
            <div class="font-medium text-sm text-gray-500">{{ $user->email }}</div>
          </div>
          <div class="mt-3 space-y-1">
            <form method="POST" action="{{ route('logout') }}">
              @csrf
              <x-responsive-nav-link :href="route('logout')"
                                     onclick="event.preventDefault(); this.closest('form').submit();">
                {{ __('Log Out') }}
              </x-responsive-nav-link>
            </form>
          </div>
        </div>
      @endguest
    </div>
  </div>
</nav>
