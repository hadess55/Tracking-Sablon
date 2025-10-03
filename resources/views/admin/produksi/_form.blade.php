@props(['produksi', 'customers', 'statuses'])

@php
  $input = 'w-full h-10 rounded-xl bg-white/80 border border-slate-200 outline outline-1 outline-slate-200
            focus:outline-2 focus:outline-brand-600/60 focus:border-transparent px-3 transition';
  $textarea = 'w-full rounded-xl bg-white/80 border border-slate-200 outline outline-1 outline-slate-200
               focus:outline-2 focus:outline-brand-600/60 focus:border-transparent p-3 transition';
  $select = $input;

   $statusLabels = ['Antri','Desain','Cetak','Finishing','Selesai'];
@endphp

<div class="grid md:grid-cols-2 gap-4">
  <div class="rounded-xl border border-slate-200/70 bg-white/80 p-4">
    <div class="text-xs uppercase tracking-wide text-slate-500 mb-3">Data Produksi</div>

    <div class="space-y-3">
      <div>
        <label class="block text-xs text-slate-600 mb-1">Pelanggan</label>
        <select name="customer_id" class="{{ $select }}" required>
          <option value="">— pilih pelanggan —</option>
          @foreach($customers as $cust)
            <option value="{{ $cust->id }}" @selected(old('customer_id', $produksi->customer_id)==$cust->id)>
              {{ $cust->nama_lengkap }}
            </option>
          @endforeach
        </select>
        @error('customer_id')<p class="text-rose-600 text-xs mt-1">{{ $message }}</p>@enderror
      </div>

      <div>
        <label class="block text-xs text-slate-600 mb-1">Produk</label>
        <input name="produk" value="{{ old('produk', $produksi->produk) }}" class="{{ $input }}" required>
        @error('produk')<p class="text-rose-600 text-xs mt-1">{{ $message }}</p>@enderror
      </div>

      <div>
        <label class="block text-xs text-slate-600 mb-1">Jumlah</label>
        <input type="number" min="1" name="jumlah" value="{{ old('jumlah', $produksi->jumlah ?? 1) }}" class="{{ $input }}" required>
        @error('jumlah')<p class="text-rose-600 text-xs mt-1">{{ $message }}</p>@enderror
      </div>

      <div>
        <label class="block text-xs text-slate-600 mb-1">Status Sekarang</label>

        <x-combobox
          name="status_sekarang"
          :value="old('status_sekarang', $produksi->status_sekarang)"
          placeholder="mis. Antri / Desain / Cetak / …"
          :options="$statusLabels"
        />
        @error('status_sekarang')<p class="text-rose-600 text-xs mt-1">{{ $message }}</p>@enderror
      </div>
    </div>
  </div>

  <div class="rounded-xl border border-slate-200/70 bg-white/80 p-4">
    <div class="text-xs uppercase tracking-wide text-slate-500 mb-3">Catatan</div>
    <div>
      <textarea name="catatan" rows="8" class="{{ $textarea }}">{{ old('catatan', $produksi->catatan) }}</textarea>
      @error('catatan')<p class="text-rose-600 text-xs mt-1">{{ $message }}</p>@enderror
    </div>

    @if($produksi->exists)
      <div class="mt-3 text-xs text-slate-500">
        Nomor Produksi:
        <span class="inline-flex items-center px-2 py-1 rounded-lg bg-slate-100 text-slate-700 font-medium">
          {{ $produksi->nomor_produksi }}
        </span>
      </div>
    @endif
  </div>
</div>
