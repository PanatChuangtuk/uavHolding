<?php

namespace App\Http\Controllers\Administrator;

use App\Models\Language;
use App\Http\Controllers\Controller;
use App\Models\Brand;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, Validator, Storage};


class BrandController extends Controller
{
    private $main_menu = 'website';
    public function index(Request $request)
    {
        $main_menu = $this->main_menu;
        $query = $request->input('query');
        $status = $request->input('status');

        $brandQuery = Brand::query();

        if ($query) {
            $brandQuery->where('name', 'LIKE', "%{$query}%");
        }

        if ($status) {
            $statusValue = ($status === 'active') ? 1 : 0;
            $brandQuery->where('status', $statusValue);
        }

        $brand = $brandQuery->paginate(10)->appends([
            'query' => $query,
            'status' => $status,
        ]);
        return view('administrator.brand.index', compact('brand', 'query', 'status', 'main_menu'));
    }

    public function add()
    {
        $main_menu = $this->main_menu;
        return view('administrator.brand.add', compact('main_menu'));
    }

    public function edit($id)
    {
        $main_menu = $this->main_menu;
        $brand = Brand::find($id);
        return view('administrator.brand.edit', compact('brand', 'main_menu'));
    }

    public function submit(Request $request)
    {
        $name = $request->input('name');
        $url = $request->input('url');
        $status = $request->input('status', 0);
        $filename = null;

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'image' => 'required'
        ], [
            'name.required' => 'กรุณากรอกชื่อ',
            'image.required' => 'กรุณาใส่รูปภาพ'
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        if ($request->hasFile('image')) {
            $filename = $this->uploadsImage($request->file('image'), 'brand');
        }

        Brand::create([
            'name' => $name,
            'url' => $url,
            'image' => $filename,
            'status' => $status,
            'created_at' => Carbon::now(),
            'created_by' => Auth::user()->id
        ]);

        return redirect()->back()->with('success', 'ข้อมูลถูกสร้างเรียบร้อยแล้ว');
    }

    public function update(Request $request, $id)
    {
        $brand = Brand::find($id);
        $name = $request->input('name');
        $url = $request->input('url');
        $status = $request->input('status', 0);
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'image' => $brand->image ? 'nullable' : 'required',
        ], [
            'name.required' => 'กรุณากรอกชื่อ',
            'image.required' => 'กรุณาใส่รูปภาพ'
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }
        $filename = $brand->image;

        if ($request->hasFile('image')) {
            $filename = $this->uploadsImage($request->file('image'), 'brand');
            if ($brand->image) {
                Storage::disk('public')->delete('file/brand/' . $brand->image);
            }
        }

        $brand->update([
            'name' => $name,
            'url' => $url,
            'image' => $filename,
            'status' => $status,
            'updated_by' => Auth::user()->id,
        ]);

        return redirect()->back()->with('success', 'ข้อมูลถูกบันทึกเรียบร้อยแล้ว');
    }

    public function destroy($id, Request $request)
    {
        $brand = Brand::findOrFail($id);
        $brand->delete();

        $currentPage = $request->query('page', 1);

        return redirect()->route('administrator.brand', ['page' => $currentPage])->with([
            'success' => 'Brand deleted successfully!',
            'id' => $id
        ]);
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids');

        if (is_array($ids) && count($ids) > 0) {
            Brand::whereIn('id', $ids)->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Selected brand have been deleted successfully.',
                'deleted_ids' => $ids
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'No brand selected for deletion.'
        ], 400);
    }

    public function deleteImage($id)
    {
        $brand = Brand::find($id);

        if ($brand) {
            $oldImagePath = str_replace(asset('public'), 'file/brand/', $brand->image);

            if (Storage::disk('public')->exists('file/brand/' . $oldImagePath)) {
                Storage::disk('public')->delete('file/brand/' . $oldImagePath);
            }

            $brand->update([
                'image' => null,
                'updated_by' => Auth::user()->id
            ]);

            return response()->json(['success' => 'Image deleted successfully']);
        }
    }
}
