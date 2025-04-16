<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Http;

class UnitOfMeasureIds extends Model
{
    public $timestamps = false;

    public static function getUnitOfMeasureIds()
    {
        $url = 'http://183.88.232.152:14148/bc14uvtst/api/amco/app/v1.0/companies(66e8e884-a363-4012-aef1-ce9c4855f09f)/GetUnitOfMeasureIds';

        $response = Http::withBasicAuth('web', 'Nav#1234')->get($url);

        if ($response->successful()) {
            $data = $response->json();
            if (isset($data['value'])) {
                $unitOfMeasureIds = $data['value'];

                // // ตัวอย่างการวนลูปและแสดงข้อมูล
                // foreach ($unitOfMeasureIds as $item) {
                //     echo 'Unit Code: ' . $item['unitOfMeasureCode'] . '<br>';
                //     echo 'Unit Description: ' . $item['unitOfMeasureDesc'] . '<br>';
                //     echo 'Unit ID: ' . $item['unitOfMeasureId'] . '<br><br>';
                // }

                return $unitOfMeasureIds;
            }
        }

        return null;
    }
}
