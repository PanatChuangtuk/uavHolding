<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use App\Models\{Language, TestimonialContent, Testimonial};
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, Validator, Storage};

class TestimonialController extends Controller
{
    private $main_menu = 'contents';
    public function index(Request $request)
    {
        $main_menu = $this->main_menu;
        $query = $request->input('query');
        $status = $request->input('status');

        $testimonialQuery = Testimonial::with('content');

        if ($query) {
            $testimonialQuery->where('name', 'LIKE', "%{$query}%");
        }

        if ($status) {
            $statusValue = ($status === 'active') ? 1 : 0;
            $testimonialQuery->where('status', $statusValue);
        }

        $testimonial = $testimonialQuery->paginate(10)->appends([
            'query' => $query,
            'status' => $status,
        ]);
        return view('administrator.testimonial.index', compact('testimonial', 'query', 'status', 'main_menu'));
    }

    public function add()
    {
        $main_menu = $this->main_menu;
        $language = Language::get();
        return view('administrator.testimonial.add', compact('language', 'main_menu'));
    }

    public function edit($id)
    {
        $main_menu = $this->main_menu;
        $language = Language::all();
        $testimonial = Testimonial::find($id);
        $testimonialContent = TestimonialContent::where('testimonial_id', $testimonial->id)->get()->keyBy('language_id');

        return view('administrator.testimonial.edit', compact('testimonial', 'testimonialContent', 'language', 'main_menu'));
    }

    public function submit(Request $request)
    {
        $languages = Language::all();
        $nameArray = $request->input('name');
        $pofilePosition = $request->input('profile_position');
        $profileNameArray = $request->input('profile_name');
        $status = $request->input('status', 0);
        $contentArray = $request->input('content');
        $createdAt = Carbon::now();
        $createdBy = Auth::user()->id;

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
        $rules['profile_image'] = 'required';
        $messages['profile_image.required'] = 'กรุณาใส่รูปภาพ';
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        $profileImageNames = null;
        if ($request->hasFile('profile_image')) {
            $profileImageNames = $this->uploadsImage($request->file('profile_image'), 'testimonial');
        }
        $testimonial = Testimonial::create([
            'name' => $nameArray[1] ?? $nameArray[2],
            'profile_image' => $profileImageNames,
            'status' => $status,
            'created_at' => $createdAt,
            'created_by' => $createdBy
        ]);

        foreach ($languages as $language) {
            TestimonialContent::create([
                'testimonial_id' => $testimonial->id,
                'language_id' => $language->id,
                'name' => $nameArray[$language->id] ?? null,
                'profile_name' => $pofilePosition[$language->id] ?? null,
                'profile_position' => $profileNameArray[$language->id] ?? null,
                'content' => $contentArray[$language->id] ?? null,
            ]);
        }
        return redirect()->back()->with('success', 'ข้อมูลถูกสร้างเรียบร้อยแล้ว');
    }

    public function update(Request $request, $id)
    {
        $languages = Language::all();
        $nameArray = $request->input('name');
        $pofilePosition = $request->input('profile_position');
        $profileNameArray = $request->input('profile_name');
        $contentArray = $request->input('content');
        $updatedBy = Auth::user()->id;
        $testimonial = Testimonial::find($id);
        $status = $request->input('status', 0);
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
        $rules['profile_image'] = $testimonial->profile_image ? 'nullable' : 'required';
        $messages['profile_image.required'] = 'กรุณาใส่รูปภาพ';
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        $profileImageName = $testimonial->profile_image;
        if ($request->hasFile('profile_image')) {
            $filename = $this->uploadsImage($request->file('profile_image'), 'testimonial');
            if (isset($testimonial) && $testimonial->profile_image !== $filename) {
                $oldImagePath = str_replace(asset('public'), 'file/testimonial/', $testimonial->profile_image);
                $relativeUrl = ltrim(str_replace(url(''), '', $oldImagePath), '/');
                Storage::disk('public')->delete('file/testimonial/' . $relativeUrl);

                $testimonial->update([
                    'name' => $nameArray[1] ?? $nameArray[2],
                    'profile_image' => $filename,
                    'status' => $status,
                    'updated_by' => $updatedBy
                ]);
            }
        } else {
            $testimonial->update([
                'name' => $nameArray[1] ?? $nameArray[2],
                'profile_image' => $profileImageName,
                'status' => $status,
                'updated_by' => $updatedBy
            ]);
        }

        foreach ($languages as $language) {
            $testimonialContent = TestimonialContent::where('testimonial_id', $testimonial->id)
                ->where('language_id', $language->id)
                ->first();

            if ($testimonialContent) {
                $testimonialContent->update([
                    'name' => $nameArray[$language->id] ?? null,
                    'profile_name' => $profileNameArray[$language->id] ?? null,
                    'profile_position' => $pofilePosition[$language->id] ?? null,
                    'content' => $contentArray[$language->id] ?? null,
                ]);
            }
        }
        return redirect()->back()->with('success', 'ข้อมูลถูกบันทึกเรียบร้อยแล้ว');
    }

    public function destroy($id, Request $request)
    {
        $testimonial = Testimonial::findOrFail($id);
        $testimonial->delete();

        $currentPage = $request->query('page', 1);

        return redirect()->route('administrator.testimonial', ['page' => $currentPage])->with([
            'success' => 'Testimonial deleted successfully!',
            'id' => $id
        ]);
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids');

        if (is_array($ids) && count($ids) > 0) {
            Testimonial::whereIn('id', $ids)->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Selected testimonial have been deleted successfully.',
                'deleted_ids' => $ids
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'No testimonial selected for deletion.'
        ], 400);
    }

    public function deleteImage($id)
    {
        $testimonial = Testimonial::find($id);

        if ($testimonial) {
            $oldImagePath = str_replace(asset('public'), 'file/testimonial/', $testimonial->profile_image);

            if (Storage::disk('public')->exists('file/testimonial/' . $oldImagePath)) {
                Storage::disk('public')->delete('file/testimonial/' . $oldImagePath);
            }

            $testimonial->update([
                'profile_image' => null,
                'updated_by' => Auth::user()->id
            ]);

            return response()->json(['success' => 'Image deleted successfully']);
        }
    }
}
