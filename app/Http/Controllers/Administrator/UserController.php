<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\{Hash, Auth, Validator};
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

use App\Models\{User, Role};

use Illuminate\Http\Request;

class UserController extends Controller
{
    private $main_menu = 'admin';
    public function index(Request $request)
    {
        $query = $request->input('query');
        $roleFilter = $request->input('admin');
        $statusFilter = $request->input('status');

        $userQuery = User::with('role');

        if ($query) {
            $userQuery->where('name', 'LIKE', "%{$query}%");
        }

        if ($roleFilter) {
            $userQuery->whereHas('role', function ($q) use ($roleFilter) {
                $q->where('name', $roleFilter);
            });
        }

        if ($statusFilter) {
            if ($statusFilter == 'active') {
                $userQuery->where('status', 1);
            } elseif ($statusFilter == 'inactive') {
                $userQuery->where('status', 0); 
            }
        }

        $users = $userQuery->paginate(10)->appends([
            'query' => $query,
            'admin' => $roleFilter,
            'status' => $statusFilter,
        ]);
        $roles = Role::where('status', 1)->get();
        $main_menu = $this->main_menu;

        return view('administrator.users.index', compact('users', 'roles', 'query', 'main_menu', 'statusFilter'));
    }

    public function add()
    {
        $main_menu = $this->main_menu;
        $roles = Role::all();
        return view('administrator.users.add', compact('roles', 'main_menu'));
    }

    public function submit(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
            'role_id' => 'required|exists:roles,id',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp,bmp|max:2048',
        ]);

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->role_id = $request->role_id;
        $user->status = $request->has('status') ? 1 : 0;

        if ($request->hasFile('profile_image')) {
            $image = $request->file('profile_image');
            $filename = 'profile_' . time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('upload/profiles/'), $filename);
            
            $user->profile_image = 'profiles/' . $filename;
        }

        $user->save();

        return redirect()->route('administrator.users')->with('success', 'User created successfully.');
    }

    public function edit($id)
    {
        $main_menu = $this->main_menu;
        $user = User::findOrFail($id);
        $roles = Role::all(); 
        return view('administrator.users.edit', compact('user', 'main_menu', 'roles'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'role_id' => 'required|exists:roles,id',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp,bmp|max:2048',
            'status' => 'nullable|boolean',
            'password' => 'nullable|min:8|confirmed',
        ]);

        try {
            DB::beginTransaction();

            $user = User::findOrFail($id);

            $user->name = $request->name;
            $user->email = $request->email;
            $user->role_id = $request->role_id;
            $user->status = $request->has('status') ? 1 : 0;

            if ($request->hasFile('profile_image')) {
                if ($user->profile_image && file_exists(public_path('upload/profiles/' . $user->profile_image))) {
                    unlink(public_path('upload/profiles/' . $user->profile_image));
                }

                $image = $request->file('profile_image');
                $filename = 'profile_' . $id . '_' . time() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('upload/profiles/'), $filename);

                $user->profile_image = 'profiles/' . $filename;
            }

            if ($request->filled('password')) {
                $user->password = Hash::make($request->password);
            }

            $user->save();
            DB::commit();

            return redirect()->route('administrator.users')->with('success', 'User updated successfully.');
        } catch (\Exception $e) {
            DB::rollback();

            \Log::error('Error updating user: ' . $e->getMessage());

            return back()->with('error', 'An error occurred while updating the user. Please try again.');
        }
    }

    public function destroy($id, Request $request)
    {
        $user = User::findOrFail($id);

        $role = $user->role;
        $user->delete();

        if ($role) {
            $role->decrement('user_count');
        }

        $currentPage = $request->query('page', 1);

        return redirect()->route('administrator.users', ['page' => $currentPage])->with([
            'success' => 'User deleted successfully!',
            'id' => $id
        ]);
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids');

        if (is_array($ids) && count($ids) > 0) {
            $users = User::whereIn('id', $ids)->with('role')->get();

            foreach ($users as $user) {
                $role = $user->role;
                $user->delete();

                if ($role) {
                    $role->decrement('user_count');
                }
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Selected users have been deleted successfully.',
                'deleted_ids' => $ids
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'No users selected for deletion.'
        ], 400);
    }

    public function toggleStatus($id)
    {
        $user = User::findOrFail($id);

        if ($user->role === 'superadmin') {
            return redirect()->route('administrator.users')->with('error', 'You cannot disable a Super Admin.');
        }

        $user->status = !$user->status;
        $user->save();

        return redirect()->route('administrator.users')->with('success', 'User status updated successfully.');
    }
}
