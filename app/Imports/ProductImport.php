<?php

namespace App\Imports;

use App\Models\{Product, ProductBrand, ProductModel, ProductPrice, ProductUnitValue, ProductSize, ProductAttribute, ProductInformation, StagingProduct};
use Maatwebsite\Excel\Concerns\{ToModel, WithHeadingRow, WithChunkReading, WithBatchInserts};
use Illuminate\Support\Facades\{DB, Log};

class ProductImport implements ToModel, WithHeadingRow, WithChunkReading, WithBatchInserts
{
    protected $type;

    public function __construct($type)
    {
        $this->type = $type;
    }

    public function model(array $row)
    {
        // $filePath = StagingProduct::all();
        // $row = $filePath->toArray();
        //     try {

        //         static $movedCount = 0;
        //         $data[] = [
        //             'erp_sku' => $row['erp_sku'],
        //             'description' => $row['description'],
        //             'cas' => $row['cas'] ?? NULL,
        //             'packsize' => $row['packsize'],
        //             'last_direct_cost' => is_numeric($row['last_direct_cost'] ?? null) ? (float) $row['last_direct_cost'] : 0,
        //             'user' => is_numeric($row['user'] ?? null) ? (float) $row['user'] : 0,
        //             'dealer' => is_numeric($row['dealer'] ?? null) ? (float)$row['dealer'] : 0,
        //             'wholesales' => is_numeric($row['wholesales'] ?? null) ? (float) $row['wholesales'] : 0,
        //             'partner' => is_numeric($row['partner'] ?? null) ? (float)$row['partner'] : 0,
        //             'linearformula' => $row['linearformula'] ?? NULL,
        //             'formulaweight' => $row['formulaweight'] ?? NULL,
        //             'density' => $row['density'] ?? NULL,
        //             'flashpoint' => $row['flashpoint'] ?? NULL,
        //             'meltingpoint' => $row['meltingpoint'] ?? NULL,
        //             'boilingpoint' => $row['boilingpoint'] ?? NULL,
        //             'unnumber' => $row['unnumber'],
        //             'hazardclass' => $row['hazardclass'],
        //             'packinggroup' => $row['packinggroup'],
        //             'tariffcode' => $row['tariffcode'],
        //             'storageconditions' => $row['storageconditions'],
        //             'shipment' => $row['shipment'],
        //             'shelflifemonths' => $row['shelflifemonths'],
        //             'item_id' => $row['id'],
        //         ];

        //         // if (count($data) >= 5000) {
        //         // DB::table('staging_products')->insert($data);
        //         $data = [];
        //         // };
        //         // Log::info(['Test' => $row]);
        //         $skuParts = explode('-', $row['erp_sku']);
        //         $modelCode = implode('-', array_slice($skuParts, 0, 2));
        //         $modelCode = strtok($modelCode, '.');
        //         $brandCode = strtok($modelCode, '-');
        //         $sku = implode('-', array_slice($skuParts, -2));

        //         DB::beginTransaction();
        //         $brand = ProductBrand::updateOrCreate(
        //             ['code' => $brandCode],
        //             ['name' => $brandCode, 'product_type_id' => $this->type, 'status' => 1, 'created_by' => 1]
        //         );

        //         $model = ProductModel::updateOrCreate(
        //             ['code' => $modelCode, 'product_brand_id' => $brand->id],
        //             ['product_type_id' => $this->type, 'name' => $row['description'] ?? null, 'status' => 1, 'created_at' => now()->toDateTimeString()]
        //         );

        //         $product = Product::updateOrCreate(
        //             ['sku' => $sku, 'product_model_id' => $model->id],
        //             [
        //                 'product_model_id' => $model->id,
        //                 'sku' => $sku,
        //                 'name' => $row['description'] ?? null,
        //                 'cost_price' => $row['last_direct_cost'],
        //                 'status' => 1,
        //                 'item_id' => $row['id'],
        //                 'created_at' => now()->toDateTimeString()
        //             ]
        //         );

        //         $prices = [];
        //         $memberGroups = ['user', 'dealer', 'wholesales', 'partner'];
        //         $groupIds = [1, 2, 3, 4];

        //         foreach ($memberGroups as $index => $group) {
        //             if ($row[$group]) {
        //                 $memberGroupId = $groupIds[$index];
        //                 $price = is_numeric($row[$group]) ? (float)$row[$group] : 0;

        //                 $prices[] = [
        //                     'product_id' => $product->id,
        //                     'member_group_id' => $memberGroupId,
        //                     'price' => $price,
        //                     'status' => 1,
        //                     'created_at' => now()->toDateTimeString()
        //                 ];
        //             }
        //         }

        //         if (!empty($prices)) {
        //             ProductPrice::insert($prices);
        //         }
        //         if ($row['packsize']) {
        //             $unitValueId = ProductUnitValue::insertGetId(
        //                 ['name' => $row['packsize'], 'created_at' => now()->toDateTimeString(), 'created_by' => 1]
        //             );
        //             ProductSize::updateOrCreate(
        //                 ['product_id' => $product->id],
        //                 [
        //                     'product_id' => $product->id,
        //                     'product_unit_value_id' => $unitValueId,
        //                     'created_at' => now()->toDateTimeString(),
        //                     'created_by' => 1
        //                 ]
        //             );
        //         }

        //         $attributes = [
        //             'CAS' => $row['cas'],
        //             'LinearFormula' => $row['linearformula'],
        //             'FormulaWeight' => $row['formulaweight'],
        //             'Density' => $row['density'],
        //             'FlashPoint' => $row['flashpoint'],
        //             'MeltingPoint' => $row['meltingpoint'],
        //             'BoilingPoint' => $row['boilingpoint'],
        //             'UNNumber' => $row['unnumber'],
        //             'HazardClass' => $row['hazardclass'],
        //             'PackingGroup' => $row['packinggroup'],
        //             'TariffCode' => $row['tariffcode'],
        //             'StorageConditions' => $row['storageconditions'],
        //             'Shipment' => $row['shipment'],
        //             'ShelfLifeMonths' => $row['shelflifemonths'],
        //         ];

        //         $productInformationInserts = [];
        //         foreach ($attributes as $code => $detail) {
        //             if ($detail) {
        //                 $attribute = ProductAttribute::updateOrCreate(
        //                     ['code' => $code, 'product_type_id' => $this->type],
        //                     ['name' => $code, 'created_at' => now()->toDateTimeString(), 'created_by' => 1]
        //                 );

        //                 $productInformationInserts[] = [
        //                     'product_model_id' => $model->id,
        //                     'product_attribute_id' => $attribute->id,
        //                     'detail' => $detail,
        //                     'status' => 1,
        //                     'created_at' => now()->toDateTimeString(),
        //                     'created_by' => 1
        //                 ];
        //             }
        //         }

        //         if (!empty($productInformationInserts)) {
        //             ProductInformation::insert($productInformationInserts);
        //         }

        //         // $movedCount++;
        //         // if ($movedCount % 100 == 0) {
        //         //     Log::info("Moved {$movedCount} rows.");
        //         // }

        //         // DB::commit();
        //         // Log::info('Products imported successfully.');
        //     } catch (\Throwable $e) {
        //         DB::rollBack();
        //         Log::error("Import products failed: " . $e->getMessage());
        //         return redirect()->back()->with('error', 'Import products failed. Please try again.');
        //     }
    }



