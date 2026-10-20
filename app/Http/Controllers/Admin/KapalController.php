<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreKapalRequest;
use App\Http\Requests\Admin\UpdateKapalRequest;
use App\Models\Kapal;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class KapalController extends Controller
{
    /**
     * Tampilkan daftar semua kapal yang terdaftar.
     */
    public function index(): View
    {
        $kapals = Kapal::latest()->paginate(10);

        return view('admin.kapal.index', compact('kapals'));
    }

    /**
     * Tampilkan form untuk menambah kapal baru.
     */
    public function create(): View
    {
        return view('admin.kapal.create');
    }

    /**
     * Simpan data kapal baru ke database.
     */
    public function store(StoreKapalRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        // Upload foto jika ada
        if ($request->hasFile('foto')) {
            $validated['foto'] = $request->file('foto')->store('kapal', 'public');
        }

        // Pastikan diskon default 0 jika kosong
        $validated['diskon'] = $validated['diskon'] ?? 0;

        Kapal::create($validated);

        return redirect()
            ->route('admin.kapal.index')
            ->with('success', "Kapal \"{$validated['nama_kapal']}\" berhasil ditambahkan.");
    }

    /**
     * Tampilkan detail satu kapal.
     */
    public function show(Kapal $kapal): View
    {
        return view('admin.kapal.show', compact('kapal'));
    }

    /**
     * Tampilkan form edit data kapal.
     */
    public function edit(Kapal $kapal): View
    {
        return view('admin.kapal.edit', compact('kapal'));
    }

    /**
     * Perbarui data kapal yang sudah ada di database.
     */
    public function update(UpdateKapalRequest $request, Kapal $kapal): RedirectResponse
    {
        $validated = $request->validated();

        // Upload foto baru jika ada, hapus foto lama
        if ($request->hasFile('foto')) {
            if ($kapal->foto) {
                Storage::disk('public')->delete($kapal->foto);
            }
            $validated['foto'] = $request->file('foto')->store('kapal', 'public');
        } else {
            // Pertahankan foto lama jika tidak ada upload baru
            unset($validated['foto']);
        }

        $validated['diskon'] = $validated['diskon'] ?? 0;

        $kapal->update($validated);

        return redirect()
            ->route('admin.kapal.index')
            ->with('success', "Data kapal \"{$kapal->nama_kapal}\" berhasil diperbarui.");
    }

    /**
     * Hapus data kapal dari database beserta foto-nya.
     */
    public function destroy(Kapal $kapal): RedirectResponse
    {
        $namaKapal = $kapal->nama_kapal;

        // Hapus foto dari storage jika ada
        if ($kapal->foto) {
            Storage::disk('public')->delete($kapal->foto);
        }

        $kapal->delete();

        return redirect()
            ->route('admin.kapal.index')
            ->with('success', "Kapal \"{$namaKapal}\" berhasil dihapus.");
    }
}
