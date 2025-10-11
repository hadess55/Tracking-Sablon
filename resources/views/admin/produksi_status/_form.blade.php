@php($isEdit = isset($status))
<div class="rounded-2xl border bg-white p-5 shadow-sm max-w-xl">
  <div class="grid gap-4">
    <div>
      <label class="block text-sm font-medium mb-1">Key (unik, huruf kecil & dash)</label>
      <input name="key" value="{{ old('key', $status->key ?? '') }}" required
             class="w-full rounded border-gray-300 px-3 py-2" placeholder="mis. desain, cutting, sablon">
      @error('key')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
    </div>

    <div>
      <label class="block text-sm font-medium mb-1">Label</label>
      <input name="label" value="{{ old('label', $status->label ?? '') }}" required
             class="w-full rounded border-gray-300 px-3 py-2" placeholder="Desain">
      @error('label')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
    </div>

    <div class="grid grid-cols-2 gap-4">
      <div>
        <label class="block text-sm font-medium mb-1">Urutan</label>
        <input type="number" name="urutan" value="{{ old('urutan', $status->urutan ?? null) }}"
               class="w-full rounded border-gray-300 px-3 py-2" placeholder="otomatis kalau kosong">
        @error('urutan')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
      </div>
      <div class="flex items-center gap-2 mt-6">
        <input type="checkbox" id="is_final" name="is_final" value="1"
               @checked(old('is_final', $status->is_final ?? false)) class="rounded border-gray-300">
        <label for="is_final" class="text-sm">Tandai sebagai status akhir (selesai)</label>
      </div>
    </div>
  </div>

  <div class="mt-4 flex items-center gap-2">
    <button class="rounded bg-blue-600 text-white px-4 py-2">{{ $isEdit? 'Update' : 'Simpan' }}</button>
    <a href="{{ route('admin.produksi-status.index') }}" class="rounded border px-4 py-2">Kembali</a>
  </div>
</div>
