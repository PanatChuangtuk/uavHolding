<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithBatchInserts;

class ProductExport implements FromQuery, WithHeadings, WithMapping, WithChunkReading, WithBatchInserts
{
    protected $query;
    
    protected $attributeMapping = [
        'CAS' => [1, 29],
        'Tariff Code' => [2, 14],
        'Storage Conditions' => [3, 15, 32],
        'Shipment' => [4, 16],
        'Shelf Life' => [5, 31],
        'UN Number' => [6],
        'Hazard Class' => [7],
        'Packing Group' => [8],
        'Formula' => [9, 33],
        'Formula Weight' => [10, 34],
        'Density' => [11, 37],
        'Melting Point' => [12, 35],
        'Boiling Point' => [13, 36],
        'Method of Transport' => [30],
        'Sensitivity' => [38],
        'Form' => [39],
        'Solubility' => [40],
        'Synonyms' => [41]
    ];

    public function __construct($query)
    {
        $this->query = $query;
    }

    public function query()
    {
        return $this->query->with([
            'productModel',
            'productSizes.productUnitValue',
            'productPrices',
            'productModel.productInformations'
        ]);
    }

    protected function extractSkuSuffix($productModelCode, $sku) {
        if (strpos($productModelCode, 'PIN-') === 0) {
            return $productModelCode;
        } else {
            $skuParts = explode('.', $sku);
            return end($skuParts);
        }
    }
    
    public function map($product): array
    {
        try {
            $productPrices = $product->productPrices->keyBy('member_group_id');
            $productInfo = $product->productModel->productInformations->keyBy('product_attribute_id');
    
            $skuSuffix = $this->extractSkuSuffix($product->productModel->code, $product->sku);
            $erpSku = (strpos($product->productModel->code, 'PIN-') === 0) ? $skuSuffix : $product->productModel->code . '.' . $skuSuffix;
    
            $row = [
                'ERP SKU' => $erpSku ?? '',
                'Description' => $product->productModel?->name ?? '',
                'PackSize' => $product->productSizes->first()?->productUnitValue?->name ?? '',
                'Last Direct Cost' => $product->cost_price ?? '',
                'Partner' => $this->formatPrice($productPrices->get(4)?->price),
                'Wholesaler' => $this->formatPrice($productPrices->get(3)?->price),
                'Dealer' => $this->formatPrice($productPrices->get(2)?->price),
                'User' => $this->formatPrice($productPrices->get(1)?->price),
            ];

            foreach ($this->attributeMapping as $columnName => $attributeIds) {
                $row[$columnName] = $this->getAttributeValue($productInfo, $attributeIds);
            }

            return $row;
        } catch (\Exception $e) {
            \Log::error('Error in ProductExport mapping:', [
                'product_id' => $product->id ?? 'unknown',
                'error' => $e->getMessage()
            ]);
            
            return array_fill(0, count($this->headings()), '');
        }
    }

    protected function formatPrice($price): string
    {
        return $price ? number_format($price, 2, '.', '') : '0.00';
    }

    protected function getAttributeValue($productInfo, $attributeIds): string
    {
        $latestInfo = null;
        foreach ($attributeIds as $id) {
            $info = $productInfo->get($id);
            if ($info) {
                if (!$latestInfo || $info->created_at > $latestInfo->created_at) {
                    $latestInfo = $info;
                }
            }
        }
        return $latestInfo ? $latestInfo->detail : '';
    }

    public function headings(): array
    {
        return array_merge(
            [
                'ERP SKU',
                'Name',
                'PackSize',
                'Last Direct Cost',
                'Partner',
                'Wholesales',
                'Dealer',
                'User',
            ],
            array_keys($this->attributeMapping)
        );
    }

    public function chunkSize(): int
    {
        return 5000;
    }

    public function batchSize(): int
    {
        return 1000;
    }
}