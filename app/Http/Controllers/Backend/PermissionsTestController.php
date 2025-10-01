<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class PermissionsTestController extends Controller
{
    public function testPermissions(Request $request)
    {
        $user = auth()->user();
        
        if (!$user) {
            return response()->json(['error' => 'User not authenticated'], 401);
        }
        
        $permissions = [
            'access.dashboard',
            'access.backend',
            'manage.users',
            'view.users',
            'manage.population',
            'view.population',
            'manage.news',
            'view.news',
            'manage.agendas',
            'view.agendas',
            'manage.announcements',
            'view.announcements'
        ];
        
        $results = [];
        foreach ($permissions as $permission) {
            $results[$permission] = Gate::allows($permission);
        }
        
        return response()->json([
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'is_active' => $user->is_active
            ],
            'permissions' => $results,
            'hasPermissionMethod' => method_exists($user, 'hasPermission'),
            'samplePermissionCheck' => method_exists($user, 'hasPermission') ? $user->hasPermission('access.dashboard') : 'Method not available'
        ]);
    }
}