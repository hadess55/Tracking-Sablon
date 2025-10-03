@props(['title'=>'Belum ada data','desc'=>'Silakan tambahkan data baru terlebih dahulu.','ctaLabel'=>null,'ctaHref'=>null])
<div class="rounded-2xl border border-dashed border-slate-300 bg-white/70 p-10 text-center">
  <div class="mx-auto w-16 h-16 mb-4 rounded-2xl bg-brand-600/10 text-brand-700 flex items-center justify-center">
    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
  </div>
  <h3 class="text-lg font-semibold">{{ $title }}</h3>
  <p class="text-sm text-slate-600 mt-1">{{ $desc }}</p>
  @if($ctaLabel && $ctaHref)
    <a href="{{ $ctaHref }}" class="inline-flex mt-4 px-4 py-2.5 rounded-xl bg-brand-600 text-white hover:bg-brand-700 shadow-soft">{{ $ctaLabel }}</a>
  @endif
</div>
