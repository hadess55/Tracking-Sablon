@php
  /** @var \App\Models\Pesanan|null $pesanan */
  $isEdit = isset($pesanan);
@endphp

{{-- Flash / errors global --}}
@if ($errors->any())
  <div class="mb-4 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-red-800">
    <ul class="ml-5 list-disc">
      @foreach ($errors->all() as $e) <li>{{ $e }}</li> @endforeach
    </ul>
  </div>
@endif

<div class="rounded-2xl border border-gray-200 bg-white shadow-sm">
  {{-- Header card --}}
  <div class="border-b border-gray-100 px-5 py-4">
    <h3 class="text-lg font-semibold text-gray-900">{{ $isEdit ? 'Edit Pesanan' : 'Detail Pesanan' }}</h3>
    <p class="text-sm text-gray-500">Lengkapi informasi produk, bahan, warna, tautan file, dan ukuran kaos.</p>
  </div>

  <div class="px-5 py-5">
    {{-- Identitas & Produk --}}
    <div class="grid gap-5 md:grid-cols-2">
      <div>
        <label class="mb-1 block text-sm font-medium text-gray-700">Customer <span class="text-red-600">*</span></label>
        <select name="pengguna_id"
                class="w-full rounded-xl border-gray-300 bg-white px-3 py-2.5 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                {{ $isEdit ? '' : 'required' }}>
          <option value="">Pilih customer</option>
          @foreach($customers as $c)
            <option value="{{ $c->id }}" @selected(old('pengguna_id', $pesanan->pengguna_id ?? '') == $c->id)>
              {{ $c->name }} — {{ $c->email }}
            </option>
          @endforeach
        </select>
        @error('pengguna_id')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
      </div>

      <div>
        <label class="mb-1 block text-sm font-medium text-gray-700">Produk <span class="text-red-600">*</span></label>
        <input name="produk" value="{{ old('produk',$pesanan->produk ?? '') }}" required
               placeholder="Contoh: Kaos, Hoodie, Polo…"
               class="w-full rounded-xl border-gray-300 px-3 py-2.5 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
        @error('produk')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
      </div>

      <div>
        <label class="mb-1 block text-sm font-medium text-gray-700">Bahan</label>
        <input name="bahan" value="{{ old('bahan',$pesanan->bahan ?? '') }}"
               placeholder="Combed 24s / 30s / Fleece / PE…"
               class="w-full rounded-xl border-gray-300 px-3 py-2.5 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
        @error('bahan')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
      </div>

      <div>
        <label class="mb-1 block text-sm font-medium text-gray-700">Warna</label>
        <input name="warna" value="{{ old('warna',$pesanan->warna ?? '') }}"
               placeholder="Hitam, Putih, Merah…"
               class="w-full rounded-xl border-gray-300 px-3 py-2.5 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
        @error('warna')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
      </div>

      <div class="md:col-span-2">
        <label class="mb-1 block text-sm font-medium text-gray-700">Tautan Drive</label>
        <input type="url" name="tautan_drive" value="{{ old('tautan_drive',$pesanan->tautan_drive ?? '') }}"
               placeholder="https://drive.google.com/..."
               class="w-full rounded-xl border-gray-300 px-3 py-2.5 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
        <p class="mt-1 text-xs text-gray-500">Masukkan link folder/berkas Google Drive yang berisi desain atau brief.</p>
        @error('tautan_drive')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
      </div>

      <div class="md:col-span-2">
        <label class="mb-1 block text-sm font-medium text-gray-700">Deskripsi</label>
        <textarea id="deskripsi" name="deskripsi" rows="4"
                  class="w-full rounded-xl border-gray-300 px-3 py-2.5 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                  placeholder="Keterangan tambahan: ukuran cetak, posisi logo, catatan produksi, dsb.">{{ old('deskripsi',$pesanan->deskripsi ?? '') }}</textarea>
        <div class="mt-1 flex items-center justify-between">
          <p class="text-xs text-gray-500">Boleh dikosongkan. Gunakan bahasa singkat dan jelas.</p>
          <span id="desc-counter" class="text-xs text-gray-400">0/500</span>
        </div>
        @error('deskripsi')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
      </div>
    </div>

    <hr class="my-6">

    {{-- Ukuran Kaos --}}
    <div>
      <div class="mb-3 flex items-center justify-between">
        <h4 class="text-sm font-semibold text-gray-900">Ukuran Kaos (jumlah per ukuran)</h4>
        <span id="total-badge" class="hidden rounded-full bg-indigo-100 px-3 py-1 text-xs font-medium text-indigo-700">Total: 0</span>
      </div>

      <div class="overflow-hidden rounded-xl border border-gray-200">
        <div class="grid grid-cols-5 bg-gray-50 text-center text-xs font-medium text-gray-600">
          @foreach (['S','M','L','XL','XXL'] as $sz)
            <div class="px-3 py-2">{{ $sz }}</div>
          @endforeach
        </div>
        <div class="grid grid-cols-5 text-center">
          @foreach (['S','M','L','XL','XXL'] as $sz)
            <div class="border-t border-gray-200 px-3 py-2">
              <input type="number" min="0" name="ukuran_kaos[{{ $sz }}]"
                     value="{{ old('ukuran_kaos.'.$sz, $pesanan->ukuran_kaos[$sz] ?? 0) }}"
                     class="w-20 rounded-lg border-gray-300 px-2 py-1 text-center focus:border-indigo-500 focus:ring-indigo-500 ukuran-input">
            </div>
          @endforeach
        </div>
      </div>

      <p class="mt-2 text-xs text-gray-500">Jika semua 0, total akan memakai nilai <b>Jumlah manual</b> di bawah.</p>

      <div class="mt-3">
        <label class="mb-1 block text-xs font-medium text-gray-600">Jumlah manual (opsional)</label>
        <input id="jumlah-manual" type="number" min="1" name="jumlah"
               value="{{ old('jumlah', $pesanan->jumlah ?? null) }}"
               class="w-40 rounded-xl border-gray-300 px-3 py-2 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
        <p id="jumlah-hint" class="mt-1 text-xs text-gray-500">Aktif bila semua ukuran = 0.</p>
      </div>
    </div>
  </div>

  {{-- Footer actions --}}
  <div class="flex items-center justify-end gap-2 border-t border-gray-100 px-5 py-4">
    <a href="{{ route('admin.pesanan.index', ['status'=>request('status')]) }}"
       class="rounded-xl border border-gray-300 bg-white px-4 py-2.5 text-gray-700 hover:bg-gray-50">Kembali</a>
    <button class="rounded-xl bg-indigo-600 px-4 py-2.5 text-white shadow-sm hover:bg-indigo-700">
      {{ $isEdit ? 'Update' : 'Simpan' }}
    </button>
  </div>
