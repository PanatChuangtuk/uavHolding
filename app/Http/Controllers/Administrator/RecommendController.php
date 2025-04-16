<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use App\Models\{Recommend, Product, ProductModel};
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{DB, Auth, Validator};

use App\Enum\CommonEnum;

class RecommendController extends Controller
{
    private $main_menu = 'products';
    public function index(Request $request)
    {
        $query = $request->input('query');

        $status = $request->input('status');

        $recommendQuery = Recommend::with(['product', 'productModel']);

        if ($query) {
            $recommendQuery->where(function ($queryBuilder) use ($query) {
                $queryBuilder->whereHas('product', function ($productQuery) use ($query) {
                    $productQuery->where('name', 'LIKE', "%{$query}%");
                })
                    ->orWhereHas('productModel', function ($productModelQuery) use ($query) {
                        $productModelQuery->where('name', 'LIKE', "%{$query}%")
                            ->orWhere('code', 'LIKE', "%{$query}%");
                    });
            });
        }

        if ($status) {
            $statusValue = ($status === 'active') ? 1 : 0;
            $recommendQuery->where('status', $statusValue);
        }
        $recommend = $recommendQuery->paginate(10)->appends([
            'query' => $query,
            'status' => $status,
        ]);

        $main_menu = $this->main_menu;
        return view('administrator.recommend.index', compact('main_menu', 'query', 'status', 'recommend'));
    }

    public function add()
    {
        $main_menu = $this->main_menu;
        return view('administrator.recommend.add', compact('main_menu'));
    }

    public function edit($id)
    {
        $recommend = Recommend::find($id);
        $main_menu = $this->main_menu;
        return view('administrator.recommend.edit', compact('main_menu', 'recommend'));
    }

    public function submit(Request $request)
    {
        $product = DB::table('product')->select('id', 'product_model_id',  'sku', 'name')->where('product_model_id', $request->input('product_model_id'))->first();

        Recommend::create([
            'product_model_id' => $request->input('product_model_id'),
            'product_id' => $product->id,
            'status' => $request->input('status', 0),
            'created_by' => Auth::user()->id,
            'created_at' =>  Carbon::now(),
        ]);
        return redirect()->back()->with('success', 'ข้อมูลถูกสร้างเรียบร้อยแล้ว');
    }

    public function update(Request $request, $id)
    {
        $recommend = Recommend::find($id);
        $product = DB::table('product')->select('id', 'product_model_id',  'sku', 'name')->where('product_model_id', $request->input('product_model_id'))->first();
        $recommend->update([
            'product_model_id' => $request->input('product_model_id'),
            'product_id' => $product->id,
            'status' => $request->input('status', 0),
            'updated_by' => Auth::user()->id,
            'updated_at' => Carbon::now(),
        ]);
        return redirect()->back()->with('success', 'ข้อมูลถูกบันทึกเรียบร้อยแล้ว');
    }

    public function destroy($id, Request $request)
    {
        $common = Recommend::findOrFail($id);
        $common->delete();

        $currentPage = $request->query('page', 1);

        return redirect()->route('administrator.common', ['page' => $currentPage])->with([
            'success' => 'Common deleted successfully!',
            'id' => $id
        ]);
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids');

        if (is_array($ids) && count($ids) > 0) {
            Recommend::whereIn('id', $ids)->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Selected common have been deleted successfully.',
                'deleted_ids' => $ids
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'No common selected for deletion.'
        ], 400);
    }
}
