{{-- resources/views/components/stat-card.blade.php --}}
@props([
  'title' => '',
  'value' => '0',
  'icon' => null,
  'href' => null, 
  'active' => false,  
])

@php
$card = "rounded-2xl bg-white/80 backdrop-blur border shadow-soft transition
         hover:shadow-glass";
if ($active) $card .= " ring-2 ring-brand-500";
@endphp

@if($href)
  <a href="{{ $href }}" class="{{ $card }}">
@else
  <div class="{{ $card }}">
@endif
    <div class="p-5 flex items-start gap-3">
      <div class="w-10 h-10 rounded-xl bg-brand-50 text-brand-600 flex items-center justify-center">
        {{ $icon }}
      </div>
      <div>
        <div class="text-sm text-slate-500">{{ $title }}</div>
        <div class="text-xl font-semibold mt-1">{{ number_format($value) }}</div>
      </div>
    </div>
@if($href)
  </a>
@else
  </div>
@endif
