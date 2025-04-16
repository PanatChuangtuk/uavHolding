<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use App\Models\Language;
use App\Models\AboutContent;
use App\Models\About;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, Validator};
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use App\Enum\AboutEnum;

class MilestoneController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->input('query');
        $status = $request->input('status');

        $aboutQuery = About::with('content');

        if ($query) {
            $aboutQuery->where('name', 'LIKE', "%{$query}%");
        }

        if ($status) {
            $statusValue = ($status === 'active') ? 1 : 0;
            $aboutQuery->where('status', $statusValue);
        }

        $about = $aboutQuery->paginate(10)->appends([
            'query' => $query,
            'status' => $status,
        ]);

        return view('administrator.about.index', compact('about', 'query', 'status'));
    }

    public function add()
    {
        $language = Language::get();
        $aboutOptions = AboutEnum::cases();
        return view('administrator.milestone.add', compact('language', 'aboutOptions'));
    }

    public function edit($id)
    {
        $languages = Language::all();
        $aboutOptions = AboutEnum::cases();
        $about = About::find($id);
        $aboutContent = AboutContent::where('about_id', $about->id)->get()->keyBy('language_id');

        return view('administrator.milestone.edit', compact('about', 'aboutContent', 'languages', 'aboutOptions'));
    }

    public function submit(Request $request)
    {
        $languages = Language::all();
        $nameArray = $request->input('name');
        $type = $request->input('about_type');
        $iconName = $request->input('icon');
        $status = $request->input('status', 0);
        $contentArray = $request->input('content');
        $descriptionArray = $request->input('description');
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

        $iconName = null;
        if ($request->hasFile('icon')) {
            $iconName = $this->uploadsImage($request->file('icon'), 'milestone');
        }
        $about = About::create([
            'name' => $nameArray[1] ?? $nameArray[2],
            'type' => $type,
            'icon' => $iconName,
            'status' => $status,
            'created_at' => $createdAt,
            'created_by' => $createdBy
        ]);

        foreach ($languages as $language) {
            AboutContent::create([
                'about_id' => $about->id,
                'language_id' => $language->id,
                'name' => $nameArray[$language->id] ?? null,
                'content' => $contentArray[$language->id] ?? null,
                'description' => $descriptionArray[$language->id] ?? null,
            ]);
        }
        return redirect()->route('administrator.milestone');
    }

    public function update(Request $request, $id)
    {
        $languages = Language::all();
        $nameArray = $request->input('name');
        $type = $request->input('about_type');
        $status = $request->input('status', 0);
        $contentArray = $request->input('content');
        $descriptionArray = $request->input('description');
        $updatedBy = Auth::user()->id;
        $about = About::find($id);
        $iconName = $about->icon;
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
        if ($request->hasFile('icon')) {
            $filename = $this->uploadsImage($request->file('icon'), 'milestone');
            if (isset($about) && $about->icon !== $filename) {
                $oldImagePath = str_replace(asset('public'), 'file/milestone/', $about->icon);
                $relativeUrl = ltrim(str_replace(url(''), '', $oldImagePath), '/');
                Storage::disk('public')->delete('file/milestone/' . $relativeUrl);
                $about->update([
                    'name' => $nameArray[1] ?? $nameArray[2],
                    'type' => $type,
                    'icon' => $filename,
                    'status' => $status,
                    'updated_by' => $updatedBy
                ]);
            }
        } else {
            $about->update([
                'name' => $nameArray[1] ?? $nameArray[2],
                'type' => $type,
                'icon' => $iconName,
                'status' => $status,
                'updated_by' => $updatedBy
            ]);
        }

        foreach ($languages as $language) {
            $aboutContent = AboutContent::where('about_id', $about->id)
                ->where('language_id', $language->id)
                ->first();
            if ($aboutContent) {
                $aboutContent->update([
                    'name' => $nameArray[$language->id] ?? null,
                    'content' => $contentArray[$language->id] ?? null,
                    'description' => $descriptionArray[$language->id] ?? null,
                ]);
            } else {
                AboutContent::create([
                    'about_id' => $about->id,
                    'language_id' => $language->id,
                    'name' => $nameArray[$language->id] ?? null,
                    'description' => $descriptionArray[$language->id] ?? null,
                    'content' => $contentArray[$language->id] ?? null,
                ]);
            }
        }

        return redirect()->route('administrator.milestone');
    }

    public function deleteImage($id)
    {
        $about = About::find($id);

        if ($about) {
            $oldImagePath = str_replace(asset('public'), 'file/milestone/', $about->icon);
            $relativeUrl = ltrim(str_replace(url(''), '', $oldImagePath), '/');

            if (Storage::disk('public')->exists('file/milestone/' . $relativeUrl)) {
                Storage::disk('public')->delete('file/milestone/' . $relativeUrl);
            }

            $about->update([
                'icon' => null,
                'updated_by' => Auth::user()->id
            ]);

            return response()->json(['success' => 'Image deleted successfully']);
        }
    }
}
