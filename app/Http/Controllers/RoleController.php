<?php

namespace App\Http\Controllers;

use App\Http\Requests\RoleRequest;
use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function storeRole(RoleRequest $request)
    {
        $field = $request->validated();

        $role = Role::create([
            'name' => $field['name']->name,
        ]);

        return response([
            'message' => 'Role created successfully',
            'role' => $role,
        ]);
    }
}
