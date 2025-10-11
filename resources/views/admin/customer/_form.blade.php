@php
  /** @var \App\Models\User|null $customer */
  $isEdit = isset($customer);
@endphp

{{-- Flash & global errors --}}
@if ($errors->any())
  <div class="mb-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-red-800">
    <ul class="ml-5 list-disc">
      @foreach ($errors->all() as $e) <li>{{ $e }}</li> @endforeach
    </ul>
  </div>
@endif

<div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
  {{-- Identitas --}}
  <div class="grid gap-4 md:grid-cols-2">
    <div>
      <label class="mb-1 block text-sm font-medium text-gray-700">Nama <span class="text-red-600">*</span></label>
      <input name="name" required
             value="{{ old('name', $customer->name ?? '') }}"
             class="w-full rounded-lg border-gray-300 px-3 py-2 shadow-sm focus:border-blue-500 focus:ring-blue-500">
      @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
      <label class="mb-1 block text-sm font-medium text-gray-700">Email <span class="text-red-600">*</span></label>
      <input type="email" name="email" required
             value="{{ old('email', $customer->email ?? '') }}"
             class="w-full rounded-lg border-gray-300 px-3 py-2 shadow-sm focus:border-blue-500 focus:ring-blue-500">
      @error('email') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
      <label class="mb-1 block text-sm font-medium text-gray-700">
        {{ $isEdit ? 'Password (opsional)' : 'Password' }} {{ $isEdit ? '' : ' ' }} @unless($isEdit)<span class="text-red-600">*</span>@endunless
      </label>
      <input type="password" name="password" {{ $isEdit ? '' : 'required' }}
             placeholder="{{ $isEdit ? 'Biarkan kosong jika tidak diganti' : '' }}"
             class="w-full rounded-lg border-gray-300 px-3 py-2 shadow-sm focus:border-blue-500 focus:ring-blue-500">
      <p class="mt-1 text-xs text-gray-500">Min. 6 karakter.</p>
      @error('password') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
      <label class="mb-1 block text-sm font-medium text-gray-700">No HP</label>
      <input name="no_hp"
             value="{{ old('no_hp', $customer->no_hp ?? '') }}"
             placeholder="08xxxxxxxxxx"
             class="w-full rounded-lg border-gray-300 px-3 py-2 shadow-sm focus:border-blue-500 focus:ring-blue-500">
      @error('no_hp') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>
  </div>

  <hr class="my-5">

  {{-- Alamat Lengkap --}}
  <h3 class="mb-3 text-base font-semibold text-gray-900">Alamat Lengkap</h3>
  <div class="grid gap-4 md:grid-cols-2">
    <div class="md:col-span-2">
      <label class="mb-1 block text-sm font-medium text-gray-700">Jalan / No / Blok</label>
      <input name="jalan"
             value="{{ old('jalan', $customer->jalan ?? '') }}"
             placeholder="Jl. Mawar No. 10 Blok B"
             class="w-full rounded-lg border-gray-300 px-3 py-2 shadow-sm focus:border-blue-500 focus:ring-blue-500">
      @error('jalan') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
      <label class="mb-1 block text-sm font-medium text-gray-700">RT</label>
      <input name="rt" value="{{ old('rt', $customer->rt ?? '') }}"
             placeholder="001"
             class="w-full rounded-lg border-gray-300 px-3 py-2 shadow-sm focus:border-blue-500 focus:ring-blue-500">
      @error('rt') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
      <label class="mb-1 block text-sm font-medium text-gray-700">RW</label>
      <input name="rw" value="{{ old('rw', $customer->rw ?? '') }}"
             placeholder="002"
             class="w-full rounded-lg border-gray-300 px-3 py-2 shadow-sm focus:border-blue-500 focus:ring-blue-500">
      @error('rw') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
      <label class="mb-1 block text-sm font-medium text-gray-700">Kelurahan/Desa</label>
      <input name="kelurahan" value="{{ old('kelurahan', $customer->kelurahan ?? '') }}"
             class="w-full rounded-lg border-gray-300 px-3 py-2 shadow-sm focus:border-blue-500 focus:ring-blue-500">
      @error('kelurahan') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
      <label class="mb-1 block text-sm font-medium text-gray-700">Kecamatan</label>
      <input name="kecamatan" value="{{ old('kecamatan', $customer->kecamatan ?? '') }}"
             class="w-full rounded-lg border-gray-300 px-3 py-2 shadow-sm focus:border-blue-500 focus:ring-blue-500">
      @error('kecamatan') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
      <label class="mb-1 block text-sm font-medium text-gray-700">Kota/Kabupaten</label>
      <input name="kota_kab" value="{{ old('kota_kab', $customer->kota_kab ?? '') }}"
             class="w-full rounded-lg border-gray-300 px-3 py-2 shadow-sm focus:border-blue-500 focus:ring-blue-500">
      @error('kota_kab') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
      <label class="mb-1 block text-sm font-medium text-gray-700">Provinsi</label>
      <input name="provinsi" value="{{ old('provinsi', $customer->provinsi ?? '') }}"
             class="w-full rounded-lg border-gray-300 px-3 py-2 shadow-sm focus:border-blue-500 focus:ring-blue-500">
      @error('provinsi') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
      <label class="mb-1 block text-sm font-medium text-gray-700">Kode Pos</label>
      <input name="kode_pos" value="{{ old('kode_pos', $customer->kode_pos ?? '') }}"
             placeholder="65123"
             class="w-full rounded-lg border-gray-300 px-3 py-2 shadow-sm focus:border-blue-500 focus:ring-blue-500">
      @error('kode_pos') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>
  </div>
</div>

{{-- Actions --}}

<div class="mt-5 flex justify-end gap-2">
  <a href="{{ route('admin.customer.index') }}"
     class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-gray-700 hover:bg-gray-50">
     Batal
  </a>
  <button type="submit"
          class="inline-flex items-center gap-2 rounded-lg bg-indigo-600 px-4 py-2.5 text-white shadow-sm hover:bg-indigo-700">
    {{ $isEdit ? 'Update' : 'Simpan' }}
  </button>

</div>
