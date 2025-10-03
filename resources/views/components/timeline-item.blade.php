@props(['tahapan','waktu'=>null,'keterangan'=>null])

<li class="relative pl-8 group animate-slide-up">
  {{-- Garis vertikal --}}
  <span class="absolute left-3 top-0 bottom-0 w-px bg-gray-200"></span>

  {{-- Titik --}}
  <span class="absolute left-0 top-1.5 w-6 h-6 rounded-full bg-white border-2 border-brand-600 flex items-center justify-center">
    <span class="w-2 h-2 rounded-full bg-brand-600 group-hover:scale-110 transition"></span>
  </span>

  {{-- Konten --}}
  <div class="rounded-xl border border-gray-100 bg-white shadow-soft p-3 sm:p-4">
    <div class="flex flex-wrap items-center gap-2 mb-1">
      <span class="text-sm font-semibold text-gray-900">{{ $tahapan }}</span>
      @if($waktu)
        <span class="text-xs text-gray-500">• {{ $waktu }}</span>
      @endif
    </div>
    @if($keterangan)
      <p class="text-sm text-gray-700">{{ $keterangan }}</p>
    @endif
  </div>
</li>
