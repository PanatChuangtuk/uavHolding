<?php

namespace App\Http\Controllers\Administrator;

use App\Models\{ProductModel, ProductUnitValue, ProductType, ProductInformation, ProductPrice, ProductAttribute, ProductBrand, ProductSize, Product, MemberGroup};
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, Storage, Log, File, DB};
use App\Http\Controllers\Controller;
use Rap2hpoutre\FastExcel\FastExcel;

class ProductController extends Controller
{
    private $main_menu = 'products';
    public function index(Request $request)
    {
        $main_menu = $this->main_menu;
        $query = $request->input('query');
        $status = $request->input('status');

        $productQuery = Product::with([
            'productModel.productBrand',
            'productModel.productType',
            'productPrices' => function ($query) {
                $query->whereIn('member_group_id', [1, 2, 3, 4]);
            }
        ]);

        if ($query) {
            $productQuery->where('name', 'LIKE', "%{$query}%")
                ->orWhere('sku', 'LIKE', "%{$query}%");
        }

        if ($status) {
            $statusValue = ($status === 'active') ? 1 : 0;
            $productQuery->where('status', $statusValue);
        }

        $product = $productQuery->paginate(10)->appends([
            'query' => $query,
            'status' => $status,
        ]);

        return view('administrator.product.index', compact('product', 'query', 'status', 'main_menu'));
    }

    public function edit($id)
    {
        $main_menu = $this->main_menu;
        $product = Product::findOrFail($id);
        // $models = ProductModel::all();
        $memberGroups = MemberGroup::all();
        $product_price = ProductPrice::where('product_id', $id)->get();

        return view('administrator.product.edit', compact('product', 'product_price', 'main_menu', 'memberGroups'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|string|max:255',
            'size' => 'nullable|string|max:255',
        ], [
            'name.required' => 'กรุณากรอกชื่อสินค้า',
            'sku.required' => 'กรุณากรอกรหัสสินค้า',

        ]);
        $product = Product::findOrFail($id);
        $product->update([
            'name' => $request->input('name'),
            'sku' => $request->input('sku'),
            // 'size' => $request->input('size'),
            'product_model_id' => $request->input('product_model_id'),
            'updated_by' => Auth::id(),
            'updated_at' => Carbon::now(),
        ]);
        optional(optional($product->productSizes->first())->productUnitValue)->update([
            'name' => $request->input('size'),
        ]);
        foreach ($request->input('price', []) as $groupId => $price) {
            $numericPrice = number_format((float) str_replace(',', '', $price), 2, '.', '');
            ProductPrice::updateOrCreate(
                ['product_id' => $id, 'member_group_id' => $groupId],
                ['price' => $numericPrice]
            );
        }
        return redirect()->route('administrator.product')->with('success', 'อัปเดตสินค้าสำเร็จ');
    }

    public function destroy($id, Request $request)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        $currentPage = $request->query('page', 1);

        return redirect()->route('administrator.product', ['page' => $currentPage])->with([
            'success' => 'Product deleted successfully!',
            'id' => $id
        ]);
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids');

