<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Customers;

class UpdateCustomerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        /** @var Customers|int|string|null $routeParam */
        $routeParam = $this->route('customer');
        $customerId = is_object($routeParam) ? $routeParam->id : $routeParam;

        return [
            'nama_lengkap'       => ['required', 'string', 'max:150'],
            'email'              => [
                'required', 'email', 'max:150',
                Rule::unique('customers', 'email')->ignore($customerId),
            ],
            'nomor_hp'           => ['nullable', 'string', 'max:30'],
            'alamat'             => ['nullable', 'string', 'max:255'],
            'kota'               => ['nullable', 'string', 'max:100'],
            'provinsi'           => ['nullable', 'string', 'max:100'],
            'kode_pos'           => ['nullable', 'string', 'max:10'],
            'status_persetujuan' => ['required', 'in:menunggu,disetujui,ditolak'],
        ];
    }

    public function attributes(): array
    {
        return [
            'nama_lengkap'       => 'nama lengkap',
            'email'              => 'email',
            'nomor_hp'           => 'nomor HP',
            'status_persetujuan' => 'status persetujuan',
        ];
    }
}
