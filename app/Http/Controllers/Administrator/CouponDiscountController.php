<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;

use App\Models\{CouponContent, Coupon, CouponBrand, CouponCategory, CouponProduct, Product, Notification, Member, Language};
use App\Enum\{DiscountType, CouponType, Type, NotificationType};
use Illuminate\Http\Request;
use Carbon\Carbon;

class CouponDiscountController extends Controller
{
    private $main_menu = 'products';
    public function index(Request $request)
    {
        $query = $request->input('query');
        $status = $request->input('status');
        $couponType = $request->input('coupon'); // ตัวแปรใหม่สำหรับเก็บค่า coupon type

        $couponQuery = Coupon::query()
            ->select(
                'id',
                'name',
                'coupon_type',
                'discount_type',
                'status',
                'start_date',
                'end_date'
            )
            ->whereNull('deleted_at')
            ->orderBy('id', 'ASC');

        // กรองตาม query
        if ($query) {
            $couponQuery->where(function ($queryBuilder) use ($query) {
                $queryBuilder->whereHas('product', function ($productQuery) use ($query) {
                    $productQuery->where('name', 'LIKE', "%{$query}%");
                })
                    ->orWhereHas('productModel', function ($productModelQuery) use ($query) {
                        $productModelQuery->where('name', 'LIKE', "%{$query}%")
                            ->orWhere('code', 'LIKE', "%{$query}%");
                    });
            });
        }

        // กรองตาม status
        if ($status) {
            $statusValue = ($status === 'active') ? 1 : 0;
            $couponQuery->where('status', $statusValue);
        }

        // กรองตาม discount type
        if ($couponType) {
            $couponQuery->where('coupon_type', $couponType);
        }

        // ดึงข้อมูลจากฐานข้อมูลพร้อมการกรอง
        $coupon = $couponQuery->paginate(10)->appends([
            'query' => $query,
            'status' => $status,
            'coupon' => $couponType, // เพิ่ม coupon type ลงไปในการ paginated link
        ]);

        $main_menu = $this->main_menu;
        return view('administrator.coupon.index', compact('main_menu', 'query', 'status', 'coupon', 'couponType'));
    }

    public function add()
    {
        $main_menu = $this->main_menu;
        $discountTypes = DiscountType::cases();
        $couponTypes = CouponType::cases();
        $allProduct = Product::all();
        $types = Type::cases();
        $language = Language::get();
        return view('administrator.coupon.add', compact('main_menu', 'discountTypes', 'couponTypes', 'allProduct', 'types', 'language'));
    }

    public function edit($id)
    {
        $main_menu = $this->main_menu;
        $coupon = Coupon::find($id);
        $brand = CouponBrand::where('coupon_id', $coupon->id)->get();
        $category = CouponCategory::where('coupon_id', $coupon->id)->get();
        $product = CouponProduct::where('coupon_id', $coupon->id)->get();
        $language = Language::get();
        $couponContents = CouponContent::where('coupon_id', $coupon->id)->get()->keyBy('language_id');
        $discountTypes = DiscountType::cases();
        $couponTypes = CouponType::cases();
        return view('administrator.coupon.edit', compact(
            'discountTypes',
            'coupon',
            'main_menu',
            'brand',
            'category',
            'product',
            'couponTypes',
            'language',
            'couponContents'
        ));
    }

