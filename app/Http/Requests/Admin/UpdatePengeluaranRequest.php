<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdatePengeluaranRequest extends FormRequest
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
            'tanggal_pengeluaran' => ['required', 'date'],
            'nama_pengeluaran' => ['required', 'string', 'max:150'],
            'nominal' => ['required', 'numeric', 'min:1'],
            'keterangan' => ['nullable', 'string', 'max:1000'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'tanggal_pengeluaran.required' => 'Tanggal pengeluaran wajib diisi.',
            'tanggal_pengeluaran.date' => 'Format tanggal pengeluaran tidak valid.',
            'nama_pengeluaran.required' => 'Nama pengeluaran wajib diisi.',
            'nama_pengeluaran.max' => 'Nama pengeluaran maksimal 150 karakter.',
            'nominal.required' => 'Nominal pengeluaran wajib diisi.',
            'nominal.numeric' => 'Nominal pengeluaran harus berupa angka.',
            'nominal.min' => 'Nominal pengeluaran minimal Rp 1.',
            'keterangan.max' => 'Keterangan maksimal 1000 karakter.',
        ];
    }
}

