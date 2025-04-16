<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use App\Models\{Language, Catalog, CatalogContent};
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, Storage, Validator};
use Illuminate\Support\Str;

class CatalogController extends Controller
{
    private $main_menu = 'website';
    public function index(Request $request)
    {
        $main_menu = $this->main_menu;
        $query = $request->input('query');
        $status = $request->input('status');

        $catalogQuery = Catalog::with('content');

        if ($query) {
            $catalogQuery->where('name', 'LIKE', "%{$query}%");
        }

        if ($status) {
            $statusValue = ($status === 'active') ? 1 : 0;
            $catalogQuery->where('status', $statusValue);
        }

        $catalog = $catalogQuery->paginate(10)->appends([
            'query' => $query,
            'status' => $status,
        ]);
        return view('administrator.catalog.index', compact('catalog', 'query', 'status', 'main_menu'));
    }

    public function add()
    {
        $main_menu = $this->main_menu;
        $language = Language::get();
        return view('administrator.catalog.add', compact('language', 'main_menu'));
    }

    public function edit($id)
    {
        $main_menu = $this->main_menu;
        $languages = Language::all();
        $catalog = Catalog::find($id);
        $catalogContent = CatalogContent::where('catalog_id', $catalog->id)->get()->keyBy('language_id');

        return view('administrator.catalog.edit', compact('catalog', 'catalogContent', 'languages', 'main_menu'));
    }

    public function submit(Request $request)
    {
        $languages = Language::all();
        $nameArray = $request->input('name');
        $descriptionArray = $request->input('description');
        $status = $request->input('status', 0);
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
        $rules['image'] = 'required';
        $messages['image.required'] = 'กรุณาใส่รูปภาพ';
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        $imageNames = null;
        if ($request->hasFile('image')) {
            $imageNames = $this->uploadsImage($request->file('image'), 'catalog/image');
        }
        $catalog = Catalog::create([
            'name' => $nameArray[1] ?? $nameArray[2],
            'image' => $imageNames,
            'status' => $status,
            'created_at' => $createdAt,
            'created_by' => $createdBy
        ]);

        foreach ($languages as $language) {
            $fileNames = null;
            if ($request->hasFile('file.' . $language->id)) {
                $fileName = $request->file('file.' . $language->id);
                $fileNames = substr(Str::uuid(), 0, 5) . '.' . $fileName->getClientOriginalExtension();
                $fileName->storeAs('file/catalog/file/' . strtolower($language->code), $fileNames, 'public');
            }
            CatalogContent::create([
                'catalog_id' => $catalog->id,
                'language_id' => $language->id,
                'name' => $nameArray[$language->id] ?? null,
                'description' => $descriptionArray[$language->id] ?? null,
                'file' => $fileNames
            ]);
        }

        return redirect()->back()->with('success', 'ข้อมูลถูกสร้างเรียบร้อยแล้ว');
    }

    public function update(Request $request, $id)
    {
        $languages = Language::all();

        $nameArray = $request->input('name');
        $descriptionArray = $request->input('description');
        $status = $request->input('status', 0);
        $updatedBy = Auth::user()->id;
        $catalog = Catalog::find($id);
        $imageName = $catalog->image;

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
        $rules['image'] = $catalog->image ? 'nullable' : 'required';
        $messages['image.required'] = 'กรุณาใส่รูปภาพ';
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }



        if ($request->hasFile('image')) {
            $filename = $this->uploadsImage($request->file('image'), 'catalog/image');

            if (isset($catalog) && $catalog->image !== $filename) {
                $oldImagePath = str_replace(asset('public'), 'file/catalog/image/', $catalog->image);
                $relativeUrl = ltrim(str_replace(url(''), '', $oldImagePath), '/');
                Storage::disk('public')->delete('file/catalog/image/' . $relativeUrl);
            }

            $catalog->update([
                'name' => $nameArray[1] ?? $nameArray[2],
                'image' => $filename,
                'status' => $status,
                'updated_by' => $updatedBy
            ]);
        } else {
            $catalog->update([
                'name' => $nameArray[1] ?? $nameArray[2],
                'image' => $imageName,
                'status' => $status,
                'updated_by' => $updatedBy
            ]);
        }

        foreach ($languages as $language) {
            $catalogContent = CatalogContent::where('catalog_id', $catalog->id)
                ->where('language_id', $language->id)
                ->first();
            $fileNames = $catalogContent->file;
            if ($request->hasFile('file.' . $language->id)) {
                $fileName = $request->file('file.' . $language->id);
                $fileNames = substr(Str::uuid(), 0, 5) . '.' . $fileName->getClientOriginalExtension();
                $fileName->storeAs('file/catalog/file/' . strtolower($language->code), $fileNames, 'public');
                if (isset($catalogContent) && $catalogContent->file !== $fileNames) {
                    $oldImagePath = str_replace(asset('public'), 'file/catalog/file/' . strtolower($language->code), $catalogContent->file);
                    $relativeUrl = ltrim(str_replace(url(''), '', $oldImagePath), '/');
                    Storage::disk('public')->delete('file/catalog/file/' . strtolower($language->code) . '/' . $relativeUrl);
                }
            }
            $catalogContent->update([
                'name' => $nameArray[$language->id] ?? null,
                'description' => $descriptionArray[$language->id] ?? null,
                'file' => $fileNames
            ]);
        }
        return redirect()->back()->with('success', 'ข้อมูลถูกบันทึกเรียบร้อยแล้ว');
    }

    public function destroy($id, Request $request)
    {
        $catalog = Catalog::findOrFail($id);
        $catalog->delete();

        $currentPage = $request->query('page', 1);

        return redirect()->route('administrator.catalog', ['page' => $currentPage])->with([
            'success' => 'Catalog deleted successfully!',
            'id' => $id
        ]);
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids');

        if (is_array($ids) && count($ids) > 0) {
            Catalog::whereIn('id', $ids)->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Selected catalog have been deleted successfully.',
                'deleted_ids' => $ids
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'No catalog selected for deletion.'
        ], 400);
    }

    public function deleteImage($id)
    {
        $catalog = Catalog::find($id);
        if ($catalog) {
            $oldImagePath = str_replace(asset('public'), 'file/catalog/image', $catalog->image);

            if (Storage::disk('public')->exists('file/catalog/image/' . $oldImagePath)) {
                Storage::disk('public')->delete('file/catalog/image/' . $oldImagePath);
            }

            $catalog->update([
                'image' => null,
                'updated_at' => now(),
                'updated_by' => Auth::user()->id
            ]);
        }
        return response()->json(['success' => 'Image deleted successfully']);
    }

    public function deleteFile(Request $request, $id)
    {
        $languageId = $request->input('language_id');
        $language = Language::find($languageId);
        $catalog = Catalog::find($id);
        $catalogContent = CatalogContent::where('catalog_id', $catalog->id)
            ->where('language_id', $language->id)
            ->first();
        if ($catalogContent && $catalogContent->file) {
            $oldImagePath = str_replace(asset('public'), 'file/catalog/file/' . strtolower($language->code), $catalogContent->file);

            if (Storage::disk('public')->exists('file/catalog/file/' . strtolower($language->code) . '/' . $oldImagePath)) {
                Storage::disk('public')->delete('file/catalog/file/' . strtolower($language->code) . '/' . $oldImagePath);
            }
            $catalogContent->update([
                'file' => null,
                'updated_by' => Auth::user()->id
            ]);
            return response()->json(['success' => 'Image and language files deleted successfully']);
        }
    }
}
