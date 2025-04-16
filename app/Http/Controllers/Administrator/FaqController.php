<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use App\Models\{Language, FaqContent, Faq};
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, Validator};

class FaqController extends Controller
{
    private $main_menu = 'contents';
    public function index(Request $request)
    {
        $main_menu = $this->main_menu;
        $query = $request->input('query');
        $status = $request->input('status');

        $faqQuery = Faq::with('content');

        if ($query) {
            $faqQuery->where('name', 'LIKE', "%{$query}%");
        }

        if ($status) {
            $statusValue = ($status === 'active') ? 1 : 0;
            $faqQuery->where('status', $statusValue);
        }

        $faq = $faqQuery->paginate(10)->appends([
            'query' => $query,
            'status' => $status,
        ]);
        return view('administrator.faq.index', compact('faq', 'query', 'status', 'main_menu'));
    }

    public function add()
    {
        $main_menu = $this->main_menu;
        $language = Language::get();
        return view('administrator.faq.add', compact('language', 'main_menu'));
    }

    public function edit($id)
    {
        $main_menu = $this->main_menu;
        $language = Language::all();
        $faq = Faq::find($id);
        $faqContent = FaqContent::where('faq_id', $faq->id)->get()->keyBy('language_id');

        return view('administrator.faq.edit', compact('faq', 'faqContent', 'language', 'main_menu'));
    }

    public function submit(Request $request)
    {
        $languages = Language::all();
        $nameArray = $request->input('name');
        $contentArray = $request->input('content');
        $status = $request->input('status');
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
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        $about = Faq::create([
            'name' => $nameArray[1] ?? $nameArray[2],
            'status' => $status,
            'created_at' => $createdAt,
            'created_by' => $createdBy
        ]);

        foreach ($languages as $language) {
            FaqContent::create([
                'faq_id' => $about->id,
                'language_id' => $language->id,
                'name' => $nameArray[$language->id] ?? null,
                'content' => $contentArray[$language->id] ?? null,
            ]);
        }

        return redirect()->back()->with('success', 'ข้อมูลถูกสร้างเรียบร้อยแล้ว');
    }

    public function update(Request $request, $id)
    {
        $languages = Language::all();

        $nameArray = $request->input('name');
        $contentArray = $request->input('content');
        $status = $request->input('status');
        $updatedBy = Auth::user()->id;
        $faq = Faq::find($id);
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
        $faq->update([
            'name' => $nameArray[1] ?? $nameArray[2],
            'status' => $status,
            'updated_at' => now(),
            'updated_by' => $updatedBy
        ]);
        foreach ($languages as $language) {
            $faqContent = FaqContent::where('faq_id', $faq->id)
                ->where('language_id', $language->id)
                ->first();
            if ($faqContent) {
                $faqContent->update([
                    'name' => $nameArray[$language->id] ?? null,
                    'content' => $contentArray[$language->id] ?? null,
                ]);
            }
        }

        return redirect()->back()->with('success', 'ข้อมูลถูกบันทึกเรียบร้อยแล้ว');
    }

    public function destroy($id, Request $request)
    {
        $faq = Faq::findOrFail($id);
        $faq->delete();

        $currentPage = $request->query('page', 1);

        return redirect()->route('administrator.faq', ['page' => $currentPage])->with([
            'success' => 'FAQ deleted successfully!',
            'id' => $id
        ]);
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids');

        if (is_array($ids) && count($ids) > 0) {
            Faq::whereIn('id', $ids)->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Selected FAQ have been deleted successfully.',
                'deleted_ids' => $ids
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'No FAQ selected for deletion.'
        ], 400);
    }
}
