@extends('layouts.admin')
@section('header','Pesanan')

@section('content')

@php($active = $status ?? 'semua')

<div class="mb-4 flex items-center gap-2">
    @php($active = request('status', 'semua'))

<a href="{{ route('admin.pesanan.index') }}"
   class="px-3 py-1 rounded-lg {{ $active==='semua' ? 'bg-indigo-600 text-white' : 'bg-gray-200' }}">Semua</a>
<a href="{{ route('admin.pesanan.index', ['status'=>'menunggu']) }}"
   class="px-3 py-1 rounded-lg {{ $active==='menunggu' ? 'bg-indigo-600 text-white' : 'bg-gray-200' }}">Menunggu</a>
<a href="{{ route('admin.pesanan.index', ['status'=>'disetujui']) }}"
   class="px-3 py-1 rounded {{ $active==='disetujui' ? 'bg-indigo-600 text-white' : 'bg-gray-200' }}">Disetujui</a>
<a href="{{ route('admin.pesanan.index', ['status'=>'ditolak']) }}"
   class="px-3 py-1 rounded-lg {{ $active==='ditolak' ? 'bg-indigo-600 text-white' : 'bg-gray-200' }}">Ditolak</a>


<form action="{{ route('admin.pesanan.index') }}" method="GET" class="ml-auto flex gap-2">
    @if(request()->filled('status')) 
        <input type="hidden" name="status" value="{{ request('status') }}">
    @endif
    <input type="text" name="q" value="{{ request('q') }}" class="border rounded px-3 py-1 w-72"
           placeholder="Cari...">
    <button class="px-3 py-1 rounded-lg bg-indigo-600 text-white">Cari</button>
</form>

    <a href="{{ route('admin.pesanan.create') }}" class="px-3 py-1 rounded-lg bg-indigo-600 text-white">+ Buat Pesanan</a>
</div>



<div class="overflow-x-auto bg-white border rounded-xl shadow-sm">
<table class="min-w-full text-sm">
    <thead class="bg-gray-50 text-left">
        <tr>
            <th class="px-3 py-2">ID</th>
            <th class="px-3 py-2">Customer</th>
            <th class="px-3 py-2">Produk</th>
            <th class="px-3 py-2">Bahan</th>
            <th class="px-3 py-2">Ukuran</th>
            <th class="px-3 py-2">Jumlah</th>
            <th class="px-3 py-2">Warna</th>
            <th class="px-3 py-2">Nomor Resi</th>
            <th class="px-3 py-2">Aksi</th>
        </tr>
    </thead>
    <tbody>
        @forelse($pesanan as $o)
        <tr class="border-t">
            <td class="px-3 py-2">{{ $o->id }}</td>
            <td class="px-3 py-2">
                <div class="font-medium">{{ $o->pengguna->name ?? '-' }}</div>
                <div class="text-xs text-gray-500">{{ $o->pengguna->email ?? '' }}</div>
            </td>
            <td class="px-3 py-2">
                <div class="font-medium">{{ $o->produk }}</div>

                @if($o->tautan_drive)
                    <div class="text-xs">
                        <a class="text-indigo-600 underline" href="{{ $o->tautan_drive }}" target="_blank" rel="noopener">Link drive</a>
                    </div>
                @endif

                @if($o->deskripsi)
                    <div class="text-xs text-gray-500" title="{{ $o->deskripsi }}">
                        {{ Str::limit($o->deskripsi, 20) }}
                        {{-- {{ Str::words($o->deskripsi, 3, '…') }} --}}
                    </div>
                @endif
            </td>

            <td class="px-3 py-2">{{ $o->bahan ?: '—' }}</td>
            <td class="px-3 py-2">{{ $o->ukuran_ringkas }}</td>
            <td class="px-3 py-2">{{ $o->jumlah }}</td>
            <td class="px-3 py-2">{{ $o->warna ?: '—' }}</td>
            <td class="px-3 py-2">
                @if($o->nomor_resi)<code class="text-xs">{{ $o->nomor_resi }}</code>@else<span class="text-gray-400">—</span>@endif
            </td>
            <td class="px-3 py-2">
                <div class="flex items-center gap-2">
                    <a href="{{ route('admin.pesanan.show',$o) }}" class="underline">Lihat</a>
                    @if($o->status==='menunggu')
                        <a href="{{ route('admin.pesanan.edit',$o) }}" class="underline">Edit</a>
                        <form method="POST" class="inline" action="{{ route('admin.pesanan.setujui',$o) }}">
                            @csrf <button class="px-2 py-1 bg-green-600 text-white rounded">Setujui</button>
                        </form>
                        <details class="inline-block">
                            <summary class="px-2 py-1 bg-red-600 text-white rounded cursor-pointer">Tolak</summary>
                            <form method="POST" action="{{ route('admin.pesanan.tolak',$o) }}" class="mt-2">
                                @csrf
                                <textarea name="alasan" rows="2" class="border p-1 w-56" placeholder="Alasan penolakan" required></textarea>
                                <button class="mt-1 px-2 py-1 bg-red-600 text-white rounded">Kirim</button>
                            </form>
                        </details>
                    @endif
                </div>
            </td>
        </tr>
        @empty
        <tr><td colspan="9" class="px-3 py-10 text-center text-gray-500">Belum ada data.</td></tr>
        @endforelse
    </tbody>
</table>
</div>

@if($pesanan->hasPages())
<div class="mt-3">{{ $pesanan->links() }}</div>
@endif
@endsection