        if (is_array($ids) && count($ids) > 0) {
            Product::whereIn('id', $ids)->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Selected products have been deleted successfully.',
                'deleted_ids' => $ids
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'No products selected for deletion.'
        ], 400);
    }

    public function deleteImage($id)
    {
        $product = Product::find($id);

        if ($product) {
            $oldImagePath = 'file/product/' . $product->image;

            if (Storage::disk('public')->exists($oldImagePath)) {
                Storage::disk('public')->delete($oldImagePath);
            }

            $product->update([
                'image' => null,
                'updated_by' => Auth::user()->id
            ]);

            return response()->json(['success' => 'Image deleted successfully']);
        }

        return response()->json(['error' => 'Product not found'], 404);
    }

    public function importPage()
    {
        $main_menu = $this->main_menu;
        $productTypes = ProductType::all();

        return view('administrator.product.import', compact('productTypes', 'main_menu'));
    }
    public function import(Request $request)
    {
        ini_set('memory_limit', '512M');
        ini_set('max_execution_time', 180);
        $request->validate([
            'type' => 'required|exists:product_type,id',
            'file' => 'required|file|mimes:csv,txt'
        ], [
            'file.required' => 'กรุณาเลือกไฟล์ที่ต้องการนำเข้า',
            'file.file' => 'ไฟล์ที่เลือกไม่ถูกต้อง',
            'file.mimes' => 'ไฟล์ที่เลือกต้องเป็นไฟล์ CSV เท่านั้น',
        ]);

        $type = $request->input('type');
        $file = $request->file('file');

        $filePath = $file->storeAs('file/product', $file->getClientOriginalName(), 'public');
        $filePath = public_path('upload/' . $filePath);
        $data = [];

        (new FastExcel)->import($filePath, function ($line) use (&$data, $type) {
            $data[] = [
                'erp_sku' => $line['ERP SKU'],
                'description' => $line['Description'],
                'cas' => $line['CAS'] ?? null,
                'packsize' => $line['PackSize'],
                'last_direct_cost' => is_numeric($line['Last Direct Cost'] ?? null) ? (float) $line['Last Direct Cost'] : 0,
                'user' => is_numeric($line['User'] ?? null) ? (float) $line['User'] : 0,
                'dealer' => is_numeric($line['Dealer'] ?? null) ? (float) $line['Dealer'] : 0,
                'wholesales' => is_numeric($line['Wholesales'] ?? null) ? (float) $line['Wholesales'] : 0,
                'partner' => is_numeric($line['Partner'] ?? null) ? (float) $line['Partner'] : 0,
                'linearformula' => $line['LinearFormula'] ?? null,
                'formulaweight' => $line['FormulaWeight'] ?? null,
                'density' => $line['Density'] ?? null,
                'flashpoint' => $line['FlashPoint'] ?? null,
                'meltingpoint' => isset($line['MeltingPoint']) ? preg_replace('/[^0-9.,°]/', '', $line['MeltingPoint']) : null,
                'boilingpoint' => isset($line['BoilingPoint']) ? preg_replace('/[^0-9.,°]/', '', $line['BoilingPoint']) : null,
                'unnumber' => isset($line['UNNumber']) ? preg_replace('/[^0-9.,°]/', '', $line['UNNumber']) : null,
                'hazardclass' =>  isset($line['HazardClass']) ? preg_replace('/[^0-9.,°]/', '', $line['HazardClass']) : null,
                'packinggroup' =>  isset($line['PackingGroup']) ? preg_replace('/[^0-9.,°]/', '', $line['PackingGroup']) : null,
                'tariffcode' => isset($line['TariffCode']) ? preg_replace('/[^0-9.,°]/', '', $line['TariffCode']) : null,
                'storageconditions' => isset($line['StorageConditions']) ? preg_replace('/[^0-9.,°]/', '', $line['StorageConditions']) : null,
                'shipment' => isset($line['Shipment']) ? preg_replace('/[^0-9.,°]/', '', $line['Shipment']) : null,
                'shelflifemonths' => isset($line['ShelfLifeMonths']) ? preg_replace('/[^0-9.,°]/', '', $line['ShelfLifeMonths']) : null,
                'item_id' => $line['Id'] ?? null,
                'uom_id' => $line['Uom'] ?? null
            ];
            if (count($data) >= 1000) {
                $this->processDataInChunks($data, $type);
                $data = [];
            }
        });
        if (!empty($data)) {
            $this->processDataInChunks($data, $type);
        }

        return redirect()->back()->with('success', 'สินค้านำเข้าสำเร็จแล้ว');
    }

    public function processDataInChunks(array|string $data, $type)
    {
        $now = now()->toDateTimeString();
        $brandNames = [];
        $modelCodes = [];
        $productSkus = [];

        foreach ($data as $record) {
            $brandName = strtok($record['erp_sku'], '-');
            $modelCode = strtok($record['erp_sku'], '.');

            $brandNames[$brandName] = ['name' => $brandName, 'code' => $brandName, 'product_type_id' => $type, 'status' => 1, 'created_at' => $now, 'created_by' => 1];
            $modelCodes[$modelCode] = [
                'product_type_id' => $type,
                'name' => $record['description'],
                'description' => $record['description'],
                'code' => $modelCode,
                'status' => 1,
                'created_at' => $now
            ];

            $productSkus[$record['erp_sku']] = $record;
        }

        $existingBrandsId = ProductBrand::whereIn('code', array_keys($brandNames))->pluck('id', 'code');
        $existingModels = ProductModel::whereIn('code', array_keys($modelCodes))->pluck('id', 'code');
        $existingProducts = Product::whereIn('sku', array_keys($productSkus))->pluck('id', 'sku');

        $newBrands = array_diff_key($brandNames, $existingBrandsId->toArray());
        $newModels = array_diff_key($modelCodes, $existingModels->toArray());

        if (!empty($newBrands)) {
            ProductBrand::insert($newBrands);
            $existingBrandsId = ProductBrand::whereIn('code', array_keys($brandNames))->pluck('id', 'code');
        }


        if (!empty($newModels)) {
            foreach ($newModels as $code => &$model) {
                $model['product_brand_id'] = $existingBrandsId[$brandName] ?? null;
            }

            ProductModel::insert($newModels);
            $existingModels = ProductModel::whereIn('code', array_keys($modelCodes))->pluck('id', 'code');
        }

        $newProducts = [];
        foreach ($productSkus as $sku => $record) {
            if (!isset($existingProducts[$sku])) {
                $brandName = strtok($sku, '-');
                $modelCode = strtok($sku, '.');

                $newProducts[] = [
                    'product_model_id' => $existingModels[$modelCode] ?? null,
                    'sku' => $sku,
                    'name' => $record['description'] ?? null,
                    'cost_price' => $record['last_direct_cost'],
                    'status' => 1,
                    'item_id' => $record['item_id'],
                    'created_at' => $now
                ];
            }
        }

        if (!empty($newProducts)) {
            Product::insert($newProducts);
            $existingProducts = Product::whereIn('sku', array_keys($productSkus))->pluck('id', 'sku');
        }

        $prices = [];
        $memberGroups = ['user', 'dealer', 'wholesales', 'partner'];
        $groupIds = [1, 2, 3, 4];

        foreach ($productSkus as $sku => $record) {
            if (!isset($existingProducts[$sku])) continue;

            foreach ($memberGroups as $index => $group) {
                if (!empty($record[$group])) {
                    $prices[] = [
                        'product_id' => $existingProducts[$sku],
                        'member_group_id' => $groupIds[$index],
                        'price' => is_numeric($record[$group]) ? (float)$record[$group] : 0,
                        'status' => 1,
                        'created_at' => $now
                    ];
                }
            }
        }

        if (!empty($newProducts)) {
            ProductPrice::insert($prices);
        }

        $unitValues = [];
        foreach ($productSkus as $sku => $record) {
            if (!isset($existingProducts[$sku]) || empty($record['packsize'])) continue;
            $unitValues[$sku] = [
                'product_unit_id' => 14,
                'uom_id' => $record['uom_id'],
                'name' => $record['packsize'],
                'created_at' => $now,
                'created_by' => 1
            ];
        }

        $existingUnitValues = ProductUnitValue::whereIn('uom_id', array_column($unitValues, 'uom_id'))
            ->whereIn('name', array_column($unitValues, 'name'))
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item->uom_id . '|' . $item->name => $item->id];
            })
            ->toArray();
        // Log::info($existingUnitValues);
        // $newUnitValues = array_diff_key($unitValues, $existingUnitValues->toArray());

        if (!empty($newProducts)) {

            ProductUnitValue::insert($unitValues);
            $existingUnitValues = ProductUnitValue::whereIn('uom_id', array_column($unitValues, 'uom_id'))
                ->whereIn('name', array_column($unitValues, 'name'))
                ->get()
                ->mapWithKeys(function ($item) {
                    return [$item->uom_id . '|' . $item->name => $item->id];
                })
                ->toArray();
        }

        $productSizes = [];
        foreach ($productSkus as $sku => $record) {
            if (!isset($existingProducts[$sku]) || empty($record['packsize'])) continue;
            $key = $record['uom_id'] . '|' . $record['packsize'];

            $productSizes[] = [
                'product_id' => $existingProducts[$sku],
                'product_unit_value_id' => $existingUnitValues[$key],
                'created_at' => $now,
                'created_by' => 1
            ];
        }

        if (!empty($newProducts)) {
            ProductSize::insert($productSizes);
        }

        $attributes = [
            'CAS',
            'LinearFormula',
            'FormulaWeight',
            'Density',
            'FlashPoint',
            'MeltingPoint',
            'BoilingPoint',
            'UNNumber',
            'HazardClass',
            'PackingGroup',
            'TariffCode',
            'StorageConditions',
            'Shipment',
            'ShelfLifeMonths'
        ];

        $attributeData = [];
        foreach ($attributes as $code) {
            $attributeData[$code] = ['code' => $code, 'name' => $code, 'product_type_id' => $type, 'created_at' => $now, 'created_by' => 1];
        }

        $existingAttributes = ProductAttribute::whereIn('code', array_keys($attributeData))->pluck('id', 'code');
        $newAttributes = array_diff_key($attributeData, $existingAttributes->toArray());

        if (!empty($newAttributes)) {
            ProductAttribute::insert($newAttributes);
            $existingAttributes = ProductAttribute::whereIn('code', array_keys($attributeData))->pluck('id', 'code');
        }

        $productInfo = [];
        foreach ($productSkus as $sku => $record) {
            if (!isset($existingProducts[$sku])) continue;

            foreach ($attributes as $code) {
                if (!empty($record[strtolower($code)])) {
                    $productInfo[] = [
                        'product_model_id' => $existingModels[strtok($sku, '.')] ?? null,
                        'product_attribute_id' => $existingAttributes[$code],
                        'detail' => $record[strtolower($code)],
                        'status' => 1,
                        'created_at' => $now,
                        'created_by' => 1
                    ];
                }
            }
        }

        if (!empty($newProducts)) {
            ProductInformation::insert($productInfo);
        }
    }
    public function exportPage()
    {
        $main_menu = $this->main_menu;
        $productTypes = ProductType::all();

        return view('administrator.product.export', compact('productTypes', 'main_menu'));
    }

    public function export(Request $request)
    {
        ini_set('memory_limit', '512M');
        ini_set('max_execution_time', 180);
        $filePath = public_path('upload/file/product');
        $files = File::allFiles($filePath);
        $latestFile = null;
        $latestTime = 0;
        foreach ($files as $file) {
            $fileTime = $file->getMTime();
            if ($fileTime > $latestTime) {
                $latestTime = $fileTime;
                $latestFile = $file;
            }
        }
        $filePath = public_path('upload/file/product/' . $latestFile->getFilename());

        return response()->download($filePath);
        // $request->validate([
        //     'type' => 'required|exists:product_type,id'
        // ]);

        // $type_id = $request->type;
        // dd($type_id);

        // $products = collect();

        // Product::query()
        //     ->whereHas('productModel', function ($query) use ($type_id) {
        //         $query->where('product_type_id', $type_id);
        //     })
        //     ->with([
        //         'productModel:id,code,name,product_type_id,description',  // ดึงเฉพาะคอลัมน์ที่จำเป็น
        //         'productSizes.productUnitValue:id,name',  // ดึงเฉพาะคอลัมน์ที่จำเป็น
        //         'productPrices:id,product_id,price,member_group_id',  // ดึงเฉพาะคอลัมน์ที่จำเป็น
        //     ])
        //     ->chunk(50, function ($chunk) use ($products) {  // เพิ่มขนาด chunk เพื่อลดการทำงานซ้ำ
        //         foreach ($chunk as $product) {

        //             $productModel = $product->productModel;
        //             $productInformation = $productModel ? $productModel->productInformations->first() : null;
        //             $productAttributes = $productInformation ? $productInformation->productAttribute->pluck('name', 'code') : [];

        //             $products->push([
        //                 'SKU' => $product->sku,
        //                 'Description' => $product->productModel->description,
        //                 'CAS' =>  $productAttributes['CAS'] ?? null,
        //                 'PackSize' => $product->productSizes->first()->productUnitValue->name ?? '',
        //                 'Last Direct Cost' => $product->cost_price,
        //                 'User' => $product->productPrices->firstWhere('member_group_id', 1)->price ?? 0.00,
        //                 'Dealer' => $product->productPrices->firstWhere('member_group_id', 2)->price ?? 0.00,
        //                 'Wholesales' => $product->productPrices->firstWhere('member_group_id', 3)->price ?? 0.00,
        //                 'Partner' => $product->productPrices->firstWhere('member_group_id', 4)->price ?? 0.00,
        //                 'FormulaWeight' => $productAttributes['FormulaWeight'] ?? null,
        //                 'LinearFormula' => $productAttributes['LinearFormula'] ?? null,
        //                 'Density' => $productAttributes['Density'] ?? null,
        //                 'FlashPoint' => $productAttributes['FlashPoint'] ?? null,
        //                 'MeltingPoint' => $productAttributes['MeltingPoint'] ?? null,
        //                 'BoilingPoint' => $productAttributes['BoilingPoint'] ?? null,
        //                 'UNNumber' => $productAttributes['UNNumber'] ?? null,
        //                 'HazardClass' => $productAttributes['HazardClass'] ?? null,
        //                 'PackingGroup' => $productAttributes['PackingGroup'] ?? null,
        //                 'TariffCode' => $productAttributes['TariffCode'] ?? null,
        //                 'StorageConditions' => $productAttributes['StorageConditions'] ?? null,
        //                 'Shipment' => $productAttributes['Shipment'] ?? null,
        //                 'ShelfLifeMonths' => $productAttributes['ShelfLifeMonths'] ?? null,
        //                 'Item ID' => $product->item_id,
        //             ]);
        //             return (new FastExcel($products))->download('Table_' . now()->format('Ymd_His') . '.csv', function ($product) {
        //                 // Optional: Log each row being exported
        //                 Log::info('Exporting product: ' . $product['SKU']);
        //             });
        //         }

        //         Log::info('Processed ' . $chunk->count() . ' products.');
        //     });



        // return response()->json([
        //     'message' => 'Your export is processing. You will be notified when it is ready.',

        // ]);
    }

    protected function exportToExcel(array $data)
    {
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $headers = array_keys($data[0]);
        foreach ($headers as $colIndex => $header) {
            $sheet->setCellValueByColumnAndRow($colIndex + 1, 1, $header);
        }

        foreach ($data as $rowIndex => $row) {
            foreach ($row as $colIndex => $value) {
                $sheet->setCellValueByColumnAndRow($colIndex + 1, $rowIndex + 2, $value);
            }
        }

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $fileName = 'product_export_' . now()->format('Ymd_His') . '.xlsx';
        $filePath = storage_path("exports/{$fileName}");
        $writer->save($filePath);

        return response()->download($filePath)->deleteFileAfterSend();
    }
    public function detonate(Request $request)
    {
        DB::table('product_brand')->truncate();

        DB::table('product_model')->truncate();

        DB::table('product')->truncate();

        DB::table('product_price')->truncate();

        DB::table('product_size')->truncate();

        DB::table('product_attribute')->truncate();

        DB::table('product_information')->truncate();

        DB::table('product_unit_value')->truncate();
    }
}
