@props(['as' => 'div'])
<{{ $as }} {{ $attributes->merge(['class' => 'rounded-2xl bg-white shadow-soft border border-gray-100']) }}>
  {{ $slot }}
</{{ $as }}>
