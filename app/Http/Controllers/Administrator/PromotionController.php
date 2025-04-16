<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use App\Models\{Language, PromotionContent, Promotion, Notification, Member};
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, Validator, Storage};
use App\Enum\NotificationType;

class PromotionController extends Controller
{
    private $main_menu = 'products';
    public function index(Request $request)
    {
        $main_menu = $this->main_menu;
        $query = $request->input('query');
        $status = $request->input('status');

        $promotionQuery = Promotion::with('content');

        if ($query) {
            $promotionQuery->where('name', 'LIKE', "%{$query}%");
        }

        if ($status) {
            $statusValue = ($status === 'active') ? 1 : 0;
            $promotionQuery->where('status', $statusValue);
        }

        $promotion = $promotionQuery->paginate(10)->appends([
            'query' => $query,
            'status' => $status,
        ]);
        return view('administrator.promotion.index', compact('promotion', 'query', 'status', 'main_menu'));
    }

    public function add()
    {
        $main_menu = $this->main_menu;
        $language = Language::get();
        return view('administrator.promotion.add', compact('language', 'main_menu'));
    }

    public function edit($id)
    {
        $main_menu = $this->main_menu;
        $languages = Language::all();
        $promotion = Promotion::find($id);
        $promotionContents = PromotionContent::where('promotion_id', $promotion->id)->get()->keyBy('language_id');
        return view('administrator.promotion.edit', compact('promotion', 'promotionContents', 'languages', 'main_menu'));
    }

    public function submit(Request $request)
    {
        $languages = Language::all();
        $nameArray = $request->input('name');
        $contentArray = $request->input('content');
        $descriptionArray = $request->input('description');
        $status = $request->input('status', 0);
        $createdAt = Carbon::now();
        $createdBy = Auth::user()->id;
        $promotionImageNames = null;

        $rules = [];
        $messages = [];
        foreach ($languages as $language) {
            $rules['name.' . $language->id] = 'required_without_all:name.' . implode(',', $languages->pluck('id')->toArray());
            if ($language->name == 'Thai') {
                $messages['name.' . $language->id . '.required_without_all'] = "กรุณากรอกชื่อสำหรับภาษาไทย";
            } elseif ($language->name == 'Eng') {
                $messages['name.' . $language->id . '.required_without_all'] = "กรุณากรอกชื่อสำหรับภาษาอังกฤษ";
            }
        }
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }
        if ($request->hasFile('image')) {
            $promotionImageNames = $this->uploadsImage($request->file('image'), 'promotion');
        }


        $promotion = Promotion::create([
            'name' => $nameArray[1] ?? $nameArray[2],
            'image' => $promotionImageNames,
            'status' => $status,
            'created_at' => $createdAt,
            'created_by' => $createdBy
        ]);

        foreach ($languages as $language) {
            PromotionContent::create([
                'promotion_id' => $promotion->id,
                'language_id' => $language->id,
                'name' => $nameArray[$language->id] ?? null,
                'content' => $contentArray[$language->id] ?? null,
                'description' => $descriptionArray[$language->id] ?? null,
            ]);
        }
        return redirect()->back()->with('success', 'ข้อมูลถูกสร้างเรียบร้อยแล้ว');
    }

    public function update(Request $request, $id)
    {
        $languages = Language::all();
        $nameArray = $request->input('name');
        $contentArray = $request->input('content');
        $descriptionArray = $request->input('description');
        $status = $request->input('status', 0);
        $updatedBy = Auth::user()->id;
        $promotion = Promotion::find($id);
        $rules = [];
        $messages = [];
        foreach ($languages as $language) {
            $rules['name.' . $language->id] = 'required_without_all:name.' . implode(',', $languages->pluck('id')->toArray());
            if ($language->name == 'Thai') {
                $messages['name.' . $language->id . '.required_without_all'] = "กรุณากรอกชื่อสำหรับภาษาไทย";
            } elseif ($language->name == 'Eng') {
                $messages['name.' . $language->id . '.required_without_all'] = "กรุณากรอกชื่อสำหรับภาษาอังกฤษ";
            }
        }
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }
        $promotionImageName = $promotion->image;
        if ($request->hasFile('image')) {
            $filename = $this->uploadsImage($request->file('image'), 'promotion');

            if (isset($promotion) && $promotion->image !== $filename) {
                $oldImagePath = str_replace(asset('public'), 'file/promotion/', $promotion->image);
                $relativeUrl = ltrim(str_replace(url(''), '', $oldImagePath), '/');
                Storage::disk('public')->delete('file/promotion/' . $relativeUrl);


                $promotion->update([
                    'name' => $nameArray[1] ?? $nameArray[2],
                    'image' => $filename,
                    'status' => $status,
                    'updated_at' => now(),
                    'update_by' => $updatedBy
                ]);
            }
        } else {
            $promotion->update([
                'name' => $nameArray[1] ?? $nameArray[2],
                'image' => $promotionImageName,
                'status' => $status,
                'updated_by' => $updatedBy
            ]);
        }
        foreach ($languages as $language) {
            $promotionContent = PromotionContent::where('promotion_id', $promotion->id)
                ->where('language_id', $language->id)
                ->first();

            if ($promotionContent) {
                $promotionContent->update([
                    'name' => $nameArray[$language->id] ?? null,
                    'description' => $descriptionArray[$language->id] ?? null,
                    'content' => $contentArray[$language->id] ?? null,
                ]);
            }
        }
        return redirect()->back()->with('success', 'ข้อมูลถูกบันทึกเรียบร้อยแล้ว');
    }

    public function destroy($id, Request $request)
    {
        $promotion = Promotion::findOrFail($id);
        $promotion->delete();

        $currentPage = $request->query('page', 1);

        return redirect()->route('administrator.promotion', ['page' => $currentPage])->with([
            'success' => 'Promotion deleted successfully!',
            'id' => $id
        ]);
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids');

        if (is_array($ids) && count($ids) > 0) {
            Promotion::whereIn('id', $ids)->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Selected promotion have been deleted successfully.',
                'deleted_ids' => $ids
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'No promotion selected for deletion.'
        ], 400);
    }

    public function deleteImage($id)
    {
        $promotion = Promotion::find($id);

        if ($promotion) {
            $oldImagePath = str_replace(asset('public'), 'file/promotion/', $promotion->image);
            $relativeUrl = ltrim(str_replace(url(''), '', $oldImagePath), '/');

            if (Storage::disk('public')->exists('file/promotion/' . $relativeUrl)) {
                Storage::disk('public')->delete('file/promotion/' . $relativeUrl);
            }

            $promotion->update(['image' => null, 'updated_at' => now(), 'updated_by' => Auth::user()->id]);

            return response()->json(['success' => 'Image deleted successfully']);
        }
    }
    public function notifications($id)
    {
        $member = Member::all();
        foreach ($member as $members) {
            Notification::create([
                'member_id' => $members->id,
                'module_id' => $id,
                'module_name' => NotificationType::promotion->value,
                'created_at' => Carbon::now()
            ]);
        }
        return response()->json(['success' => 'Notification successfully']);
    }
}
