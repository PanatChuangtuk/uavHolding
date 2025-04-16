<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use App\Models\{Member, MemberInfo, MemberToGroup, MemberGroup};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\{Hash, Auth, Validator, Http};
use Carbon\Carbon;
use App\Enum\IsSourceEnum;

class MemberController extends Controller
{
    private $main_menu = 'customer';
    public function index(Request $request)
    {
        $main_menu = $this->main_menu;
        $query = $request->input('query');
        $status = $request->input('status');
        $memberGroupFilter = $request->input('member_group');
        $accountType = $request->input('account');

        $memberQuery = Member::with(['info', 'memberGroups']);

        if ($query) {
            $memberQuery->where(function ($q) use ($query) {
                $q->where('username', 'LIKE', "%{$query}%")
                    ->orWhere('email', 'LIKE', "%{$query}%")
                    ->orWhere('mobile_phone', 'LIKE', "%{$query}%")
                    ->orWhereHas('info', function ($subQuery) use ($query) {
                        $subQuery->where('first_name', 'LIKE', "%{$query}%")
                            ->orWhere('last_name', 'LIKE', "%{$query}%");
                    });
            });
        }

        if ($status) {
            $statusValue = ($status === 'active') ? 1 : 0;
            $memberQuery->where('status', $statusValue);
        }

        if ($memberGroupFilter) {
            $memberQuery->whereHas('memberGroups', function ($query) use ($memberGroupFilter) {
                $query->where('name', $memberGroupFilter);
            });
        }

        if ($accountType) {
            $memberQuery->whereHas('info', function ($query) use ($accountType) {
                $query->where('account_type', $accountType);
            });
        }

        $members = $memberQuery->paginate(10)->appends([
            'query' => $query,
            'status' => $status,
            'member_group' => $memberGroupFilter,
            'account' => $accountType,
        ]);

        $memberGroups = MemberGroup::where('status', 1)->get();

        return view('administrator.member.index', compact(
            'members',
            'query',
            'status',
            'memberGroups',
            'memberGroupFilter',
            'accountType',
            'main_menu'
        ));
    }

    public function add()
    {
        $main_menu = $this->main_menu;
        $memberGroups = MemberGroup::where('status', 1)->get();
        return view('administrator.member.add', compact('main_menu', 'memberGroups'));
    }
    public function submit(Request $request)
    {
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:member',
            'password' => 'required|string|min:8|confirmed',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'mobile_phone' => 'required|digits:10|unique:member,mobile_phone',
            'company' => 'nullable|string|max:255',
            'line_id' => 'nullable|string|max:50',
            'vat_register_number' => 'nullable|string|max:13',
            'account_type' => 'required|in:government,private',
        ], [
            'username.required' => 'กรุณากรอกชื่อผู้ใช้',
            'username.max' => 'ชื่อผู้ใช้ต้องไม่เกิน 255 ตัวอักษร',
            'email.required' => 'กรุณากรอกอีเมล',
            'email.email' => 'กรุณากรอกอีเมลที่ถูกต้อง',
            'email.unique' => 'อีเมลนี้ถูกใช้งานแล้ว',
            'password.required' => 'กรุณากรอกรหัสผ่าน',
            'password.min' => 'รหัสผ่านต้องมีความยาวอย่างน้อย 8 ตัวอักษร',
            'password.confirmed' => 'รหัสผ่านไม่ตรงกัน',
            'first_name.required' => 'กรุณากรอกชื่อจริง',
            'last_name.required' => 'กรุณากรอกนามสกุล',
            'account_type.required' => 'กรุณาเลือกประเภทบัญชี',
            'mobile_phone.required' => 'กรุณากรอกหมายเลขโทรศัพท์มือถือ',
            'mobile_phone.unique' => 'หมายเลขโทรศัพท์มือถือถูกใช้แล้ว',
            'mobile_phone.digits' => 'หมายเลขโทรศัพท์มือถือต้องมี 10 หลัก',
            'vat_register_number.max' => 'หมายเลขทะเบียนภาษีต้องไม่เกิน 13 หลัก'
        ]);
        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput($request->except('password', 'password_confirmation'));
        }
        $user = Member::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'mobile_phone' => $request->mobile_phone,
            'is_source' => IsSourceEnum::Register->value,
            'created_at' => Carbon::now(),
            'created_by' => Auth::check() ? Auth::user()->id : null
        ]);
        MemberInfo::create([
            'member_id' => $user->id,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'company' => $request->company,
            'line_id' => $request->line_id,
            'vat_register_number' => $request->vat_number,
            'account_type' => $request->account_type,
        ]);
        MemberToGroup::create([
            'member_id' => $user->id,
            'member_group_id' =>  $request->customer_group,
        ]);


        return redirect()->back()->with('success', 'ข้อมูลถูกสร้างเรียบร้อยแล้ว');
    }

    public function edit($id)
    {
        $main_menu = $this->main_menu;
        $member = Member::with(['info', 'memberGroups'])->findOrFail($id);
        $memberGroups = MemberGroup::where('status', 1)->get();

        return view('administrator.member.edit', compact('member', 'memberGroups', 'main_menu'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:member,email,' . $id,
            'mobile_phone' => 'nullable|string|max:20',
            'vat_number' => 'nullable|string|max:20',
            'customer_group' => 'required|exists:member_group,id',
            'account_type' => 'required|in:Government,Private',
        ]);

        try {
            DB::beginTransaction();

            $member = Member::findOrFail($id);

            $status = $request->has('status') ? 1 : 0;

            $member->update([
                'email' => $request->email,
                'mobile_phone' => $request->mobile_phone,
                'status' => $status,
                'updated_by' => auth()->id()

            ]);

            $memberInfo = $member->info;
            if (!$memberInfo) {
                $memberInfo = new MemberInfo([
                    'member_id' => $id
                ]);
            }

            $memberInfo->fill([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'vat_register_number' => $request->vat_number,
                'account_type' => strtolower($request->account_type),
                'updated_by' => auth()->id()
            ]);
            $memberInfo->save();

            $member->memberGroups()->detach();
            $member->memberGroups()->attach($request->customer_group);

            DB::commit();

            return redirect()->route('administrator.member')
                ->with('success', 'Member updated successfully.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'An error occurred while updating the member. ' . $e->getMessage());
        }
    }

    public function destroy(Request $request, $id)
    {
        $member = Member::findOrFail($id);
        $member->forceDelete();

        $currentPage = $request->query('page', 1);

        return redirect()->route('administrator.member', ['page' => $currentPage])->with([
            'success' => 'Member deleted successfully!',
            'id' => $id
        ]);
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids');

        if (is_array($ids) && count($ids) > 0) {
            Member::whereIn('id', $ids)->forceDelete();

            return response()->json([
                'status' => 'success',
                'message' => 'Selected members have been deleted successfully.',
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
        $member = Member::findOrFail($id);
        $member->status = $member->status == 1 ? 0 : 1;
        $member->save();

        return back()->with('success', 'Member status updated successfully.');
    }
}
