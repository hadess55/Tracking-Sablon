@props([
    'title',
    'desc'   => null,
    'back'   => null,     
    'icon'   => null, 
    'align'  => 'center',  
])

@php
    $isCenter = $align === 'center';
@endphp

<div {{ $attributes->merge(['class' => 'mb-8 animate-fade rounded-2xl bg-white shadow-soft border border-gray-100 p-5']) }}>
  <div class="flex items-start justify-between gap-4 {{ $isCenter ? 'flex-col items-center text-center' : '' }}">
    <div class="{{ $isCenter ? 'w-full' : 'min-w-0' }}">

      @if($back)
        <a href="{{ $back }}"
           class="mb-2 inline-flex items-center text-sm text-slate-500 hover:text-slate-700">
          <svg class="mr-1.5 h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M15 19l-7-7 7-7"></path>
          </svg>
          Kembali
        </a>
      @endif

      <div class="flex items-center gap-3 {{ $isCenter ? 'justify-center' : '' }}">
        @if($icon)
          <span class="inline-flex h-10 w-10 items-center justify-center
                       rounded-xl bg-indigo-50 text-indigo-600 ring-1 ring-indigo-100">
            {!! $icon !!}
          </span>
        @endif

        <h1 class="text-2xl sm:text-3xl font-semibold tracking-tight">
          <span class="bg-gradient-to-r from-slate-900 to-slate-700 bg-clip-text text-transparent">
            {{ $title }}
          </span>
        </h1>
      </div>

      @if($desc)
        <p class="mt-2 max-w-8xl text-sm sm:text-base text-slate-600">{{ $desc }}</p>
      @endif
    </div>


    @isset($actions)
      <div class="{{ $isCenter ? 'mt-3' : 'shrink-0' }}">
        {{ $actions }}
      </div>
    @endisset
  </div>

  <div class="mt-4 h-px w-full bg-gradient-to-r from-transparent via-slate-400/80 to-transparent"></div>
</div>
