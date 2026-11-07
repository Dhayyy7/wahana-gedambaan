<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreTiketRequest extends FormRequest
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
            'kapal_id' => ['required', 'integer', 'exists:kapals,id'],
            'jumlah_tiket' => ['nullable', 'integer', 'min:1', 'max:50'],
            'nama_pembeli' => ['required', 'string', 'max:100'],
            'no_hp' => ['required', 'string', 'max:20'],
            'alamat' => ['nullable', 'string', 'max:500'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'kapal_id.required' => 'Wahana wajib dipilih.',
            'kapal_id.exists' => 'Wahana yang dipilih tidak valid.',
            'jumlah_tiket.integer' => 'Jumlah tiket harus berupa angka.',
            'jumlah_tiket.min' => 'Jumlah tiket minimal 1.',
            'jumlah_tiket.max' => 'Sekali pembuatan maksimal 50 tiket.',
            'nama_pembeli.required' => 'Nama pembeli wajib diisi.',
            'nama_pembeli.max' => 'Nama pembeli maksimal 100 karakter.',
            'no_hp.required' => 'Nomor HP wajib diisi.',
            'no_hp.max' => 'Nomor HP maksimal 20 karakter.',
        ];
    }
}
