<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use App\Models\MemberGroup;
use Illuminate\Http\Request;

class MemberGroupController extends Controller
{
    private $main_menu = 'customer';
    public function index(Request $request)
    {
        $query = $request->input('query');
        $status = $request->input('status');

        $memberGroups = MemberGroup::query()
            ->when($query, function ($q) use ($query) {
                $q->where('name', 'LIKE', "%{$query}%")
                    ->orWhere('code', 'LIKE', "%{$query}%");
            })
            ->when($status, function ($q) use ($status) {
                $q->where('status', $status);
            })
            ->paginate(10);
        $main_menu = $this->main_menu;
        return view('administrator.group-member.index', compact('memberGroups', 'main_menu'));
    }

    public function create()
    {
        $main_menu = $this->main_menu;
        return view('administrator.group-member.add', compact('main_menu'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:255|unique:member_group',
            'status' => 'required|boolean',
        ]);

        MemberGroup::create([
            'name' => $request->name,
            'code' => $request->code,
            'status' => $request->status,
            'created_by' => auth()->id(),
        ]);

        return redirect()->route('administrator.group-member')->with('success', 'Member Group created successfully.');
    }

    public function edit($id)
    {
        $main_menu = $this->main_menu;
        $memberGroup = MemberGroup::findOrFail($id);
        return view('administrator.group-member.edit', compact('memberGroup', 'main_menu'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'boolean',
        ]);

        $memberGroup = MemberGroup::findOrFail($id);
        $memberGroup->update([
            'status' => $request->has('status') ? 1 : 0,
            'updated_by' => auth()->id(),
        ]);

        return redirect()->route('administrator.group-member')
            ->with('success', 'Member Group updated successfully.');
    }

    public function destroy($id)
    {
        $memberGroup = MemberGroup::findOrFail($id);
        $memberGroup->forceDelete();

        return redirect()->route('administrator.group-member')->with('success', 'Member Group deleted successfully.');
    }
}
