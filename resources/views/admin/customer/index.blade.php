@extends('layouts.admin')

@section('header', 'Customer')

@section('content')
    {{-- Page Header --}}
    <div class="mb-5 flex items-center justify-between">
        <div>
            <nav class="text-sm text-gray-500 mb-1" aria-label="Breadcrumb">
                <ol class="list-reset inline-flex items-center gap-2">
                    <li><a href="{{ route('admin.pesanan.index') }}" class="hover:text-gray-700">Panel Admin</a></li>
                    <li>/</li>
                    <li class="text-gray-700 font-medium">Customer</li>
                </ol>
            </nav>
            <h1 class="text-xl md:text-2xl font-semibold tracking-tight text-gray-900">Customer</h1>
            <p class="text-sm text-gray-500 mt-1">Kelola akun customer: buat, ubah data, dan hapus.</p>
        </div>

        <div class="flex items-center gap-2">
            <a href="{{ route('admin.customer.create') }}"
               class="inline-flex items-center gap-2 rounded-lg bg-indigo-600 px-4 py-2 text-white hover:bg-indigo-700 shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor"><path d="M11 11V6a1 1 0 1 1 2 0v5h5a1 1 0 1 1 0 2h-5v5a1 1 0 1 1-2 0v-5H6a1 1 0 1 1 0-2h5z"/></svg>
                Tambah Customer
            </a>
        </div>
    </div>

    {{-- Top Toolbar --}}
    <div class="mb-4 flex items-center gap-3">
        <form action="{{ route('admin.customer.index') }}" method="GET" class="ml-auto w-full md:w-96">
            <div class="relative">
                <input type="text" name="q" value="{{ $q }}"
                       placeholder="Cari nama / email / no hp / alamat"
                       class="w-full rounded-lg border-gray-300 pl-10 pr-24 py-2.5 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                       autocomplete="off">
                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor"><path d="M10 2a8 8 0 1 1-5.293 14.293l-3 3a1 1 0 1 1-1.414-1.414l3-3A8 8 0 0 1 10 2zm0 2a6 6 0 1 0 0 12 6 6 0 0 0 0-12z"/></svg>
                </div>
                <button class="absolute right-1 top-1/2 -translate-y-1/2 rounded-md bg-gray-800 px-3 py-1.5 text-white text-sm hover:bg-gray-900">
                    Cari
                </button>
            </div>
        </form>
    </div>

    {{-- Flash Messages --}}
    @if(session('berhasil'))
        <div class="mb-4 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-green-800">
            {{ session('berhasil') }}
        </div>
    @endif
    @if(session('peringatan'))
        <div class="mb-4 rounded-lg border border-yellow-200 bg-yellow-50 px-4 py-3 text-yellow-800">
            {{ session('peringatan') }}
        </div>
    @endif
    @if ($errors->any())
        <div class="mb-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-red-800">
            <ul class="list-disc ml-5">
                @foreach ($errors->all() as $e) <li>{{ $e }}</li> @endforeach
            </ul>
        </div>
    @endif

    {{-- Table (Desktop) --}}
    <div class="hidden md:block rounded-xl border border-gray-200 bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="sticky top-0 z-10 bg-gray-50 text-left text-gray-600">
                    <tr class="border-b">
                        <th class="px-4 py-3 font-medium w-14">#</th>
                        <th class="px-4 py-3 font-medium">Nama</th>
                        <th class="px-4 py-3 font-medium">Email</th>
                        <th class="px-4 py-3 font-medium">No HP</th>
                        <th class="px-4 py-3 font-medium">Alamat</th>
                        <th class="px-4 py-3 font-medium text-right w-40">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($pelanggan as $c)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 text-gray-500">{{ $c->id }}</td>
                            <td class="px-4 py-3">
                                <div class="font-medium text-gray-900">{{ $c->name }}</div>
                                <div class="text-[11px] text-gray-500">Customer</div>
                            </td>
                            <td class="px-4 py-3 text-gray-700">{{ $c->email }}</td>
                            <td class="px-4 py-3 text-gray-700">{{ $c->no_hp ?? '—' }}</td>
                            <td class="px-4 py-3">
                                @php $alamat = $c->alamat_lengkap ?? null; @endphp
                                @if($alamat)
                                    <div class="max-w-xs truncate" title="{{ $alamat }}">{{ $alamat }}</div>
                                @else
                                    <span class="text-gray-400">—</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-2 justify-end">
                                    <a href="{{ route('admin.customer.edit',$c) }}"
                                       class="inline-flex items-center gap-1 rounded-md bg-amber-500/90 px-3 py-1.5 text-white hover:bg-amber-600">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor"><path d="M5 19h14v2H5v-2zm13.7-13.3a1 1 0 0 1 0 1.4l-8.9 8.9-3.4.8.8-3.4 8.9-8.9a1 1 0 0 1 1.4 0l1.2 1.2z"/></svg>
                                        Edit
                                    </a>
                                    <form method="POST" action="{{ route('admin.customer.destroy',$c) }}"
                                          onsubmit="return confirm('Hapus customer {{ $c->name }}? Tindakan ini tidak bisa dibatalkan.')">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                                class="inline-flex items-center gap-1 rounded-md bg-red-600 px-3 py-1.5 text-white hover:bg-red-700">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor"><path d="M9 3h6a1 1 0 0 1 1 1v1h4a1 1 0 1 1 0 2h-1v12a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V7H4a1 1 0 1 1 0-2h4V4a1 1 0 0 1 1-1zm1 4H7v12h10V7h-3H10zm1 2a1 1 0 0 1 1 1v7a1 1 0 1 1-2 0V10a1 1 0 0 1 1-1zm4 0a1 1 0 0 1 1 1v7a1 1 0 1 1-2 0V10a1 1 0 0 1 1-1zM10 5v1h4V5h-4z"/></svg>
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-14">
                                <div class="flex flex-col items-center justify-center text-center">
                                    <div class="mb-3 rounded-full bg-gray-100 p-3 text-gray-400">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" viewBox="0 0 24 24" fill="currentColor"><path d="M12 12a5 5 0 1 0 0-10 5 5 0 0 0 0 10zm-7 9a7 7 0 1 1 14 0H5z"/></svg>
                                    </div>
                                    <h3 class="text-gray-900 font-medium">Belum ada data</h3>
                                    <p class="text-sm text-gray-500 mt-1">Mulai dengan menambahkan customer baru.</p>
                                    <a href="{{ route('admin.customer.create') }}"
                                       class="mt-4 inline-flex items-center gap-2 rounded-lg bg-indigo-600 px-4 py-2 text-white hover:bg-indigo-700">
                                        Tambah Customer
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($pelanggan->hasPages())
            <div class="flex items-center justify-between px-4 py-3 border-t border-gray-100 bg-gray-50 rounded-b-xl">
                <div class="text-sm text-gray-500">
                    Menampilkan
                    <span class="font-medium text-gray-700">
                        {{ $pelanggan->firstItem() ?? 0 }}–{{ $pelanggan->lastItem() ?? 0 }}
                    </span>
                    dari <span class="font-medium text-gray-700">{{ $pelanggan->total() }}</span> customer
                </div>
                <div>{{ $pelanggan->appends(['q'=>request('q')])->links() }}</div>
            </div>
        @endif
    </div>

    {{-- Cards (Mobile) --}}
    <div class="md:hidden space-y-3">
        @forelse($pelanggan as $c)
            <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
                <div class="flex items-start justify-between">
                    <div>
                        <div class="font-semibold text-gray-900">{{ $c->name }}</div>
                        <div class="text-sm text-gray-600">{{ $c->email }}</div>
                        <div class="text-sm text-gray-600">{{ $c->no_hp ?? '—' }}</div>
                    </div>
                    <span class="text-xs text-gray-500">#{{ $c->id }}</span>
                </div>
                @php $alamat = $c->alamat_lengkap ?? null; @endphp
                <div class="mt-3 text-sm text-gray-700">
                    <span class="text-gray-500">Alamat:</span>
                    {{ $alamat ?: '—' }}
                </div>
                <div class="mt-4 flex items-center gap-2">
                    <a href="{{ route('admin.customer.edit',$c) }}"
                       class="flex-1 rounded-md bg-amber-500/90 px-3 py-2 text-center text-white hover:bg-amber-600">Edit</a>
                    <form method="POST" action="{{ route('admin.customer.destroy',$c) }}" class="flex-1"
                          onsubmit="return confirm('Hapus customer {{ $c->name }}?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="w-full rounded-md bg-red-600 px-3 py-2 text-white hover:bg-red-700">
                            Hapus
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="rounded-xl border border-gray-200 bg-white p-6 text-center text-gray-600">
                Belum ada data customer.
            </div>
        @endforelse

        @if($pelanggan->hasPages())
            <div class="pt-2">{{ $pelanggan->appends(['q'=>request('q')])->links() }}</div>
        @endif
    </div>
@endsection
