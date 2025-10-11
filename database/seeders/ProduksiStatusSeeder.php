<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ProduksiStatus;

class ProduksiStatusSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['key' => 'antri',   'label' => 'Antri'],
            ['key' => 'desain',  'label' => 'Desain'],
            ['key' => 'cutting', 'label' => 'Cutting'],
            ['key' => 'sablon',  'label' => 'Sablon'],
            ['key' => 'jahit',   'label' => 'Jahit'],
            ['key' => 'qc',      'label' => 'Quality Check'],
            ['key' => 'packing', 'label' => 'Packing'],
            ['key' => 'selesai', 'label' => 'Selesai', 'is_final' => true],
        ];

        collect($data)->each(function ($row, $i) {
            ProduksiStatus::firstOrCreate(
                ['key' => $row['key']],                  // unique key
                [
                    'label'    => $row['label'],
                    'urutan'   => $i + 1,
                    'is_final' => $row['is_final'] ?? false,
                ],
            );
        });
    }
}
