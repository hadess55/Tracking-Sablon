@extends('layouts.admin')
@section('header','Detail Produksi')

@section('content')
@php
  // Kelas badge status
  $badgeClass = 'bg-slate-100 text-slate-800 border-slate-200';
  if(($produksi->statusDef?->is_final ?? false) || $produksi->status_key === 'selesai'){
    $badgeClass = 'bg-green-100 text-green-800 border-green-200';
  }
@endphp

{{-- Flash message sederhana (opsional) --}}
@if(session('berhasil'))
  <div class="mb-3 rounded-lg border border-green-200 bg-green-50 px-4 py-2 text-green-800">
    {{ session('berhasil') }}
  </div>
@endif
@if(session('peringatan'))
  <div class="mb-3 rounded-lg border border-amber-200 bg-amber-50 px-4 py-2 text-amber-800">
    {{ session('peringatan') }}
  </div>
@endif
@if($errors->any())
  <div class="mb-3 rounded-lg border border-red-200 bg-red-50 px-4 py-2 text-red-800">
    <ul class="ml-5 list-disc">
      @foreach($errors->all() as $e) <li>{{ $e }}</li> @endforeach
    </ul>
  </div>
@endif

{{-- Actions --}}
<div class="mb-4 flex items-center justify-between">
  <a href="{{ route('admin.produksi.index', ['filter'=>request('filter')]) }}"
     class="rounded-lg border px-3 py-2 text-sm text-white bg-indigo-600 hover:bg-indigo-700">← Kembali</a>

  <div class="flex items-center gap-2">
    <a href="{{ route('tracking.show', $produksi->nomor_resi) }}" target="_blank" rel="noopener"
       class="rounded-lg bg-indigo-600 px-3 py-2 text-sm text-white hover:bg-indigo-700">Lihat Tracking Publik</a>
  </div>
</div>

<div class="grid gap-4 lg:grid-cols-3">
  {{-- Ringkasan --}}
  <div class="lg:col-span-2 rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">
    <div class="flex items-start justify-between">
      <div>
        <div class="mt-1 text-sm text-gray-500">Nomor Resi</div>
        <h2 class="text-lg font-semibold text-gray-900">#{{ $produksi->nomor_resi }}</h2>
      </div>
      <span class="rounded-full border px-3 py-1 text-xs font-medium {{ $badgeClass }}">
        {{ $produksi->statusDef->label ?? strtoupper($produksi->status_key) }}
      </span>
    </div>

    <dl class="mt-4 grid gap-4 text-sm md:grid-cols-2">
      <div>
        <dt class="text-gray-500">Produk</dt>
        <dd class="mt-1">{{ $produksi->pesanan->produk ?? '—' }}</dd>
      </div>
      <div>
        <dt class="text-gray-500">Bahan</dt>
        <dd class="mt-1">{{ $produksi->pesanan->bahan ?? '—' }}</dd>
      </div>
      <div>
        <dt class="text-gray-500">Warna</dt>
        <dd class="mt-1">{{ $produksi->pesanan->warna ?? '—' }}</dd>
      </div>
      <div>
        <dt class="text-gray-500">Jumlah</dt>
        <dd class="mt-1">{{ $produksi->pesanan->jumlah ?? '—' }}</dd>
      </div>
      <div>
        <dt class="text-gray-500">Customer</dt>
        <dd class="mt-1">{{ optional($produksi->pesanan->pengguna)->name ?? '—' }}</dd>
      </div>
      <div>
        <dt class="text-gray-500">Email</dt>
        <dd class="mt-1">{{ optional($produksi->pesanan->pengguna)->email ?? '—' }}</dd>
      </div>
    </dl>

    @if(!is_null($produksi->progress))
      <div class="mt-5">
        <div class="flex justify-between text-xs text-gray-500">
          <span>Progress</span><span>{{ $produksi->progress }}%</span>
        </div>
        <div class="mt-1 h-2.5 overflow-hidden rounded-full bg-gray-100">
          <div class="h-full bg-indigo-600" style="width: {{ $produksi->progress }}%"></div>
        </div>
      </div>
    @endif
  </div>

  {{-- Ubah Status (combobox: pilih/ketik) --}}
  <div id="ubah-status" class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">
    <h3 class="mb-3 text-base font-semibold text-gray-900">Ubah Status Produksi</h3>
    <form method="POST" action="{{ route('admin.produksi.update',$produksi) }}">
      @csrf @method('PUT')

      <div class="space-y-4">
        {{-- Combobox kustom --}}
        {{-- Combobox: pilih atau ketik status (self-contained) --}}
