@props(['customer', 'mode' => 'create'])

@php
  // util class outline untuk semua field
  $input = 'w-full h-10 rounded-xl bg-white/80 border border-slate-200 outline outline-1 outline-slate-200
            focus:outline-2 focus:outline-brand-600/60 focus:border-transparent px-3 transition';
  $textarea = 'w-full rounded-xl bg-white/80 border border-slate-200 outline outline-1 outline-slate-200
               focus:outline-2 focus:outline-brand-600/60 focus:border-transparent p-3 transition';
  $select = $input;
@endphp

<div class="grid md:grid-cols-2 gap-4">
  {{-- DATA AKUN --}}
  <div class="rounded-xl border border-slate-200/70 bg-white/80 p-4">
    <div class="text-xs uppercase tracking-wide text-slate-500 mb-3">Data Customer</div>

    <div class="space-y-3">
      <div>
        <label class="block text-xs text-slate-600 mb-1">Nama Lengkap</label>
        <input name="nama_lengkap" value="{{ old('nama_lengkap', $customer->nama_lengkap) }}"
               class="{{ $input }}" required>
        @error('nama_lengkap')<p class="text-rose-600 text-xs mt-1">{{ $message }}</p>@enderror
      </div>

      <div>
        <label class="block text-xs text-slate-600 mb-1">Email</label>
        <input type="email" name="email" value="{{ old('email', $customer->email) }}"
               class="{{ $input }}" required>
        @error('email')<p class="text-rose-600 text-xs mt-1">{{ $message }}</p>@enderror
      </div>

      <div>
        <label class="block text-xs text-slate-600 mb-1">Nomor HP</label>
        <input name="nomor_hp" value="{{ old('nomor_hp', $customer->nomor_hp) }}"
               class="{{ $input }}">
        @error('nomor_hp')<p class="text-rose-600 text-xs mt-1">{{ $message }}</p>@enderror
      </div>

      <div>
        <label class="block text-xs text-slate-600 mb-1">Status Persetujuan</label>
        <select name="status_persetujuan" class="{{ $select }}">
          @foreach (['menunggu'=>'Menunggu','disetujui'=>'Disetujui','ditolak'=>'Ditolak'] as $k=>$v)
            <option value="{{ $k }}" @selected(old('status_persetujuan',$customer->status_persetujuan)===$k)>{{ $v }}</option>
          @endforeach
        </select>
        @error('status_persetujuan')<p class="text-rose-600 text-xs mt-1">{{ $message }}</p>@enderror
      </div>
    </div>
  </div>

  {{-- ALAMAT --}}
  <div class="rounded-xl border border-slate-200/70 bg-white/80 p-4">
    <div class="text-xs uppercase tracking-wide text-slate-500 mb-3">Alamat</div>

    <div class="space-y-3">
      <div>
        <label class="block text-xs text-slate-600 mb-1">Alamat</label>
        <textarea name="alamat" rows="3" class="{{ $textarea }}">{{ old('alamat', $customer->alamat) }}</textarea>
        @error('alamat')<p class="text-rose-600 text-xs mt-1">{{ $message }}</p>@enderror
      </div>

      <div class="grid grid-cols-2 gap-3">
        <div>
          <label class="block text-xs text-slate-600 mb-1">Kota</label>
          <input name="kota" value="{{ old('kota', $customer->kota) }}" class="{{ $input }}">
        </div>
        <div>
          <label class="block text-xs text-slate-600 mb-1">Provinsi</label>
          <input name="provinsi" value="{{ old('provinsi', $customer->provinsi) }}" class="{{ $input }}">
        </div>
      </div>

      <div class="grid grid-cols-2 gap-3">
        <div>
          <label class="block text-xs text-slate-600 mb-1">Kode Pos</label>
          <input name="kode_pos" value="{{ old('kode_pos', $customer->kode_pos) }}" class="{{ $input }}">
        </div>
      </div>
    </div>
  </div>
</div>
