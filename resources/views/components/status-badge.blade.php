@props(['status'])

@php
  $map = [
    'Menunggu Produksi' => 'bg-yellow-50 text-yellow-700 ring-1 ring-yellow-200',
    'Order Diterima'    => 'bg-blue-50 text-blue-700 ring-1 ring-blue-200',
    'Desain'            => 'bg-indigo-50 text-indigo-700 ring-1 ring-indigo-200',
    'Cetak'             => 'bg-purple-50 text-purple-700 ring-1 ring-purple-200',
    'Finishing'         => 'bg-amber-50 text-amber-800 ring-1 ring-amber-200',
    'QC'                => 'bg-teal-50 text-teal-700 ring-1 ring-teal-200',
    'Selesai'           => 'bg-emerald-50 text-emerald-700 ring-1 ring-emerald-200',
  ];
  $cls = $map[$status] ?? 'bg-gray-100 text-gray-700 ring-1 ring-gray-200';
@endphp

<span class="inline-flex items-center gap-1 rounded-full px-2.5 py-1 text-xs font-medium {{ $cls }}">
  <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
  {{ $status }}
</span>
