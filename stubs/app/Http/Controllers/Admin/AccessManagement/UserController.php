<?php

namespace App\Http\Controllers\Admin\AccessManagement;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:view_any_user', only: ['index', 'show']),
            new Middleware('permission:create_user', only: ['create', 'store']),
            new Middleware('permission:update_user', only: ['edit', 'update']),
            new Middleware('permission:delete_user', only: ['destroy']),
            new Middleware('permission:delete_any_user', only: ['bulkDelete']),
        ];
    }

    public function index(Request $request)
    {
        return view('pages.admin.access-management.users.index');
    }

    public function create()
    {
        if (! auth()->user()->hasRole('super_admin')) {
            return redirect()->route('admin.users.index')->with('error', 'Hanya Super Admin yang dapat menambahkan pengguna baru.');
        }

        $roles = Role::all();

        return view('pages.admin.access-management.users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        if (! auth()->user()->hasRole('super_admin')) {
            return redirect()->route('admin.users.index')->with('error', 'Hanya Super Admin yang dapat menambahkan pengguna baru.');
        }

        $request->validate([
            'username' => 'required|string|max:255|regex:/^[\pL\s\-\_0-9]+$/u',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'string', 'confirmed', Password::min(8)->mixedCase()->numbers()],
            'role' => 'required|string|exists:roles,name',
        ]);

        $user = User::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        $user->assignRole($request->role);

        return redirect()->route('admin.users.index')->with('success', 'User berhasil dibuat.');
    }

    public function show(User $user)
    {
        return view('pages.admin.access-management.users.show', compact('user'));
    }

    public function edit(User $user)
    {
        if (! auth()->user()->hasRole('super_admin') && $user->id_users !== auth()->id()) {
            return redirect()->route('admin.users.index')->with('error', 'Anda hanya dapat mengedit profil Anda sendiri.');
        }

        $roles = Role::all();

        return view('pages.admin.access-management.users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        if (! auth()->user()->hasRole('super_admin') && $user->id_users !== auth()->id()) {
            return redirect()->route('admin.users.index')->with('error', 'Anda hanya dapat mengedit profil Anda sendiri.');
        }

        $currentRole = $user->getRoleNames()->first();

        if ($user->hasRole('super_admin') && ! auth()->user()->hasRole('super_admin')) {
            return redirect()->back()->with('error', 'Anda tidak memiliki izin mengubah Super Admin.');
        }

        if ($request->role !== $currentRole && ! auth()->user()->hasRole('super_admin')) {
            return redirect()->back()->with('error', 'Hanya Super Admin yang dapat merubah role pengguna.');
        }

        if ($user->id_users === auth()->id() && $request->role !== $currentRole) {
            return redirect()->back()->with('error', 'Anda tidak dapat merubah role sendiri.');
        }

        $rules = [
            'username' => 'required|string|max:255|regex:/^[\pL\s\-\_0-9]+$/u',
            'email' => 'required|string|email|max:255|unique:users,email,'.$user->id_users.',id_users',
            'password' => ['nullable', 'string', 'confirmed', Password::min(8)->mixedCase()->numbers()],
            'role' => 'required|string|exists:roles,name',
        ];

        $request->validate($rules);

        $data = [
            'username' => $request->username,
            'email' => $request->email,
            'role' => $request->role,
        ];

        if ($request->filled('password')) {
            if (! auth()->user()->hasRole('super_admin')) {
                return redirect()->back()->with('error', 'Hanya Super Admin yang dapat mengganti password.');
            }
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);
        $user->syncRoles([$request->role]);

        return redirect()->route('admin.users.index')->with('success', 'User berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        if ($user->id_users === auth()->id()) {
            return redirect()->back()->with('error', 'Anda tidak dapat menghapus akun sendiri.');
        }

        if ($user->hasRole('super_admin')) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Super Admin tidak dapat dihapus.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'Pengguna berhasil dihapus.');
    }

    public function bulkDelete(Request $request)
    {
        $request->validate([
            'ids' => 'required|string',
        ]);

        $ids = json_decode($request->input('ids'), true);

        if (! is_array($ids) || empty($ids)) {
            return redirect()->back()->with('error', 'Tidak ada pengguna yang dipilih.');
        }

        $ids = array_slice(array_unique(array_filter(array_map('intval', $ids))), 0, 100);

        if (empty($ids)) {
            return redirect()->back()->with('error', 'Data tidak valid.');
        }

        $users = User::whereIn('id_users', $ids)->get();
        $usersToDelete = $users->reject(fn ($user) => $user->hasRole('super_admin'));

        if ($usersToDelete->isEmpty()) {
            return redirect()->back()->with('error', 'Tidak ada pengguna yang dapat dihapus.');
        }

        $deletedNames = $usersToDelete->pluck('username')->implode(', ');
        $deletedIds = $usersToDelete->pluck('id_users')->toArray();

        User::whereIn('id_users', $deletedIds)->delete();

        activity()
            ->causedBy(auth()->user())
            ->event('deleted')
            ->log("Bulk deleted Users: {$deletedNames}");

        return redirect()->back()->with('success', count($deletedIds).' pengguna berhasil dihapus.');
    }
}
