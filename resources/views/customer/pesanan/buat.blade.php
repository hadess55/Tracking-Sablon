@extends('layouts.app')
@section('title','Buat Pesanan')

@section('content')
<div class="fixed inset-0 -z-10">
    <div class="absolute inset-x-0 top-0 h-64 bg-gradient-to-b from-indigo-50 to-transparent"></div>
    <div class="absolute inset-0 bg-[radial-gradient(rgba(99,102,241,.12)_1px,transparent_1px)] [background-size:16px_16px]"></div>
  </div>
<div class="max-w-6xl mx-auto">
  {{-- Back --}}

  {{-- Hero --}}
  <div class="rounded-2xl border border-white/60 bg-gradient-to-br from-indigo-500/10 via-indigo-300/10 to-transparent p-6">
    <h1 class="text-lg font-semibold">Buat Pesanan</h1>
    <p class="text-sm text-slate-600">Isi detail pesanan Anda, admin akan meninjau terlebih dahulu.</p>
  </div>

  {{-- Error summary --}}
  @if ($errors->any())
    <div class="mt-4 rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700">
      <div class="font-medium mb-1">Periksa lagi isian berikut:</div>
      <ul class="list-disc pl-5 space-y-0.5">
        @foreach ($errors->all() as $err)
          <li>{{ $err }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  {{-- Form --}}
  <div x-data="formPesanan()" x-init="init()"
       class="mt-4 rounded-2xl border border-white/60 bg-white/80 backdrop-blur p-5 shadow-sm">

    <form method="POST" action="{{ route('pesanan.simpan') }}" class="space-y-6 bg-white rounded-lg">
      @csrf

      {{-- Produk & Bahan --}}
      <div class="grid gap-4 sm:grid-cols-2">
        <div>
          <label class="text-sm text-slate-600">Produk <span class="text-rose-500">*</span></label>
          <input type="text" name="produk" required
                 value="{{ old('produk') }}"
                 class="mt-1 w-full rounded-xl border border-slate-200 bg-white/80 px-3 py-2 focus:border-indigo-500 focus:outline focus:outline-2 focus:outline-indigo-500"
                 placeholder="">
          @error('produk') <div class="mt-1 text-xs text-rose-600">{{ $message }}</div> @enderror
        </div>

        <div>
          <div class="flex items-center justify-between">
            <label class="text-sm text-slate-600">Bahan <span class="text-rose-500">*</span></label>
            <div class="flex gap-1">
              @foreach (['Cotton 24s','Cotton 30s','Fleece','Pique'] as $opt)
              <button type="button"
                      @click="$refs.bahan.value='{{ $opt }}'"
                      class="rounded-full border border-slate-200 bg-white px-2 py-0.5 text-[11px] text-slate-600 hover:bg-slate-50">{{ $opt }}</button>
              @endforeach
            </div>
          </div>
          <input type="text" name="bahan" required x-ref="bahan"
                 value="{{ old('bahan') }}"
                 class="mt-1 w-full rounded-xl border border-slate-200 bg-white/80 px-3 py-2 focus:border-indigo-500 focus:outline focus:outline-2 focus:outline-indigo-500"
                 placeholder="">
          @error('bahan') <div class="mt-1 text-xs text-rose-600">{{ $message }}</div> @enderror
        </div>
      </div>

      {{-- Link Drive & Warna --}}
      <div class="grid gap-4 sm:grid-cols-2">
        <div>
          <label class="text-sm text-slate-600">Link Desain (upload gambar pada drive dan paste link disini)</label>
          <input type="url" name="drive_link"
                 value="{{ old('drive_link') }}"
                 class="mt-1 w-full rounded-xl border border-slate-200 bg-white/80 px-3 py-2 focus:border-indigo-500 focus:outline focus:outline-2 focus:outline-indigo-500"
                 placeholder="https://drive.google.com/...">
          @error('drive_link') <div class="mt-1 text-xs text-rose-600">{{ $message }}</div> @enderror
        </div>

        <div>
          <div class="flex items-center justify-between">
            <label class="text-sm text-slate-600">Warna <span class="text-rose-500">*</span></label>
            <div class="flex gap-1">
              @foreach (['Putih','Hitam'] as $opt)
              <button type="button"
                      @click="$refs.warna.value='{{ $opt }}'"
                      class="rounded-full border border-slate-200 bg-white px-2 py-0.5 text-[11px] text-slate-600 hover:bg-slate-50">{{ $opt }}</button>
              @endforeach
            </div>
          </div>
          <input type="text" name="warna" required x-ref="warna"
                 value="{{ old('warna') }}"
                 class="mt-1 w-full rounded-xl border border-slate-200 bg-white/80 px-3 py-2 focus:border-indigo-500 focus:outline focus:outline-2 focus:outline-indigo-500"
                 placeholder="">
          @error('warna') <div class="mt-1 text-xs text-rose-600">{{ $message }}</div> @enderror
        </div>
      </div>

      {{-- Ukuran per-size --}}
      <div>
        <div class="mb-1 flex items-end justify-between">
          <label class="text-sm text-slate-600">Ukuran Kaos (jumlah per ukuran)</label>
          <span class="text-[11px] text-slate-500">Jika semua 0, total akan memakai <b>Jumlah Manual</b>.</span>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-5 gap-2">
          @foreach (['S'=>'s','M'=>'m','L'=>'l','XL'=>'xl','XXL'=>'xxl'] as $label => $key)
          <div class="rounded-xl border border-slate-200 bg-white/60 p-2">
            <div class="text-xs text-slate-500">{{ $label }}</div>
            <div class="mt-1 flex items-center gap-1">
              <button type="button" @click="dec('{{ $key }}')"
                      class="h-8 w-8 rounded-lg border border-slate-200 bg-white text-slate-600 hover:bg-slate-50">−</button>
              <input type="number" min="0" name="ukuran[{{ $key }}]" x-model.number="uk.{{ $key }}"
                     class="w-full rounded-lg border border-slate-200 px-2 py-1.5 text-center">
              <button type="button" @click="inc('{{ $key }}')"
                      class="h-8 w-8 rounded-lg border border-slate-200 bg-white text-slate-600 hover:bg-slate-50">+</button>
            </div>
          </div>
          @endforeach
        </div>
      </div>

      {{-- Jumlah manual & Total --}}
      <div class="grid gap-4 sm:grid-cols-2">
        <div>
          <label class="text-sm text-slate-600">Jumlah Manual (opsional)</label>
          <input type="number" min="0" name="jumlah_manual" x-model.number="jumlahManual"
                 value="{{ old('jumlah_manual') }}"
                 class="mt-1 w-full rounded-xl border border-slate-200 bg-white/80 px-3 py-2 focus:border-indigo-500 focus:outline focus:outline-2 focus:outline-indigo-500"
                 placeholder="Isi jika tidak menggunakan per-size">
          @error('jumlah_manual') <div class="mt-1 text-xs text-rose-600">{{ $message }}</div> @enderror
        </div>

        <div>
          <label class="text-sm text-slate-600">Total (otomatis)</label>
          <div class="mt-1 flex items-center gap-2 rounded-xl border border-slate-200 bg-slate-50 px-3 py-2">
            <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-width="2" d="M3 10h18M7 6h10M7 14h10M3 18h18"/></svg>
            <input type="number" readonly :value="total" class="w-full bg-transparent text-slate-700">
          </div>
        </div>
      </div>

      {{-- Deskripsi --}}
      <div>
        <label class="text-sm text-slate-600">Deskripsi (opsional)</label>
        <textarea name="deskripsi" rows="4"
                  class="mt-1 w-full rounded-xl border border-slate-200 bg-white/80 px-3 py-2 focus:border-indigo-500 focus:outline focus:outline-2 focus:outline-indigo-500"
                  placeholder="Tambahan keterangan desain, posisi sablon, catatan khusus, dll.">{{ old('deskripsi') }}</textarea>
        @error('deskripsi') <div class="mt-1 text-xs text-rose-600">{{ $message }}</div> @enderror
      </div>

      {{-- Actions --}}
      <div class="flex items-center justify-end gap-2">
        <a href="{{ route('pesanan.index') }}"
           class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-slate-700 hover:bg-slate-50">
          Batal
        </a>
        <button class="rounded-xl bg-indigo-600 px-4 py-2 text-white hover:bg-indigo-700">
          Kirim Pesanan
        </button>
      </div>
    </form>
  </div>
</div>

{{-- Alpine helper --}}
<script>
function formPesanan(){
  return {
    uk: {s:0, m:0, l:0, xl:0, xxl:0},
    jumlahManual: Number('{{ old('jumlah_manual', 0) }}') || 0,
    get total(){
      const t = (this.uk.s||0)+(this.uk.m||0)+(this.uk.l||0)+(this.uk.xl||0)+(this.uk.xxl||0);
      return t > 0 ? t : (this.jumlahManual||0);
    },
    inc(k){ this.uk[k] = Number(this.uk[k]||0) + 1; },
    dec(k){ this.uk[k] = Math.max(0, Number(this.uk[k]||0) - 1); },
    init(){
      // preload old values (agar tidak hilang saat validasi gagal)
      this.uk.s   = Number('{{ data_get(old('ukuran'), 's', 0) }}')   || 0;
      this.uk.m   = Number('{{ data_get(old('ukuran'), 'm', 0) }}')   || 0;
      this.uk.l   = Number('{{ data_get(old('ukuran'), 'l', 0) }}')   || 0;
      this.uk.xl  = Number('{{ data_get(old('ukuran'), 'xl', 0) }}')  || 0;
      this.uk.xxl = Number('{{ data_get(old('ukuran'), 'xxl', 0) }}') || 0;
    }
  }
}
</script>
@endsection