<div
  x-data="{
    open:false,
    label: @js($produksi->statusDef->label ?? strtoupper($produksi->status_key)),
    key:   @js($produksi->status_key),
    all:   @js($statuses->map(fn($s)=>['key'=>$s->key,'label'=>$s->label])->values()),
    // daftar hasil
    get filtered(){
      const q = this.label.toLowerCase().trim();
      return this.all.filter(o => o.label.toLowerCase().includes(q));
    },
    // sinkronkan key setiap kali pengguna mengetik
    syncKey(){
      const q = this.label.toLowerCase().trim();
      const found = this.all.find(o => o.label.toLowerCase() === q);
      this.key = found ? found.key : ''; // <-- kosongkan jika label baru
    },
    choose(o){ this.label=o.label; this.key=o.key; this.open=false; },
    isNew(){
      const q = this.label.toLowerCase().trim();
      return q !== '' && !this.all.some(o => o.label.toLowerCase() === q);
    }
  }"
  class="relative "
>
  <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>

  <div class="relative">
    <input
      x-model="label"
      @focus="open=true"
      @input="open=true; syncKey()"
      placeholder="Pilih atau ketik status..."
      class="w-full rounded-lg border-gray-300 bg-white px-3 py-2 pr-9 focus:border-indigo-500 focus:ring-indigo-500"
    />
    <button type="button" @click="open=!open"
            class="absolute right-2 top-1/2 -translate-y-1/2 text-gray-500">▾</button>
  </div>

  <ul x-cloak x-show="open" @click.outside="open=false"
      class="absolute z-50 mt-1 w-full max-h-56 overflow-auto rounded-lg border border-gray-200 bg-white shadow-lg">
    <template x-for="opt in filtered" :key="opt.key">
      <li @click="choose(opt)" class="cursor-pointer px-3 py-2 hover:bg-gray-100" x-text="opt.label"></li>
    </template>
    <li x-show="!filtered.length" class="px-3 py-2 text-sm text-gray-500">Tidak ada pilihan</li>
  </ul>

  <!-- yang dikirim ke backend -->
  <input type="hidden" name="status_key"   x-model="key">
  <input type="hidden" name="status_label" x-model="label">

  <p class="mt-1 text-xs text-gray-500" x-show="isNew()">Status baru
    <b x-text="label"></b> akan dibuat saat disimpan.</p>
</div>


        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Progress (opsional)</label>
          <input type="number" min="0" max="100" name="progress"
                 value="{{ old('progress', $produksi->progress) }}"
                 class="w-28 rounded-lg border-gray-300 px-3 py-2 focus:border-indigo-500 focus:ring-indigo-500">
          <p class="mt-1 text-xs text-gray-500">Kosongkan bila tidak ingin memakai persentase.</p>
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Catatan (opsional)</label>
          <textarea name="catatan" rows="3"
                    class="w-full rounded-lg border-gray-300 px-3 py-2 outline-2 focus:border-indigo-500 focus:ring-indigo-500"
                    placeholder="Catatan singkat proses…">{{ old('catatan') }}</textarea>
        </div>

        <div class="flex items-center justify-end gap-2">
          <button class="rounded-lg bg-indigo-600 px-4 py-2 text-white hover:bg-indigo-700">Simpan</button>
        </div>
      </div>
    </form>
  </div>

  {{-- Timeline --}}
  <div class="lg:col-span-3 rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">
    <h3 class="mb-3 text-base font-semibold text-gray-900">Riwayat Produksi</h3>
    @php($logs = $produksi->logs()->oldest()->get())
    @if($logs->isEmpty())
      <p class="text-sm text-gray-500">Belum ada riwayat.</p>
    @else
      <ol class="relative ml-2 border-l-2 border-gray-200">
        @foreach($logs as $log)
          <li class="mb-5 ml-4">
            <div class="absolute -left-1.5 mt-1.5 h-3 w-3 rounded-full bg-indigo-500"></div>
            <time class="text-xs text-gray-500">{{ $log->created_at->format('d M Y H:i') }}</time>
            <div class="font-medium">{{ strtoupper($log->status_key) }}</div>
            @if($log->catatan)
              <div class="text-sm text-gray-700">{{ $log->catatan }}</div>
            @endif
            @if($log->author)
              <div class="mt-1 text-xs text-gray-400">oleh {{ $log->author->name }}</div>
            @endif
          </li>
        @endforeach
      </ol>
    @endif
  </div>
</div>
@endsection

@push('scripts')
<script>
// Combobox status: pilih dari daftar atau ketik manual
function statusCombo(options){
  // options = [{key,label}, ...]
  const map = Object.fromEntries(options.map(o => [o.label.toLowerCase(), o.key]));
  return {
    open:false,
    label:@json($produksi->statusDef->label ?? strtoupper($produksi->status_key)),
    key:@json($produksi->status_key),
    all: options,
    filtered: options,
    filter(){
      const q = this.label.toLowerCase().trim();
      this.filtered = this.all.filter(o => o.label.toLowerCase().includes(q));
      this.key = map[q] ?? ''; // kosong => backend akan buat status baru dari status_label
    },
    choose(o){ this.label=o.label; this.key=o.key; this.open=false; },
    isNew(){ return this.label.trim() !== '' && this.key === ''; }
  }
}
</script>
@endpush
