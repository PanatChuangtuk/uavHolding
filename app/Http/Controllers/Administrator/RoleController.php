<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    private $main_menu = 'admin';
    public function index(Request $request)
    {
        $query = $request->input('query');
        $statusFilter = $request->input('status');

        $roles = Role::withCount('users')
            ->when($query, function ($q) use ($query) {
                $q->where('name', 'LIKE', "%{$query}%");
            })
            ->when($statusFilter, function ($q) use ($statusFilter) {
                if ($statusFilter == 'active') {
                    $q->where('status', 1);
                } elseif ($statusFilter == 'inactive') {
                    $q->where('status', 0);
                }
            })
            ->paginate(10);

        $permissions = Permission::all();
        $main_menu = $this->main_menu;

        return view('administrator.roles.index', compact('roles', 'permissions', 'query', 'main_menu', 'statusFilter'));
    }

    public function updatePermissions(Request $request)
    {
        $request->validate([
            'permissions' => 'array',
        ]);

        foreach ($request->input('permissions') as $roleId => $perms) {
            $role = Role::findOrFail($roleId);
            $role->permissions()->sync($perms);
        }

        return redirect()->route('administrator.roles.index')->with('success', 'Permissions updated successfully.');
    }


    public function add() 
    {
        $main_menu = $this->main_menu;
        return view('administrator.roles.add', compact('main_menu'));
    }

    public function submit(Request $request) 
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'nullable|boolean',
        ]);

        Role::create([
            'name' => $request->name,
            'status' => $request->has('status') ? 1 : 0,
        ]);

        return redirect()->route('administrator.roles')->with('success', 'Role created successfully.');
    }

    public function edit($id)
    {
        $main_menu = $this->main_menu;
        $role = Role::findOrFail($id);

        return view('administrator.roles.edit', compact('role', 'main_menu'));
    }

    public function update(Request $request, $id)
    {
        $role = Role::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'nullable|boolean',
        ]);

        $role->update([
            'name' => $request->name,
            'status' => $request->has('status') ? 1 : 0,
        ]);

        return redirect()->route('administrator.roles')->with('success', 'Role updated successfully.');
    }

    public function destroy($id, Request $request)
    {
        $role = Role::findOrFail($id);
        $role->delete();

        $currentPage = $request->query('page', 1);

        return redirect()->route('administrator.roles', ['page' => $currentPage])->with([
            'success' => 'Banner deleted successfully!',
            'id' => $id
        ]);
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids');

        if (is_array($ids) && count($ids) > 0) {
            Role::whereIn('id', $ids)->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Selected roles have been deleted successfully.',
                'deleted_ids' => $ids
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'No roles selected for deletion.'
        ], 400);
    }
}
