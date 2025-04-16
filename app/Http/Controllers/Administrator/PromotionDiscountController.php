<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;

use App\Models\{DiscountProduct, Discount, Notification, Member};
use App\Enum\{DiscountType, NotificationType};
use Illuminate\Http\Request;
use Carbon\Carbon;


class PromotionDiscountController extends Controller
{
    private $main_menu = 'products';
    public function index(Request $request)
    {
        $query = $request->input('query');
        $status = $request->input('status');
        $promoType = $request->input('promo');

        $recommendQuery = Discount::query()->with('discountProducts')
            ->select(
                'id',
                'name',
                'discount_type',
                'status',
                'start_date',
                'end_date'
            )
            ->whereNull('deleted_at')
            ->orderBy('id', 'ASC');

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

        if ($promoType) {
            $recommendQuery->where('discount_type', $promoType);
        }

        $recommend = $recommendQuery->paginate(10)->appends([
            'query' => $query,
            'status' => $status,
            'promo' => $promoType,
        ]);

        $main_menu = $this->main_menu;
        return view('administrator.promo_discount.index', compact('main_menu', 'query', 'status', 'promoType', 'recommend'));
    }

    public function add()
    {
        $main_menu = $this->main_menu;

        $discountTypes = DiscountType::cases();


        return view('administrator.promo_discount.add', compact('main_menu', 'discountTypes'));
    }

    public function edit($id)
    {

        $main_menu = $this->main_menu;
        $promo = Discount::find($id);
        $promoDiscount = DiscountProduct::with('product')->where('discount_id', $id)->get();
        $discountTypes = DiscountType::cases();

        return view('administrator.promo_discount.edit', compact(
            'promoDiscount',
            'discountTypes',
            'promo',
            'main_menu',
        ));
    }

    public function submit(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'product_id' => 'required',
            'discount_type' => 'required',
            'discount_amount' => 'required_without_all:price|min:0',
            'price.*' => 'required_without:discount_amount|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ], [
            'product_id.required' => 'กรุณาเลือกสินค้า',
            'discount_type.required' => 'กรุณากรอกประเภทส่วนลด',
            'discount_amount.required_without_all' => 'กรุณากรอกจำนวนส่วนลดทุกตัว',
            'price.required_without' => 'กรุณากรอกจำนวนส่วนลด',
            'price.numeric' => 'จำนวนส่วนลดต้องเป็นตัวเลข',
            'start_date.required' => 'กรุณากรอกวันที่เริ่มต้น',
            'start_date.date' => 'วันที่เริ่มต้นต้องเป็นวันที่ที่ถูกต้อง',
            'end_date.required' => 'กรุณากรอกวันที่สิ้นสุด',
            'end_date.date' => 'วันที่สิ้นสุดต้องเป็นวันที่ที่ถูกต้อง',
            'end_date.after' => 'วันที่สิ้นสุดต้องหลังจากวันที่เริ่มต้น',
        ]);
        $discountTypes = $request->input('discount_type');
        $discount = Discount::create([
            'name' => $request->input('name'),
            'discount_type' => $discountTypes,
            'discount_amount' => $request->input('discount_amount', 0),
            'start_date' => $request->input('start_date'),
            'end_date' => $request->input('end_date'),
            'status' => $request->input('status', 0),
        ]);


        foreach ($request->input('product_id') as $productId) {
            $price = $request->input("price.$productId");
            DiscountProduct::create([
                'discount_id' => $discount->id,
                'product_id' => $productId,
                'discount_type' => $discountTypes,
                'discount_amount' => $price ?? $request->input('discount_amount', 0),
            ]);
        }

        return redirect()->back()->with('success', 'ข้อมูลถูกบันทึกเรียบร้อยแล้ว');
    }

    public function update(Request $request, $id)
    {
        $discount = Discount::find($id);
        $request->validate([
            'product_id' => 'required',
            'discount_type' => 'required',
            'discount_amount' => 'required_without_all:price|min:0',
            'price.*' => 'required_without:discount_amount|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ], [
            'product_id.required' => 'กรุณาเลือกสินค้า',
            'discount_type.required' => 'กรุณากรอกประเภทส่วนลด',
            'discount_amount.required_without_all' => 'กรุณากรอกจำนวนส่วนลดทุกตัว',
            'price.required_without' => 'กรุณากรอกจำนวนส่วนลด',
            'price.numeric' => 'จำนวนส่วนลดต้องเป็นตัวเลข',
            'start_date.required' => 'กรุณากรอกวันที่เริ่มต้น',
            'start_date.date' => 'วันที่เริ่มต้นต้องเป็นวันที่ที่ถูกต้อง',
            'end_date.required' => 'กรุณากรอกวันที่สิ้นสุด',
            'end_date.date' => 'วันที่สิ้นสุดต้องเป็นวันที่ที่ถูกต้อง',
            'end_date.after' => 'วันที่สิ้นสุดต้องหลังจากวันที่เริ่มต้น',
        ]);
        $discountTypes = $request->input('discount_type');
        $discount->update([
            'name' => $request->input('name'),
            'discount_type' => $discountTypes,
            'discount_amount' => $request->input('discount_amount', 0),
            'start_date' => $request->input('start_date'),
            'end_date' => $request->input('end_date'),
            'status' => $request->input('status', 0),
        ]);
        DiscountProduct::where('discount_id', $discount->id)
            ->forceDelete();
        foreach ($request->input('product_id') as $productId) {

            $price = $request->input("price.$productId");
            DiscountProduct::updateOrCreate([
                'discount_id' => $discount->id,
                'product_id' => $productId,
                'discount_type' => $discountTypes,
                'discount_amount' => $price ?? $request->input('discount_amount', 0),
            ]);
        }
        return redirect()->back()->with('success', 'ข้อมูลถูกบันทึกเรียบร้อยแล้ว');
    }

    public function destroy($id, Request $request)
    {

        DiscountProduct::where('discount_id', $id)->delete();
        Discount::where('id', $id)->delete();

        $currentPage = $request->query('page', 1);

        return redirect()->route('administrator.promo_discount', ['page' => $currentPage])->with([
            'success' => 'Discount product deleted successfully!',
            'id' => $id
        ]);
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids');
        if (is_array($ids) && count($ids) > 0) {
            Discount::whereIn('id', $ids)->delete();
            DiscountProduct::whereIn('discount_id', $ids)->delete();
            return response()->json([
                'status' => 'success',
                'message' => 'Selected discount product have been deleted successfully.',
                'deleted_ids' => $ids
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'No promotion selected for deletion.'
        ], 400);
    }
    public function notifications($id)
    {
        $member = Member::all();
        foreach ($member as $members) {
            Notification::create([
                'member_id' => $members->id,
                'module_id' => $id,
                'module_name' => NotificationType::promotion_discount->value,
                'created_at' => Carbon::now()
            ]);
        }
        return response()->json(['success' => 'Notification successfully']);
    }
}
