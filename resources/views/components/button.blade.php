@props(['variant'=>'primary','as'=>'button','href'=>null,'type'=>'button'])
@php
  $base = 'inline-flex items-center justify-center rounded-xl px-4 py-2.5 text-sm font-medium transition';
  $styles = [
    'primary' => 'bg-brand-600 text-white hover:bg-brand-700 focus:ring focus:ring-brand-600/30',
    'secondary' => 'bg-white border border-gray-300 text-gray-700 hover:bg-gray-50',
    'danger' => 'bg-red-600 text-white hover:bg-red-700 focus:ring focus:ring-red-600/30',
  ][$variant] ?? '';
@endphp

@if($as === 'a')
  <a href="{{ $href }}" {{ $attributes->merge(['class'=>"$base $styles"]) }}>{{ $slot }}</a>
@else
  <button type="{{ $type }}" {{ $attributes->merge(['class'=>"$base $styles"]) }}>{{ $slot }}</button>
@endif