    public function headingRow(): int
    {
        return 1;
    }

    public function chunkSize(): int
    {
        return 400;
    }

    public function batchSize(): int
    {
        return 5600;
    }
}
  // StagingProduct::insert([
            //     'erp_sku' => $row['erp_sku'],
            //     'description' => $row['description'],
            //     'cas' => $row['cas'] ?? NULL,
            //     'packsize' => $row['packsize'],
            //     'last_direct_cost' => is_numeric($row['last_direct_cost'] ?? null) ? (float) $row['last_direct_cost'] : 0,
            //     'user' => is_numeric($row['user'] ?? null) ? (float) $row['user'] : 0,
            //     'dealer' => is_numeric($row['dealer'] ?? null) ? (float)$row['dealer'] : 0,
            //     'wholesales' => is_numeric($row['wholesales'] ?? null) ? (float) $row['wholesales'] : 0,
            //     'partner' => is_numeric($row['partner'] ?? null) ? (float)$row['partner'] : 0,
            //     'linearformula' => $row['linearformula'] ?? NULL,
            //     'formulaweight' => $row['formulaweight'] ?? NULL,
            //     'density' => $row['density'] ?? NULL,
            //     'flashpoint' => $row['flashpoint'] ?? NULL,
            //     'meltingpoint' => $row['meltingpoint'] ?? NULL,
            //     'boilingpoint' => $row['boilingpoint'] ?? NULL,
            //     'unnumber' => $row['unnumber'],
            //     'hazardclass' => $row['hazardclass'],
            //     'packinggroup' => $row['packinggroup'],
            //     'tariffcode' => $row['tariffcode'],
            //     'storageconditions' => $row['storageconditions'],
            //     'shipment' => $row['shipment'],
            //     'shelflifemonths' => $row['shelflifemonths'],
            //     'item_id' => $row['id'],
            // ]);