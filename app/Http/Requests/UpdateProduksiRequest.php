<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProduksiRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'customer_id'      => ['required','exists:customers,id'],
            'produk'           => ['required','string','max:150'],
            'jumlah'           => ['required','integer','min:1'],
            'status_sekarang'  => ['required','string','max:50'],
            'catatan'          => ['nullable','string','max:500'],
        ];
    }

    public function attributes(): array
    {
        return [
            'customer_id'     => 'pelanggan',
            'produk'          => 'produk',
            'jumlah'          => 'jumlah',
            'status_sekarang' => 'status',
            'catatan'         => 'catatan',
        ];
    }
}
