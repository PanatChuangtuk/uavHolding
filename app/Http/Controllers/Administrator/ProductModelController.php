<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use App\Models\{ProductModel, ProductBrand, ProductType};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Carbon\Carbon;

class ProductModelController extends Controller
{
    private $main_menu = 'products';
    public function index(Request $request)
    {
        $main_menu = $this->main_menu;
        $query = $request->input('query');
        $status = $request->input('status');
        $brand = $request->input('brand');
        $type = $request->input('type') ? trim(strtolower($request->input('type'))) : null;
        $brand_type = ProductBrand::all();

        $product_types = ProductType::orderBy('sort')->get();
        $productModelQuery = ProductModel::with(['productBrand', 'productType']);

        if ($query) {
            $productModelQuery->where(function ($q) use ($query) {
                $q->where('name', 'LIKE', "%{$query}%")
                    ->orWhere('code', 'LIKE', "%{$query}%");
            });
        }

        if ($status) {
            $statusValue = ($status === 'active') ? 1 : 0;
            $productModelQuery->where('status', $statusValue);
        }

        if ($brand) {
            $productModelQuery->where('product_brand_id', $brand);
        }

        if ($type) {
            $productModelQuery->where('product_type_id', $type);
        }

        $productModels = $productModelQuery->paginate(10)->appends([
            'query' => $query,
            'status' => $status,
            'brand' => $brand,
            'type' => $type,
        ]);

        return view('administrator.product_model.index', compact('productModels', 'query', 'status', 'brand', 'type', 'main_menu', 'brand_type', 'product_types'));
    }

    public function add()
    {
        $main_menu = $this->main_menu;
        return view('administrator.product_model.add', compact('main_menu'));
    }

    public function edit($id)
    {
        $main_menu = $this->main_menu;
        $productModels = ProductModel::find($id);

        return view('administrator.product_model.edit', compact('productModels', 'main_menu'));
    }

    public function submit(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'types_id' => 'required',
            'brand_id' => 'required',
            // 'category_id' => 'required',
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|max:2048',
        ], [
            'types_id.required' => 'กรุณาเลือกประเภทสินค้า.',
            'brand_id.required' => 'กรุณาเลือกแบรนด์สินค้า.',
            // 'category_id.required' => 'กรุณาเลือกหมวดหมู่สินค้า.',
            'name.required' => 'กรุณากรอกชื่อสินค้า.',
            'name.string' => 'ชื่อสินค้าต้องเป็นข้อความ.',
            'name.max' => 'ชื่อสินค้าต้องมีความยาวไม่เกิน 255 ตัวอักษร.',
            'image.image' => 'ไฟล์ที่อัปโหลดต้องเป็นไฟล์รูปภาพ.',
            'image.max' => 'ขนาดไฟล์รูปภาพไม่เกิน 2MB.',
        ]);

        $filename = null;
        if ($request->hasFile('image')) {
            $filename = $this->uploadsImage($request->file('image'), 'model-product');
        }

        ProductModel::create([
            'product_type_id' => $request->input('types_id'),
            'product_brand_id' => $request->input('brand_id'),
            'product_category_id' => $request->input('category_id'),
            'name' => $request->input('name'),
            // 'code' => $request->input('code'),
            'description' => $request->input('description'),
            'image' => $filename,
            'status' => $request->input('status', 0),
            'created_at' => Carbon::now(),
            'created_by' => Auth::user()->id,
        ]);

        return redirect()->back()->with('success', 'ข้อมูลถูกสร้างเรียบร้อยแล้ว');
    }

    public function update(Request $request, $id)
    {
        // dd($request->all());
        $productModels = ProductModel::find($id);

        $request->validate([
            'types_id' => 'required',
            'brand_id' => 'required',
            // 'category_id' => 'required',
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|max:2048',
        ], [
            'types_id.required' => 'กรุณาเลือกประเภทสินค้า.',
            'brand_id.required' => 'กรุณาเลือกแบรนด์สินค้า.',
            // 'category_id.required' => 'กรุณาเลือกหมวดหมู่สินค้า.',
            'name.required' => 'กรุณากรอกชื่อสินค้า.',
            'name.string' => 'ชื่อสินค้าต้องเป็นข้อความ.',
            'name.max' => 'ชื่อสินค้าต้องมีความยาวไม่เกิน 255 ตัวอักษร.',
            'image.image' => 'ไฟล์ที่อัปโหลดต้องเป็นไฟล์รูปภาพ.',
            'image.max' => 'ขนาดไฟล์รูปภาพไม่เกิน 2MB.',
        ]);

        $filename = $productModels->image;
        if ($request->hasFile('image')) {
            $filename = $this->uploadsImage($request->file('image'), 'model-product');
        }

        $productModels->update([
            'product_type_id' => $request->input('types_id'),
            'product_brand_id' => $request->input('brand_id'),
            'product_category_id' => $request->input('category_id'),
            'name' => $request->input('name'),
            // 'code' => $request->input('code'),
            'description' => $request->input('description'),
            'image' => $filename,
            'status' => $request->input('status', 0),
            'updated_by' => Auth::user()->id,
        ]);
        return redirect()->back()->with('success', 'ข้อมูลถูกบันทึกเรียบร้อยแล้ว');
    }

    public function destroy($id, Request $request)
    {
        $productModels = ProductModel::findOrFail($id);
        if ($productModels->image) {
            Storage::disk('public')->delete('file/model-product/' . $productModels->image);
        }
        $productModels->delete();

        $currentPage = $request->query('page', 1);

        return redirect()->route('administrator.model-product', ['page' => $currentPage])->with('success', 'Product model deleted successfully!');
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids');

        if (is_array($ids) && count($ids) > 0) {
            $productModels = ProductModel::whereIn('id', $ids)->get();
            foreach ($productModels as $productModel) {
                if ($productModel->image) {
                    Storage::disk('public')->delete('file/model-product/' . $productModel->image);
                }
                $productModel->delete();
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Selected product models have been deleted successfully.',
                'deleted_ids' => $ids,
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'No product models selected for deletion.',
        ], 400);
    }

    public function deleteImage($id)
    {
        $productModels = ProductModel::findOrFail($id);

        if ($productModels->image) {
            Storage::disk('public')->delete('file/model-product/' . $productModels->image);
            $productModels->update(['image' => null, 'updated_by' => Auth::user()->id]);

            return response()->json(['success' => 'Image deleted successfully']);
        }

        return response()->json(['error' => 'Image not found'], 404);
    }
}
