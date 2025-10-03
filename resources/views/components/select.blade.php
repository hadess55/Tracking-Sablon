@props(['label'=>null,'name'=>null,'required'=>false])
<label class="block">
  @if($label)<span class="block text-sm font-medium text-gray-700 mb-1">{{ $label }}</span>@endif
  <select name="{{ $name }}" @if($required) required @endif
          {{ $attributes->merge(['class'=>'w-full rounded-xl border-gray-300 focus:border-brand-600 focus:ring focus:ring-brand-600/20']) }}>
    {{ $slot }}
  </select>
</label>
