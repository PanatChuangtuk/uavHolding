<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use App\Models\{ProductBrand, ProductType};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Carbon\Carbon;

class ProductBrandController extends Controller
{
    private $main_menu = 'products';
    public function index(Request $request)
    {
        $main_menu = $this->main_menu;
        $query = $request->input('query');
        $status = $request->input('status');

        $productBrandQuery = ProductBrand::query();

        if ($query) {
            $productBrandQuery->where('name', 'LIKE', "%{$query}%")
                ->orWhere('code', 'LIKE', "%{$query}%");
        }

        if ($status) {
            $statusValue = ($status === 'active') ? 1 : 0;
            $productBrandQuery->where('status', $statusValue);
        }

        $productBrands = $productBrandQuery->paginate(10)->appends([
            'query' => $query,
            'status' => $status,
        ]);

        return view('administrator.brand-product.index', compact('productBrands', 'query', 'status', 'main_menu'));
    }


    public function add()
    {
        $main_menu = $this->main_menu;
        $product_type = ProductType::all();

        return view('administrator.brand-product.add', compact('main_menu', 'product_type'));
    }

    public function edit($id)
    {
        $main_menu = $this->main_menu;
        $productBrands = ProductBrand::find($id);
        $product_type = ProductType::all();
        return view('administrator.brand-product.edit', compact('productBrands', 'main_menu', 'product_type'));
    }

    public function submit(Request $request)
    {
        // dd($request->all());
        // $request->validate([
        //     'name' => 'required|string|max:255',
        //     'url' => 'nullable|url',
        //     'image' => 'nullable|image|max:2048',
        // ]);

        $filename = null;
        if ($request->hasFile('image')) {
            $filename = $this->uploadsImage($request->file('image'), 'product_brand');
        }

        ProductBrand::create([
            'product_type_id' => $request->input('type_id'),
            'name' => $request->input('name'),
            'code' => $request->input('code'),
            'image' => $filename,
            'status' => $request->input('status', 0),
            'created_at' => Carbon::now(),
            'created_by' => Auth::user()->id,
        ]);

        return redirect()->back()->with('success', 'ข้อมูลถูกสร้างเรียบร้อยแล้ว');
    }

    public function update(Request $request, $id)
    {
        $productBrands = ProductBrand::find($id);
        $filename = $productBrands->image;

        if ($request->hasFile('image')) {
            $filename = $this->uploadsImage($request->file('image'), 'product_brand');
            if ($productBrands->image) {
                Storage::disk('public')->delete('file/product_brand/' . $productBrands->image);
            }
        }

        $productBrands->update([
            'product_type_id' => $request->input('type_id'),
            'name' => $request->input('name'),
            'code' => $request->input('code'),
            'image' => $filename,
            'status' => $request->input('status', 0),
            'updated_by' => Auth::user()->id,
        ]);

        return redirect()->back()->with('success', 'ข้อมูลถูกบันทึกเรียบร้อยแล้ว');
    }

    public function destroy($id, Request $request)
    {
        $productBrands = ProductBrand::findOrFail($id);
        if ($productBrands->image) {
            Storage::disk('public')->delete('file/brand/' . $productBrands->image);
        }
        $productBrands->delete();

        $currentPage = $request->query('page', 1);

        return redirect()->route('administrator.brand-product', ['page' => $currentPage])->with('success', 'Product brand deleted successfully!');
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids');

        if (is_array($ids) && count($ids) > 0) {
            $productBrands = ProductBrand::whereIn('id', $ids)->get();
            foreach ($productBrands as $productBrand) {
                if ($productBrand->image) {
                    Storage::disk('public')->delete('file/product_brand/' . $productBrand->image);
                }
                $productBrand->delete();
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Selected product brands have been deleted successfully.',
                'deleted_ids' => $ids,
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'No product brands selected for deletion.',
        ], 400);
    }

    public function deleteImage($id)
    {
        $productBrands = ProductBrand::findOrFail($id);

        if ($productBrands->image) {
            Storage::disk('public')->delete('file/product_brand/' . $productBrands->image);
            $productBrands->update(['image' => null, 'updated_by' => Auth::user()->id]);

            return response()->json(['success' => 'Image deleted successfully']);
        }

        return response()->json(['error' => 'Image not found'], 404);
    }
}
