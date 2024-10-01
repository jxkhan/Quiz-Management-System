<?php

namespace App\Http\Controllers;

use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function getRolesWithPermissions()
    {
        // Retrieve all roles and their associated permissions
        $roles = Role::with('permissions')->get();

        // Format the response to include roles and permissions
        $response = $roles->map(function ($role) {
            return [
                'role' => $role->name,
                'permissions' => $role->permissions->pluck('name') // Get permissions names
            ];
        });

        return response()->json($response, 200);
    }
}
