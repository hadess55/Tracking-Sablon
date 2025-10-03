@extends('layouts.admin')

@section('content')
  @php($header = 'Produksi')


@php $active = $filter ?? 'semua'; @endphp
  <div x-data="searchBox()" class="relative max-w-8xl w-full">
    <form id="searchForm" action="{{ route('admin.produksi.index') }}" method="get">
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
        <a :href="item.url"
          class="flex items-center gap-3 px-4 py-2 hover:bg-slate-50">
          <span class="text-sm font-medium" x-text="item.text"></span>
        </a>
      </template>

      <div class="px-4 py-2 text-xs text-slate-500" x-show="!results.length && query.length >= 2">
        Tidak ada hasil
      </div>
    </div>
  </div>
  <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 animate-fade">

  <x-stat-card
      title="Total Produksi"
      :value="$totalProduksi ?? 0"
      :href="route('admin.produksi.index', ['filter' => 'semua'])"
      :active="($filter ?? 'semua') === 'semua'">
    <x-slot name="icon">
      <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-width="2" d="M3 6h18M3 12h18M3 18h18"/></svg>
    </x-slot>
  </x-stat-card>

  <x-stat-card
      title="Sedang Diproses"
      :value="$sedangProses ?? 0"
      :href="route('admin.produksi.index', ['filter' => 'proses'])"
      :active="($filter ?? 'semua') === 'proses'">
    <x-slot name="icon">
      <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-width="2" d="M12 6v6l4 2"/></svg>
    </x-slot>
  </x-stat-card>

  <x-stat-card
      title="Selesai"
      :value="$selesai ?? 0"
      :href="route('admin.produksi.index', ['filter' => 'selesai'])"
      :active="($filter ?? 'semua') === 'selesai'">
    <x-slot name="icon">
      <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
    </x-slot>
  </x-stat-card>
</div>




  {{-- Table / Empty state --}}
  <div class="mt-4 rounded-2xl bg-white/80 backdrop-blur border border-white/60 shadow-soft overflow-hidden">
    <div class="px-4 py-3 flex items-center justify-between gap-3">
      <div class="text-sm font-medium">Daftar Produksi</div>
      <a href="{{ route('admin.produksi.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-brand-600 text-white hover:bg-brand-700 shadow-soft">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg> Tambah
      </a>
    </div>

    @if($produksis->count() === 0)
      <div class="p-6"><x-empty-state title="Belum ada produksi" desc="Mulai dengan membuat entri produksi baru." ctaLabel="Tambah Produksi" :ctaHref="route('admin.produksi.create')" /></div>
    @else
      <div class="overflow-x-auto">
        <table class="min-w-full text-sm">
          <thead class="bg-white/60 text-slate-600">
            <tr>
              <th class="text-left font-medium px-4 py-3">Nomor</th>
              <th class="text-left font-medium px-4 py-3">Customer</th>
              <th class="text-left font-medium px-4 py-3">Produk</th>
              <th class="text-left font-medium px-4 py-3">Status</th>
              <th class="text-right font-medium px-4 py-3">Aksi</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-100/80">
            @foreach($produksis as $p)
              <tr class="hover:bg-white/50 transition">
                <td class="px-4 py-3 font-mono text-xs sm:text-sm">{{ $p->nomor_produksi }}</td>
                <td class="px-4 py-3">{{ $p->pelanggan->nama_lengkap ?? '-' }}</td>
                <td class="px-4 py-3">{{ $p->produk }} <span class="text-slate-500">({{ $p->jumlah }})</span></td>
                <td class="px-4 py-3"><x-status-badge :status="$p->status_sekarang"/></td>
                <td class="px-4 py-3 text-right space-x-2">
                  <a class="inline-flex px-3 py-2 rounded-lg border text-slate-700 hover:bg-white" href="{{ route('admin.produksi.show',$p) }}">Detail</a>
                  <a class="inline-flex px-3 py-2 rounded-lg border text-slate-700 hover:bg-white" href="{{ route('admin.produksi.edit',$p) }}">Edit</a>
                  <form class="inline" method="POST" action="{{ route('admin.produksi.destroy',$p) }}">
                    @csrf @method('DELETE')
                    <button class="inline-flex px-3 py-2 rounded-lg bg-rose-600 text-white hover:bg-rose-700" onclick="return confirm('Hapus data ini?')">Hapus</button>
                  </form>
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
<script>
function searchBox() {
  return {
    open: false,
    query: @json($q ?? ''),
    results: [],
    fetchData() {
      const q = this.query.trim();
      if (q.length < 2) { this.results = []; return; }

      fetch('{{ route('admin.produksi.quick') }}?q=' + encodeURIComponent(q), {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
      })
      .then(res => res.json())
      .then(d => { this.results = d.items; });
    }
  }
}
</script>