</div>

@push('scripts')
<script>
(function(){
  // ===== Deskripsi counter (maks 500, opsional) =====
  const desc = document.getElementById('deskripsi');
  const counter = document.getElementById('desc-counter');
  const MAX = 500;
  function syncCounter(){
    const len = (desc?.value || '').length;
    if (counter) counter.textContent = `${len}/${MAX}`;
  }
  if (desc && counter){
    desc.addEventListener('input', syncCounter);
    syncCounter();
  }

  // ===== Hitung total ukuran & toggle jumlah manual =====
  const sizeInputs = document.querySelectorAll('.ukuran-input');
  const badge = document.getElementById('total-badge');
  const manual = document.getElementById('jumlah-manual');
  const hint = document.getElementById('jumlah-hint');

  function calc(){
    let total = 0;
    sizeInputs.forEach(i => {
      const n = parseInt(i.value, 10);
      if (!isNaN(n) && n > 0) total += n;
    });

    if (badge){
      badge.classList.toggle('hidden', total === 0);
      badge.textContent = `Total: ${total}`;
    }

    if (manual){
      if (total > 0){
        manual.setAttribute('disabled','disabled');
        manual.classList.add('bg-gray-100');
        hint.textContent = 'Jumlah dihitung otomatis dari total ukuran.';
      } else {
        manual.removeAttribute('disabled');
        manual.classList.remove('bg-gray-100');
        hint.textContent = 'Aktif bila semua ukuran = 0.';
      }
    }
  }

  sizeInputs.forEach(i => i.addEventListener('input', calc));
  calc(); // initial
})();
</script>
@endpush
