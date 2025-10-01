<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class PermissionController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:super_admin,admin']);
    }

    /**
     * Display permission management page
     */
    public function index(): View
    {
        $permissions = Permission::orderBy('name')->get();
        $roles = Role::with('permissions')->get();
        $users = User::with('permissions', 'roleObject')->get();
        
        return view('backend.permissions.index', compact('permissions', 'roles', 'users'));
    }

    /**
     * Update role permissions
     */
    public function updateRolePermissions(Request $request): RedirectResponse
    {
        $request->validate([
            'role_id' => 'required|exists:roles,id',
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,id'
        ]);

        $role = Role::findOrFail($request->role_id);
        $role->permissions()->sync($request->permissions ?? []);

        return redirect()->back()->with('success', "Permission role {$role->display_name} berhasil diperbarui!");
    }

    /**
     * Update user permissions
     */
    public function updateUserPermissions(Request $request): RedirectResponse
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,id'
        ]);

        $user = User::findOrFail($request->user_id);
        $user->permissions()->sync($request->permissions ?? []);

        return redirect()->back()->with('success', "Permission user {$user->name} berhasil diperbarui!");
    }

    /**
     * Test user permissions
     */
    public function testPermissions(): View
    {
        $user = auth()->user();
        $allPermissions = Permission::orderBy('name')->get();
        
        $userPermissions = [];
        foreach ($allPermissions as $permission) {
            $userPermissions[$permission->name] = $user->hasPermission($permission->name);
        }
        
        return view('backend.permissions.test', compact('allPermissions', 'userPermissions', 'user'));
    }
}