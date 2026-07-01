<?php

namespace App\Http\Controllers\Admin\AccessManagement;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Services\Acl\PermissionGeneratorService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:view_any_role', only: ['index', 'show']),
            new Middleware('permission:create_role', only: ['create', 'store']),
            new Middleware('permission:update_role', only: ['edit', 'update']),
            new Middleware('permission:delete_role', only: ['destroy']),
            new Middleware('permission:delete_any_role', only: ['bulkDelete']),
        ];
    }

    public function index(Request $request)
    {
        return view('pages.admin.access-management.roles.index');
    }

    public function create()
    {
        $groupedPermissions = PermissionGeneratorService::getGroupedPermissions();
        $customPermissions = PermissionGeneratorService::getCustomPermissions();
        $rolePermissions = [];

        return view('pages.admin.access-management.roles.create', compact('groupedPermissions', 'customPermissions', 'rolePermissions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|regex:/^[a-zA-Z0-9\-\_ ]+$/|unique:roles,name',
            'permissions' => 'array',
        ]);

        $role = Role::create(['name' => strtolower($request->name)]);

        if ($request->has('permissions')) {
            $validPermissions = Permission::whereIn('name', $request->permissions)->pluck('name')->toArray();
            $role->syncPermissions($validPermissions);
        }

        return redirect()->route('admin.roles.index')->with('success', 'Role berhasil dibuat.');
    }

    public function show(Role $role)
    {
        $rolePermissions = $role->permissions->pluck('name')->toArray();
        $groupedPermissions = PermissionGeneratorService::getGroupedPermissions();
        $customPermissions = PermissionGeneratorService::getCustomPermissions();

        return view('pages.admin.access-management.roles.show', compact('role', 'groupedPermissions', 'customPermissions', 'rolePermissions'));
    }

    public function edit(Role $role)
    {
        $groupedPermissions = PermissionGeneratorService::getGroupedPermissions();
        $customPermissions = PermissionGeneratorService::getCustomPermissions();
        $rolePermissions = $role->permissions->pluck('name')->toArray();

        return view('pages.admin.access-management.roles.edit', compact('role', 'groupedPermissions', 'customPermissions', 'rolePermissions'));
    }

    public function update(Request $request, Role $role)
    {
        $request->validate([
            'name' => 'required|string|regex:/^[a-zA-Z0-9\-\_ ]+$/|unique:roles,name,'.$role->id,
            'permissions' => 'array',
        ]);

        $role->update(['name' => strtolower($request->name)]);

        if ($request->has('permissions')) {
            $validPermissions = Permission::whereIn('name', $request->permissions)->pluck('name')->toArray();
            $role->syncPermissions($validPermissions);
        } else {
            $role->syncPermissions([]);
        }

        return redirect()->route('admin.roles.index')->with('success', 'Role berhasil diperbarui.');
    }

    public function destroy(Role $role)
    {
        if ($role->name === 'super_admin') {
            return redirect()->route('admin.roles.index')
                ->with('error', 'Role sistem tidak dapat dihapus.');
        }

        $role->delete();

        return redirect()->route('admin.roles.index')
            ->with('success', 'Role berhasil dihapus.');
    }

    public function bulkDelete(Request $request)
    {
        $request->validate([
            'ids' => 'required|string',
        ]);

        $ids = json_decode($request->input('ids'), true);

        if (!is_array($ids) || empty($ids)) {
            return redirect()->back()->with('error', 'Tidak ada role yang dipilih.');
        }

        $ids = array_slice(array_unique(array_filter(array_map('intval', $ids))), 0, 100);

        if (empty($ids)) {
            return redirect()->back()->with('error', 'Data tidak valid.');
        }

        $roles = Role::whereIn('id', $ids)->get();
        $rolesToDelete = $roles->reject(fn ($role) => $role->name === 'super_admin');

        if ($rolesToDelete->isEmpty()) {
            return redirect()->back()->with('error', 'Tidak ada role yang dapat dihapus.');
        }

        $deletedNames = $rolesToDelete->pluck('name')->implode(', ');
        $deletedIds = $rolesToDelete->pluck('id')->toArray();

        Role::whereIn('id', $deletedIds)->delete();

        activity()
            ->causedBy(auth()->user())
            ->event('deleted')
            ->log("Bulk deleted Roles: {$deletedNames}");

        return redirect()->back()->with('success', count($deletedIds).' role berhasil dihapus.');
    }
}