    public function submit(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'product_id' => 'nullable',
            'coupon_type' => 'required',
            'limit' => 'required|integer|min:1',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',

        ], [
            'product_id.required' => 'กรุณาเลือกสินค้า',
            'coupon_type.required' => 'กรุณาเลือกชนิดคูปอง',
            'limit.required' => 'กรุณากรอกจำนวน',
            'limit.integer' => 'ค่าต้องเป็นตัวเลขเท่านั้น',
            'limit.min' => 'ค่าต้องไม่น้อยกว่า 1',
            'discount_type.required' => 'กรุณากรอกประเภทส่วนลด',
            'discount_amount.required' => 'กรุณากรอกจำนวนส่วนลดทุกตัว',
            'start_date.required' => 'กรุณากรอกวันที่เริ่มต้น',
            'start_date.date' => 'วันที่เริ่มต้นต้องเป็นวันที่ที่ถูกต้อง',
            'end_date.required' => 'กรุณากรอกวันที่สิ้นสุด',
            'end_date.date' => 'วันที่สิ้นสุดต้องเป็นวันที่ที่ถูกต้อง',
            'end_date.after' => 'วันที่สิ้นสุดต้องหลังจากวันที่เริ่มต้น',

        ]);
        $languages = Language::all();
        $discountTypes = $request->input('discount_type');
        $discount = Coupon::create([
            'name' => $request->input('name'),
            'limit' => $request->input('limit', 1),
            'discount_type' => $discountTypes,
            'coupon_type' => $request->input('coupon_type'),
            "max_discount" => $request->input('max_discount'),
            "base_price" => $request->input('base_price'),
            'discount_amount' => $request->input('discount_amount', 0),
            'start_date' => $request->input('start_date'),
            'end_date' => $request->input('end_date'),
            'status' => $request->input('status', 0),
        ]);
        $nameArray = $request->input('nameContent');
        $descriptionArray = $request->input('description');
        foreach ($languages as $language) {
            $newData = [
                'coupon_id' => $discount->id,
                'language_id' => $language->id,
                'name' => $nameArray[$language->id] ?? null,
                'description' => $descriptionArray[$language->id] ?? null,
                'created_at' => now()
            ];
            // dd($newData);
            CouponContent::create($newData);
        }
        if ($request->input('statusProduct') === Type::All->value) {
            CouponProduct::create([
                'coupon_id' => $discount->id,
                'product_id' => 0,
                'type' => Type::All->value,
                'created_at' => now('Asia/Bangkok')
            ]);
        } else {
            if ($request->input('product_id')) {
                foreach ($request->input('product_id') as $productId) {
                    // $price = $request->input("price.$productId");
                    CouponProduct::create([
                        'coupon_id' => $discount->id,
                        'product_id' => $productId,
                        'type' => Type::Item->value,
                        'created_at' => now('Asia/Bangkok')
                    ]);
                }
            }
        }
        if ($request->input('statusBrand') === Type::All->value) {
            CouponBrand::create([
                'coupon_id' => $discount->id,
                'brand_id' => 0,
                'type' => Type::All->value,
                'created_at' => now('Asia/Bangkok')
            ]);
        } else {
            $brandIds = $request->input('brand_id');
            if (!empty($brandIds)) {
                foreach ($brandIds as $brandId) {
                    CouponBrand::create([
                        'coupon_id' => $discount->id,
                        'brand_id' => $brandId,
                        'type' => Type::Item->value,
                        'created_at' => now('Asia/Bangkok')
                    ]);
                }
            }
        }

        if ($request->input('statusCategory') === Type::All->value) {
            CouponCategory::create([
                'coupon_id' => $discount->id,
                'category_id' => 0,
                'type' => Type::All->value,
                'created_at' => now('Asia/Bangkok')
            ]);
        } else {
            $categoryIds = $request->input('category_id');
            if (!empty($categoryIds)) {
                foreach ($categoryIds as $categoryId) {
                    CouponCategory::create([
                        'coupon_id' => $discount->id,
                        'category_id' => $categoryId,
                        'type' => Type::Item->value,
                        'created_at' => now('Asia/Bangkok')
                    ]);
                }
            }
        }

        return redirect()->back()->with('success', 'ข้อมูลถูกบันทึกเรียบร้อยแล้ว');
    }

    public function update(Request $request, $id)
    {
        // dd($request->all());
        $discount = Coupon::find($id);
        $request->validate([
            'product_id' => 'nullable',
            'coupon_type' => 'required',
            'limit' => 'required|integer|min:1',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            // 'statusProduct' => 'nullable|in:' . \App\Enum\Type::All->value . ',' . \App\Enum\Type::Item->value,
            // 'product_id' => 'nullable|required_without:statusProduct|array',
        ], [
            'product_id.required' => 'กรุณาเลือกสินค้า',
            'coupon_type.required' => 'กรุณาเลือกชนิดคูปอง',
            'limit.required' => 'กรุณากรอกจำนวน',
            'limit.integer' => 'ค่าต้องเป็นตัวเลขเท่านั้น',
            'limit.min' => 'ค่าต้องไม่น้อยกว่า 1',
            'discount_type.required' => 'กรุณากรอกประเภทส่วนลด',
            'discount_amount.required' => 'กรุณากรอกจำนวนส่วนลดทุกตัว',
            'start_date.required' => 'กรุณากรอกวันที่เริ่มต้น',
            'start_date.date' => 'วันที่เริ่มต้นต้องเป็นวันที่ที่ถูกต้อง',
            'end_date.required' => 'กรุณากรอกวันที่สิ้นสุด',
            'end_date.date' => 'วันที่สิ้นสุดต้องเป็นวันที่ที่ถูกต้อง',
            'end_date.after' => 'วันที่สิ้นสุดต้องหลังจากวันที่เริ่มต้น',
            // 'statusProduct.required' => 'กรุณาเลือกสถานะของสินค้า',
            // 'product_id.required_without' => 'กรุณาเลือกสินค้าเมื่อสถานะสินค้าเป็น "เลือกสินค้าเฉพาะ"',
            // 'product_id.array' => 'กรุณาเลือกสินค้าอย่างน้อยหนึ่งรายการ',
        ]);
        $discountTypes = $request->input('discount_type');
        $discount->update([
            'name' => $request->input('name'),
            'limit' => $request->input('limit', 1),
            'discount_type' => $discountTypes,
            'coupon_type' => $request->input('coupon_type'),
            "max_discount" => $request->input('max_discount'),
            "base_price" => $request->input('base_price'),
            'discount_amount' => $request->input('discount_amount', 0),
            'start_date' => $request->input('start_date'),
            'end_date' => $request->input('end_date'),
            'status' => $request->input('status', 0),
        ]);
        $languages = Language::all();
        $nameArray = $request->input('nameContent');
        $descriptionArray = $request->input('description');
        foreach ($languages as $language) {
            $name = $nameArray[$language->id] ?? null;
            $description = $descriptionArray[$language->id] ?? null;
            $newsContent = CouponContent::where('coupon_id', $discount->id)
                ->where('language_id', $language->id)
                ->first();
            if ($newsContent) {
                $newsContent->update([
                    'name' => $name,
                    'description' => $description,
                ]);
            } else {
                CouponContent::create([
                    'coupon_id' => $discount->id,
                    'language_id' => $language->id,
                    'name' => $nameArray[$language->id] ?? null,
                    'description' => $descriptionArray[$language->id] ?? null,
                    'created_at' => now()
                ]);
            }
        }
        CouponProduct::where('coupon_id', $discount->id)
            ->forceDelete();
        CouponBrand::where('coupon_id', $discount->id)
            ->forceDelete();
        CouponCategory::where('coupon_id', $discount->id)
            ->forceDelete();
        if ($request->input('statusProduct') === Type::All->value) {
            CouponProduct::updateOrCreate([
                'coupon_id' => $discount->id,
                'product_id' => 0,
                'type' => Type::All->value,
                'created_at' => now('Asia/Bangkok')
            ]);
        } else {
            if ($request->input('product_id')) {
                foreach ($request->input('product_id') as $productId) {
                    // $price = $request->input("price.$productId");
                    CouponProduct::updateOrCreate([
                        'coupon_id' => $discount->id,
                        'product_id' => $productId,
                        'type' => Type::Item->value,
                        'created_at' => now('Asia/Bangkok')
                    ]);
                }
            }
        }
        if ($request->input('statusBrand') === Type::All->value) {
            CouponBrand::updateOrCreate([
                'coupon_id' => $discount->id,
                'brand_id' => 0,
                'type' => Type::All->value,
                'created_at' => now('Asia/Bangkok')
            ]);
        } else {
            $brandIds = $request->input('brand_id');
            if (!empty($brandIds)) {
                foreach ($brandIds as $brandId) {
                    CouponBrand::updateOrCreate([
                        'coupon_id' => $discount->id,
                        'brand_id' => $brandId,
                        'type' => Type::Item->value,
                        'created_at' => now('Asia/Bangkok')
                    ]);
                }
            }
        }

        if ($request->input('statusCategory') === Type::All->value) {
            CouponCategory::updateOrCreate([
                'coupon_id' => $discount->id,
                'category_id' => 0,
                'type' => Type::All->value,
                'created_at' => now('Asia/Bangkok')
            ]);
        } else {
            $categoryIds = $request->input('category_id');
            if (!empty($categoryIds)) {
                foreach ($categoryIds as $categoryId) {
                    CouponCategory::updateOrCreate([
                        'coupon_id' => $discount->id,
                        'category_id' => $categoryId,
                        'type' => Type::Item->value,
                        'created_at' => now('Asia/Bangkok')
                    ]);
                }
            }
        }
        return redirect()->back()->with('success', 'ข้อมูลถูกบันทึกเรียบร้อยแล้ว');
    }

    public function destroy($id, Request $request)
    {

        // CouponDiscount::where('coupon_id', $id)->delete();
        Coupon::where('id', $id)->delete();

        $currentPage = $request->query('page', 1);

        return redirect()->route('administrator.coupon', ['page' => $currentPage])->with([
            'success' => 'Discount product deleted successfully!',
            'id' => $id
        ]);
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids');
        if (is_array($ids) && count($ids) > 0) {
            Coupon::whereIn('id', $ids)->delete();
            // CouponDiscount::whereIn('coupon_id', $ids)->delete();
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
                'module_name' => NotificationType::coupon_discount->value,
                'created_at' => Carbon::now()
            ]);
        }
        return response()->json(['success' => 'Notification successfully']);
    }
}
