@extends('layouts.admin')

@section('content')
  @php($header = 'Produksi')
  @php($active = $filter ?? request('filter','semua'))
  
  {{-- Search --}}
  <div x-data="searchBox()" class="relative max-w-8xl w-full">
    <form id="searchForm" action="{{ route('admin.produksi.index') }}" method="get">
      @if($active && $active !== 'semua')
        <input type="hidden" name="filter" value="{{ $active }}">
      @endif
      <div class="relative">
        <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-width="2" d="M21 21l-4.35-4.35M10 18a8 8 0 1 1 0-16 8 8 0 0 1 0 16z"/>
        </svg>

        <input
          type="text"
          name="q"
          placeholder="Cari..."
          value="{{ $q ?? '' }}"
          x-model.debounce.300ms="query"
          @focus="open=true; fetchData()"
          @keydown.escape="open=false"
          @input="fetchData"
          class="w-full h-12 rounded-2xl pl-11 pr-3 bg-white/70 ring-1 ring-slate-200 focus:ring-2 focus:ring-indigo-500 outline-none"
        />
      </div>
    </form>

    <div x-show="open && results.length"
         @click.outside="open=false"
         class="absolute z-40 mt-2 w-full bg-white rounded-xl shadow-lg border border-slate-100 overflow-hidden">
      <template x-for="item in results" :key="item.id">
        <a :href="item.url" class="flex items-center gap-3 px-4 py-2 hover:bg-slate-50">
          <span class="text-sm font-medium" x-text="item.text"></span>
        </a>
      </template>
      <div class="px-4 py-2 text-xs text-slate-500" x-show="!results.length && query.length >= 2">
        Tidak ada hasil
      </div>
    </div>
  </div>

  {{-- Stat cards --}}
  <div class="mt-4 grid grid-cols-1 sm:grid-cols-3 gap-4 animate-fade">
    <x-stat-card
      title="Total Produksi"
      :value="$totalProduksi ?? 0"
      :href="route('admin.produksi.index', ['filter' => 'semua'])"
      :active="$active === 'semua'">
      <x-slot name="icon">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-width="2" d="M3 6h18M3 12h18M3 18h18"/></svg>
      </x-slot>
    </x-stat-card>

    <x-stat-card
      title="Sedang Diproses"
      :value="$sedangProses ?? 0"
      :href="route('admin.produksi.index', ['filter' => 'proses'])"
      :active="$active === 'proses'">
      <x-slot name="icon">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-width="2" d="M12 6v6l4 2"/></svg>
      </x-slot>
    </x-stat-card>

    <x-stat-card
      title="Selesai"
      :value="$selesai ?? 0"
      :href="route('admin.produksi.index', ['filter' => 'selesai'])"
      :active="$active === 'selesai'">
      <x-slot name="icon">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
      </x-slot>
    </x-stat-card>
  </div>

  {{-- Tabel / empty --}}
  <div class="mt-4 rounded-2xl bg-white/80 backdrop-blur border border-white/60 shadow-soft overflow-hidden">
    <div class="px-4 py-3 flex items-center justify-between gap-3">
      <div class="text-sm font-medium">Daftar Produksi</div>

      {{-- Produksi dibuat otomatis dari persetujuan pesanan.
           Kalau kamu tetap ingin tombol manual, hilangkan komentar di bawah. --}}
      {{-- <a href="{{ route('admin.produksi.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-brand-600 text-white hover:bg-brand-700 shadow-soft">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg> Tambah
      </a> --}}
    </div>

    @if($produksis->count() === 0)
      <div class="p-6">
        <x-empty-state
          title="Belum ada produksi"
          desc="Setujui pesanan untuk membuat entri produksi secara otomatis."
        />
      </div>
    @else
      <div class="overflow-x-auto">
        <table class="min-w-full text-sm">
          <thead class="bg-white/60 text-slate-600">
            <tr>
              <th class="text-left font-medium px-4 py-3">Nomor Resi</th>
              <th class="text-left font-medium px-4 py-3">Customer</th>
              <th class="text-left font-medium px-4 py-3">Produk</th>
              <th class="text-left font-medium px-4 py-3">Status</th>
              <th class="text-right font-medium px-4 py-3">Aksi</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-100/80">
            @foreach($produksis as $p)
              <tr class="hover:bg-white/50 transition">
                {{-- Resi (kunci tracking) --}}
                <td class="px-4 py-3 font-mono text-xs sm:text-sm">{{ $p->nomor_resi }}</td>

                {{-- Customer dari pesanan --}}
                <td class="px-4 py-3">
                  <div class="font-medium text-slate-800">{{ optional($p->pesanan->pengguna)->name ?? '-' }}</div>
                  <div class="text-xs text-slate-500">{{ optional($p->pesanan->pengguna)->email }}</div>
                </td>

                {{-- Produk + jumlah dari pesanan --}}
                <td class="px-4 py-3">
                  {{ $p->pesanan->produk ?? '-' }}
                  @if($p->pesanan?->jumlah)
                    <span class="text-slate-500">({{ $p->pesanan->jumlah }})</span>
                  @endif
                </td>

                {{-- Status produksi (label) + progress opsional --}}
                <td class="px-4 py-3">
                  @php($label = $p->statusDef->label ?? strtoupper($p->status_key))
                  @isset($components)
                    {{-- jika ada komponen status badge custom --}}
                    <x-status-badge :status="$label"/>
                  @else
                    <span class="inline-flex rounded-full bg-slate-100 px-2.5 py-1 text-xs font-medium text-slate-700">{{ $label }}</span>
                  @endisset
                  @if(!is_null($p->progress))
                    <span class="ml-2 text-xs text-slate-500">{{ $p->progress }}%</span>
                  @endif
                </td>

                <td class="px-4 py-3 text-right space-x-2">
                  <a class="inline-flex px-3 py-2 rounded-lg border text-slate-700 hover:bg-white" href="{{ route('admin.produksi.show',$p) }}">Detail</a>
                  <a class="inline-flex px-3 py-2 rounded-lg border text-slate-700 hover:bg-white" href="{{ route('admin.produksi.show',$p) }}#ubah-status">Status</a>
                  {{-- Hapus kalau memang diizinkan --}}
                  {{-- <form class="inline" method="POST" action="{{ route('admin.produksi.destroy',$p) }}">
                    @csrf @method('DELETE')
                    <button class="inline-flex px-3 py-2 rounded-lg bg-rose-600 text-white hover:bg-rose-700" onclick="return confirm('Hapus data ini?')">Hapus</button>
                  </form> --}}
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>

      <div class="px-4 py-3 border-t border-white/60">
        {{ $produksis->links() }}
      </div>
    @endif
  </div>
@endsection

@push('scripts')
<script>
function searchBox() {
  return {
    open: false,
    query: @json($q ?? ''),
    results: [],
    fetchData() {
      const q = this.query.trim();
      if (q.length < 2) { this.results = []; return; }

      // Kirim juga filter aktif agar hasil konsisten
      const url = new URL(@json(route('admin.produksi.quick')), window.location.origin);
      url.searchParams.set('q', q);
      const filter = @json($active);
      if (filter && filter !== 'semua') url.searchParams.set('filter', filter);

      fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' }})
        .then(res => res.json())
        .then(d => { this.results = d.items || []; this.open = true; })
        .catch(() => { this.results = []; });
    }
  }
}
</script>
@endpush
