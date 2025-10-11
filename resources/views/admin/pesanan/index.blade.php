@extends('layouts.admin')

@section('header', 'Pesanan')

@section('content')

    {{-- Filter & Pencarian --}}
    <div class="mb-4 flex items-center gap-2">
        <a href="{{ route('admin.pesanan.index', ['status' => 'menunggu']) }}"
           class="px-3 py-1 rounded {{ request('status','menunggu')==='menunggu' ? 'bg-blue-600 text-white' : 'bg-gray-200' }}">
            Menunggu
        </a>
        <a href="{{ route('admin.pesanan.index', ['status' => 'disetujui']) }}"
           class="px-3 py-1 rounded {{ request('status')==='disetujui' ? 'bg-blue-600 text-white' : 'bg-gray-200' }}">
            Disetujui
        </a>
        <a href="{{ route('admin.pesanan.index', ['status' => 'ditolak']) }}"
           class="px-3 py-1 rounded {{ request('status')==='ditolak' ? 'bg-blue-600 text-white' : 'bg-gray-200' }}">
            Ditolak
        </a>

        <form action="{{ route('admin.pesanan.index') }}" method="GET" class="ml-auto flex gap-2">
            <input type="hidden" name="status" value="{{ request('status','menunggu') }}">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari judul / resi / nama"
                   class="border rounded px-3 py-1 w-64">
            <button class="px-3 py-1 rounded bg-gray-800 text-white">Cari</button>
        </form>
    </div>

    {{-- Flash message --}}
    @if(session('berhasil'))
        <div class="mb-4 p-3 rounded bg-green-100 text-green-800">
            {{ session('berhasil') }}
        </div>
    @endif
    @if(session('peringatan'))
        <div class="mb-4 p-3 rounded bg-yellow-100 text-yellow-800">
            {{ session('peringatan') }}
        </div>
    @endif
    @if ($errors->any())
        <div class="mb-4 p-3 rounded bg-red-100 text-red-800">
            <ul class="list-disc ml-5">
                @foreach ($errors->all() as $e)
                    <li>{{ $e }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Tabel Pesanan --}}
    <div class="overflow-x-auto bg-white rounded shadow">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-100 text-left">
            <tr>
                <th class="px-3 py-2">ID</th>
                <th class="px-3 py-2">Customer</th>
                <th class="px-3 py-2">Judul</th>
                <th class="px-3 py-2">Jumlah</th>
                <th class="px-3 py-2">Status</th>
                <th class="px-3 py-2">Nomor Resi</th>
                <th class="px-3 py-2 w-56">Aksi</th>
            </tr>
            </thead>
            <tbody>
            @forelse($pesanan as $p)
                <tr class="border-t">
                    <td class="px-3 py-2">{{ $p->id }}</td>
                    <td class="px-3 py-2">
                        {{ $p->pengguna->name ?? '-' }}<br>
                        <span class="text-xs text-gray-500">{{ $p->pengguna->email ?? '' }}</span>
                    </td>
                    <td class="px-3 py-2">
                        <div class="font-medium">{{ $p->judul }}</div>
                        @if($p->deskripsi)
                            <div class="text-xs text-gray-600 line-clamp-2">{{ $p->deskripsi }}</div>
                        @endif
                        <div class="text-[11px] text-gray-500">Dibuat: {{ $p->created_at->format('d M Y H:i') }}</div>
                    </td>
                    <td class="px-3 py-2">{{ $p->jumlah }}</td>
                    <td class="px-3 py-2">
                        @php
                            $badge = [
                                'menunggu' => 'bg-yellow-100 text-yellow-800',
                                'disetujui' => 'bg-green-100 text-green-800',
                                'ditolak' => 'bg-red-100 text-red-800',
                            ][$p->status] ?? 'bg-gray-100 text-gray-800';
                        @endphp
                        <span class="px-2 py-1 rounded text-xs {{ $badge }}">{{ Str::ucfirst($p->status) }}</span>
                        @if($p->tanggal_disetujui)
                            <div class="text-[11px] text-gray-500 mt-1">Disetujui: {{ $p->tanggal_disetujui->format('d M Y H:i') }}</div>
                        @endif
                    </td>
                    <td class="px-3 py-2">
                        @if($p->nomor_resi)
                            <code class="text-xs">{{ $p->nomor_resi }}</code>
                        @else
                            <span class="text-gray-400 text-xs">-</span>
                        @endif
                    </td>
                    <td class="px-3 py-2">
                        @if($p->status === 'menunggu')
                            <div class="flex items-center gap-2">
                                {{-- Setujui --}}
                                <form action="{{ route('admin.pesanan.setujui', $p) }}" method="POST"
                                      onsubmit="return confirm('Setujui pesanan #{{ $p->id }}? Nomor resi akan digenerate.')">
                                    @csrf
                                    <button class="px-3 py-1 rounded bg-green-600 text-white">Setujui</button>
                                </form>

                                {{-- Tolak (toggle alasan) --}}
                                <details class="relative">
                                    <summary class="list-none px-3 py-1 rounded bg-red-600 text-white cursor-pointer">
                                        Tolak
                                    </summary>
                                    <div class="absolute z-10 mt-2 p-3 bg-white border rounded shadow w-64">
                                        <form action="{{ route('admin.pesanan.tolak', $p) }}" method="POST">
                                            @csrf
                                            <label class="text-xs text-gray-600">Alasan penolakan</label>
                                            <textarea name="alasan" rows="3" class="w-full border rounded p-2 text-sm" required></textarea>
                                            <div class="mt-2 flex justify-end gap-2">
                                                <button type="submit" class="px-3 py-1 rounded bg-red-600 text-white text-sm">Kirim</button>
                                            </div>
                                        </form>
                                    </div>
                                </details>
                            </div>
                        @else
                            <div class="text-xs text-gray-600">Tidak ada aksi</div>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="px-3 py-6 text-center text-gray-500">Belum ada data.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="mt-4">
        {{ $pesanan->appends(['status'=>request('status'),'q'=>request('q')])->links() }}
    </div>

@endsection
