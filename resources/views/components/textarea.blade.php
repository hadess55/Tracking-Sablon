@props([
  'label' => null,
  'name' => null,
  'error' => null,
  'value' => null,
])

<label class="block text-sm font-medium text-slate-700">
  {{ $label }}
  <textarea
    {{ $attributes->merge([
      'class' => 'mt-1 w-full rounded-xl border-slate-200 bg-white px-3 py-2.5 text-sm placeholder-slate-400 shadow-sm focus:border-indigo-400 focus:ring-2 focus:ring-indigo-200'
        . ($error ? ' border-rose-400 focus:border-rose-400 focus:ring-rose-200' : '')
    ]) }}
    name="{{ $name }}"
  >{{ old($name, $value) }}</textarea>
</label>

@isset($error)
  <p class="mt-1 text-sm text-rose-600">{{ $error }}</p>
@endisset
