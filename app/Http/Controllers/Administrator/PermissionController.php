<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PermissionController extends Controller
{
    private $main_menu = 'admin';

    public function index()
    {
        $roles = Role::where('id', '!=', 1)->get();
        $permissions = Permission::all();
        $main_menu = $this->main_menu;
        return view('administrator.permissions.index', compact('roles', 'permissions', 'main_menu'));
    }

    public function update(Request $request)
    {
        $permissions = $request->input('permissions', []);

        // Log::info('Permissions update request received', ['permissions' => $permissions]);

        try {
            DB::beginTransaction();

            $roles = Role::where('id', '!=', 1)->get();
            foreach ($roles as $role) {
                $role->permissions()->detach();
            }

            // Sync permissions for roles except super admin
            foreach ($permissions as $permissionId => $roleIds) {
                $permission = Permission::find($permissionId);

                if ($permission) {
                    $filteredRoleIds = array_filter($roleIds, function($roleId) {
                        return $roleId != 1;
                    });

                    // Log::info('Syncing permissions', ['permission_id' => $permissionId, 'role_ids' => $filteredRoleIds]);

                    $permission->roles()->sync($filteredRoleIds);
                }
            }

            $superAdminRole = Role::find(1);
            if ($superAdminRole) {
                $allPermissions = Permission::all()->pluck('id')->toArray();

                // Log::info('Syncing all permissions for super admin', ['role_id' => 1, 'permissions' => $allPermissions]);

                $superAdminRole->permissions()->sync($allPermissions);
            }

            DB::commit();
            return response()->json([
                'status' => 'success',
                'title' => 'Permissions Updated',
                'message' => 'Permissions have been updated successfully.'
            ]);
        } catch (\Exception $e) {
            DB::rollback();

            // Log::error('Failed to update permissions', ['error' => $e->getMessage()]);

            return response()->json([
                'status' => 'error',
                'title' => 'Error',
                'message' => 'Failed to update permissions: ' . $e->getMessage()
            ], 500);
        }
    }   
}