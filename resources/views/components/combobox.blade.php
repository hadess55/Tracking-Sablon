@props([
  'name',                         // name untuk field yang dikirim ke server
  'value' => '',                  // nilai awal
  'placeholder' => '',
  'options' => [],                // array string saran
])

<div x-data="combobox({ options: @js(array_values($options)), initial: @js($value) })" x-init="init()" class="relative">
  {{-- nilai yang benar-benar dikirim ke server --}}
  <input type="hidden" name="{{ $name }}" :value="value">

  {{-- input tampilan/search --}}
  <div class="relative">
    <input
      type="text"
      x-model="query"
      @focus="open = true; filter()"
      @input="filter()"
      @keydown.arrow-down.prevent="move(1)"
      @keydown.arrow-up.prevent="move(-1)"
      @keydown.enter.prevent="choose(activeIndex)"
      @keydown.escape="open = false"
      @blur="value = query"  {{-- kalau diketik manual & langsung pindah fokus --}}
      class="w-full h-10 rounded-xl bg-white/80 border border-slate-300 outline outline-1 outline-slate-300
             focus:outline-2 focus:outline-brand-600/60 focus:border-transparent px-3 pr-10 transition"
      placeholder="{{ $placeholder }}"
    />

    <button type="button" class="absolute inset-y-0 right-0 px-3 text-slate-500" @click="open = !open">
      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
      </svg>
    </button>
  </div>

  {{-- dropdown --}}
  <template x-if="open">
    <ul x-transition
        class="absolute z-50 mt-1 w-full max-h-60 overflow-auto rounded-xl border border-slate-200 bg-white shadow-xl ring-1 ring-black/5">
      <template x-for="(opt, i) in results" :key="i">
        <li
          @mousedown.prevent="choose(i)"
          @mousemove="activeIndex = i"
          :class="i === activeIndex ? 'bg-brand-50 text-brand-700' : 'text-slate-700'"
          class="px-3 py-2 cursor-pointer flex items-center justify-between"
        >
          <span x-text="opt"></span>
          <svg x-show="opt === value" class="w-4 h-4 text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-width="2" d="M5 13l4 4L19 7"/>
          </svg>
        </li>
      </template>

      {{-- saat tidak ada hasil, tawarkan pakai teks apa adanya --}}
      <li x-show="results.length === 0" class="px-3 py-2 text-slate-500">
        Tekan <b>Enter</b> untuk memilih: "<span x-text="query"></span>"
      </li>
    </ul>
  </template>
</div>
