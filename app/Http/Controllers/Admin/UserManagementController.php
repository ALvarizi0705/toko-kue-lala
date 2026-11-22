<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserManagementController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        if ($request->filled('q')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->q . '%')
                  ->orWhere('email', 'like', '%' . $request->q . '%')
                  ->orWhere('phone', 'like', '%' . $request->q . '%');
            });
        }

        $users = $query->orderBy('role')->orderBy('name')->paginate(15)->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.form');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20|unique:users,phone',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:8',
            'role' => ['required', Rule::in(['pemilik', 'staf_kasir', 'staf_produksi', 'pelanggan'])],
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'phone.required' => 'Nomor WhatsApp wajib diisi.',
            'phone.unique' => 'Nomor WhatsApp sudah digunakan akun lain.',
            'email.required' => 'Email wajib diisi.',
            'email.unique' => 'Email sudah digunakan akun lain.',
            'password.required' => 'Kata sandi wajib diisi.',
            'password.min' => 'Kata sandi minimal 8 karakter.',
            'role.required' => 'Role akun wajib dipilih.',
        ]);

        User::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.akun.index')->with('success', 'Akun ' . $request->name . ' berhasil dibuat.');
    }

    public function edit(User $akun)
    {
        $user = $akun;
        return view('admin.users.form', compact('user'));
    }

    public function update(Request $request, User $akun)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => ['required', 'string', 'max:20', Rule::unique('users')->ignore($akun->id)],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($akun->id)],
            'role' => ['required', Rule::in(['pemilik', 'staf_kasir', 'staf_produksi', 'pelanggan'])],
        ]);

        $data = [
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'role' => $request->role,
            'is_active' => $request->has('is_active'),
        ];

        if ($request->filled('password')) {
            $request->validate(['password' => 'min:8']);
            $data['password'] = Hash::make($request->password);
        }

        $akun->update($data);

        return redirect()->route('admin.akun.index')->with('success', 'Data akun berhasil diperbarui.');
    }

    public function toggle(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Anda tidak dapat menonaktifkan akun sendiri.');
        }

        $user->update(['is_active' => !$user->is_active]);
        $status = $user->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return back()->with('success', "Akun {$user->name} berhasil {$status}.");
    }

    public function resetPassword(Request $request, User $user)
    {
        $request->validate([
            'new_password' => 'required|string|min:8',
        ], [
            'new_password.required' => 'Kata sandi baru wajib diisi.',
            'new_password.min' => 'Kata sandi baru minimal 8 karakter.',
        ]);

        $user->update([
            'password' => Hash::make($request->new_password),
        ]);

        return back()->with('success', "Kata sandi akun {$user->name} berhasil direset.");
    }

    public function destroy(User $akun)
    {
        if ($akun->id === auth()->id()) {
            return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $akun->delete();
        return redirect()->route('admin.akun.index')->with('success', 'Akun berhasil dihapus.');
    }
}
