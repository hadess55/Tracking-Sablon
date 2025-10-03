@extends('layouts.admin')

@section('header', 'Persetujuan Pelanggan')

@section('content')

<form method="get" action="{{ route('admin.customers.index') }}" class="mb-4 flex gap-2">
  <input
    type="text"
    name="q"
    value="{{ $q ?? '' }}"
    placeholder="Cari…"
    class="w-full h-11 rounded-xl border px-3"
  />

  <select name="status" class="h-11 rounded-xl border px-3">
    <option value="">Semua</option>
    <option value="menunggu" {{ ($status ?? '') === 'menunggu' ? 'selected' : '' }}>Menunggu</option>
    <option value="disetujui" {{ ($status ?? '') === 'disetujui' ? 'selected' : '' }}>Disetujui</option>
    <option value="ditolak"   {{ ($status ?? '') === 'ditolak'   ? 'selected' : '' }}>Ditolak</option>
  </select>

  <button class="h-11 px-4 rounded-xl bg-indigo-600 text-white">Cari</button>
</form>

  <div class="rounded-2xl bg-white/80 backdrop-blur border border-white/60 shadow-soft overflow-hidden">

    <div class="px-4 py-3 border-b border-slate-200/70 flex items-center justify-between">
      <h2 class="text-sm font-semibold">Daftar Pelanggan</h2>


      <a href="{{ route('admin.customers.create') }}"
         class="inline-flex items-center gap-2 px-3 py-2 rounded-xl bg-brand-600 hover:bg-brand-700 text-white">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Tambah
      </a>
    </div>

    <div class="overflow-x-auto">
      <table class="min-w-full text-sm">
        <thead class="bg-white/60 text-slate-600">
          <tr>
            <th class="text-left px-4 py-3 font-medium">Nama</th>
            <th class="text-left px-4 py-3 font-medium">Email</th>
            <th class="text-left px-4 py-3 font-medium">No HP</th>
            <th class="text-left px-4 py-3 font-medium">Status</th>
            <th class="text-right px-4 py-3 font-medium">Aksi</th>
          </tr>
        </thead>

        <tbody class="divide-y divide-slate-100/70">
          @forelse ($customers as $c)
            @php $status = strtolower($c->status_persetujuan); @endphp

            <tr x-data="{ openApprove:false, openReject:false }">
              <td class="px-4 py-3">{{ $c->nama_lengkap }}</td>
              <td class="px-4 py-3">{{ $c->email }}</td>
              <td class="px-4 py-3">{{ $c->nomor_hp ?? '-' }}</td>


              <td class="px-4 py-3">
                @if(in_array($status, ['menunggu','pending']))
                  <span class="inline-flex items-center px-2 py-1 rounded-lg text-xs bg-yellow-100 text-yellow-700">Menunggu</span>
                @elseif($status === 'disetujui')
                  <span class="inline-flex items-center px-2 py-1 rounded-lg text-xs bg-emerald-100 text-emerald-700">Disetujui</span>
                  @if($c->disetujui_pada)
                    <span class="ml-2 text-[11px] text-slate-500">{{ $c->disetujui_pada->diffForHumans() }}</span>
                  @endif
                @elseif($status === 'ditolak')
                  <span class="inline-flex items-center px-2 py-1 rounded-lg text-xs bg-rose-100 text-rose-700">Ditolak</span>
                @else
                  <span class="text-xs text-slate-400">{{ $c->status_persetujuan }}</span>
                @endif
              </td>


              <td class="px-4 py-3 text-right">
                <div class="inline-flex flex-wrap gap-2 justify-end">

                  <a href="{{ route('admin.customers.show', $c) }}"
                     class="px-3 py-2 rounded-lg bg-slate-100 text-slate-700 hover:bg-slate-200">
                    Detail
                  </a>

               
                  <a href="{{ route('admin.customers.edit', $c) }}"
                     class="px-3 py-2 rounded-lg bg-indigo-50 text-indigo-700 hover:bg-indigo-100">
                    Edit
                  </a>

                  @if(in_array($status, ['menunggu','pending']))
                    
                    <button type="button"
                            @click="openApprove = true"
                            class="px-3 py-2 rounded-lg bg-emerald-600 text-white hover:bg-emerald-700">
                      Setujui
                    </button>

                   
                    <button type="button"
                            @click="openReject = true"
                            class="px-3 py-2 rounded-lg bg-rose-600 text-white hover:bg-rose-700">
                      Tolak
                    </button>
                  @endif
                </div>

                <form x-ref="approveForm" method="POST" action="{{ route('admin.customers.setujui', $c) }}" class="hidden">
                  @csrf
                </form>
                <form x-ref="rejectForm" method="POST" action="{{ route('admin.customers.tolak', $c) }}" class="hidden">
                  @csrf
                </form>


                <template x-teleport="body">
                  <div x-cloak x-show="openApprove"
                       x-transition.opacity.duration.200ms
                       x-trap.inert.noscroll="openApprove"
                       class="fixed inset-0 z-[100] flex items-center justify-center p-4">
                    <div class="absolute inset-0 bg-black/50" @click="openApprove=false"></div>
                    <div class="relative w-full max-w-md mx-auto rounded-2xl bg-white shadow-xl ring-1 ring-black/5 overflow-hidden">
                      <div class="p-4 border-b border-slate-100"><h3 class="text-base font-semibold">Setujui pelanggan?</h3></div>
                      <div class="p-4 text-sm text-slate-600">Pelanggan: <b>{{ $c->nama_lengkap }}</b><br>Email: {{ $c->email }}</div>
                      <div class="p-4 flex items-center justify-end gap-2 border-t border-slate-100">
                        <button type="button" @click="openApprove=false"
                                class="px-3 py-2 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700">Batal</button>
                        <button type="button" @click="$refs.approveForm.submit()"
                                class="px-3 py-2 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white">Setujui</button>
                      </div>
                    </div>
                  </div>
                </template>

                <template x-teleport="body">
                  <div x-cloak x-show="openReject"
                       x-transition.opacity.duration.200ms
                       x-trap.inert.noscroll="openReject"
                       class="fixed inset-0 z-[100] flex items-center justify-center p-4">
                    <div class="absolute inset-0 bg-black/50" @click="openReject=false"></div>
                    <div class="relative w-full max-w-md mx-auto rounded-2xl bg-white shadow-xl ring-1 ring-black/5 overflow-hidden">
                      <div class="p-4 border-b border-slate-100"><h3 class="text-base font-semibold">Tolak pelanggan?</h3></div>
                      <div class="p-4 text-sm text-slate-600">Pelanggan: <b>{{ $c->nama_lengkap }}</b><br>Email: {{ $c->email }}</div>
                      <div class="p-4 flex items-center justify-end gap-2 border-t border-slate-100">
                        <button type="button" @click="openReject=false"
                                class="px-3 py-2 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700">Batal</button>
                        <button type="button" @click="$refs.rejectForm.submit()"
                                class="px-3 py-2 rounded-lg bg-rose-600 hover:bg-rose-700 text-white">Tolak</button>
                      </div>
                    </div>
                  </div>
                </template>

              </td>
            </tr>
          @empty
            <tr>
              <td colspan="5" class="px-4 py-10 text-center text-slate-500">Belum ada pelanggan.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <div class="px-4 py-3 border-t border-slate-200/70">
      {{ $customers->links() }}
    </div>
  </div>
@endsection
