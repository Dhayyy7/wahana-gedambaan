<?php

namespace App\Http\Requests\Admin;

use App\Models\Kapal;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateKapalRequest extends FormRequest
{
    /**
     * Authorize: hanya user yang sudah login.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Aturan validasi untuk memperbarui data kapal.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        /** @var Kapal $kapal */
        $kapal = $this->route('kapal');

        return [
            'kode_kapal' => ['required', 'string', 'max:20', 'unique:kapals,kode_kapal,'.$kapal->id],
            'nama_kapal' => ['required', 'string', 'max:100'],
            'tarif_biasa' => ['required', 'numeric', 'min:0'],
            'tarif_weekend' => ['required', 'numeric', 'min:0'],
            'diskon' => ['nullable', 'numeric', 'min:0'],
            'kapasitas' => ['required', 'integer', 'min:1', 'max:100'],
            'status' => ['required', 'integer', 'in:1,3'],
            'deskripsi' => ['nullable', 'string', 'max:1000'],
            'foto' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ];
    }

    /**
     * Pesan validasi kustom dalam Bahasa Indonesia.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'kode_kapal.required' => 'Kode kapal wajib diisi.',
            'kode_kapal.unique' => 'Kode kapal ini sudah digunakan, gunakan kode lain.',
            'kode_kapal.max' => 'Kode kapal maksimal 20 karakter.',
            'nama_kapal.required' => 'Nama kapal wajib diisi.',
            'tarif_biasa.required' => 'Tarif hari biasa wajib diisi.',
            'tarif_biasa.numeric' => 'Tarif hari biasa harus berupa angka.',
            'tarif_weekend.required' => 'Tarif weekend wajib diisi.',
            'tarif_weekend.numeric' => 'Tarif weekend harus berupa angka.',
            'diskon.numeric' => 'Diskon harus berupa angka.',
            'kapasitas.required' => 'Kapasitas penumpang wajib diisi.',
            'kapasitas.integer' => 'Kapasitas harus berupa bilangan bulat.',
            'kapasitas.min' => 'Kapasitas minimal 1 penumpang.',
            'status.required' => 'Status kapal wajib dipilih.',
            'status.in' => 'Status kapal tidak valid.',
            'foto.image' => 'File foto harus berupa gambar.',
            'foto.mimes' => 'Format foto harus jpg, jpeg, png, atau webp.',
            'foto.max' => 'Ukuran foto maksimal 2MB.',
        ];
    }
}
