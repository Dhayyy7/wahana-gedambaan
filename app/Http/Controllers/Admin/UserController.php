<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreUserRequest;
use App\Http\Requests\Admin\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class UserController extends Controller
{
    /**
     * Tampilkan daftar user beserta statistik dan filter role.
     */
    public function index(Request $request): View
    {
        $query = User::query();

        // Filter pencarian
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('username', 'like', "%{$search}%");
            });
        }

        // Filter role
        if ($role = $request->input('role')) {
            $query->where('role', (int) $role);
        }

        $users = $query->latest('id')->paginate(10)->withQueryString();

        // Hitung statistik
        $totalUsers = User::count();
        $totalSuperadmin = User::where('role', User::ROLE_SUPERADMIN)->count();
        $totalKasir = User::where('role', User::ROLE_KASIR)->count();
        $totalOperator = User::where('role', User::ROLE_OPERATOR)->count();

        return view('admin.user.index', compact(
            'users',
            'totalUsers',
            'totalSuperadmin',
            'totalKasir',
            'totalOperator'
        ));
    }

    /**
     * Form tambah user baru.
     */
    public function create(): View
    {
        return view('admin.user.create');
    }

    /**
     * Simpan user baru ke database.
     */
    public function store(StoreUserRequest $request): RedirectResponse
    {
        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'role' => (int) $request->role,
            'password' => Hash::make($request->password),
        ]);

        return redirect()
            ->route('admin.users.index')
            ->with('success', "User '{$user->name}' ({$user->role_label}) berhasil ditambahkan!");
    }

    /**
     * Detail user (diarahkan ke form edit).
     */
    public function show(User $user): RedirectResponse
    {
        return redirect()->route('admin.users.edit', $user);
    }

    /**
     * Form edit data user.
     */
    public function edit(User $user): View
    {
        return view('admin.user.edit', compact('user'));
    }

    /**
     * Perbarui data user.
     */
    public function update(UpdateUserRequest $request, User $user): RedirectResponse
    {
        $data = [
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'role' => (int) $request->role,
        ];

        // Update password jika diisi
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()
            ->route('admin.users.index')
            ->with('success', "Data user '{$user->name}' berhasil diperbarui!");
    }

    /**
     * Hapus user (dengan proteksi akun sendiri & akun superadmin terakhir).
     */
    public function destroy(User $user): RedirectResponse
    {
        // 1. Tidak boleh menghapus akun yang sedang dipakai login
        if ($user->id === Auth::id()) {
            return back()->withErrors(['error' => 'Anda tidak dapat menghapus akun Anda sendiri yang sedang aktif digunakan.']);
        }

        // 2. Tidak boleh menghapus superadmin jika hanya tersisa 1
        if ($user->isSuperadmin()) {
            $superadminCount = User::where('role', User::ROLE_SUPERADMIN)->count();
            if ($superadminCount <= 1) {
                return back()->withErrors(['error' => 'Tidak dapat menghapus Superadmin terakhir di sistem.']);
            }
        }

        $name = $user->name;
        $user->delete();

        return redirect()
            ->route('admin.users.index')
            ->with('success', "User '{$name}' berhasil dihapus.");
    }
}
