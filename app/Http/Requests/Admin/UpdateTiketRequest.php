<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateTiketRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'metode_pembayaran' => ['nullable', 'string', 'in:tunai,qris'],
            'nama_pembeli' => ['required', 'string', 'max:100'],
            'no_hp' => ['required', 'string', 'max:20'],
            'alamat' => ['nullable', 'string', 'max:500'],
            'apply_same_buyer' => ['nullable', 'boolean'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'metode_pembayaran.in' => 'Jenis pembayaran harus Tunai atau QRIS.',
            'nama_pembeli.required' => 'Nama pembeli wajib diisi.',
            'nama_pembeli.max' => 'Nama pembeli maksimal 100 karakter.',
            'no_hp.required' => 'Nomor HP wajib diisi.',
            'no_hp.max' => 'Nomor HP maksimal 20 karakter.',
        ];
    }
}

