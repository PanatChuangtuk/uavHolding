<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AddressLocationController extends Controller
{
    public function getProvinces(Request $request)
    {
        $language = app()->getLocale();
        $provinces = DB::table('provinces')->select('id', 'name_th', 'name_en')->get();

        $provinces = $provinces->map(function ($province) use ($language) {
            $province->name = ($language == 'en') ? $province->name_en : $province->name_th;
            return $province;
        });

        return response()->json($provinces);
    }

    public function getDistricts($province_id, Request $request)
    {
        $language = app()->getLocale();
        $districts = DB::table('amphures')->select('id', 'name_th', 'name_en', 'province_id')
            ->where('province_id', $province_id)->get();

        $districts = $districts->map(function ($district) use ($language) {
            $district->name = ($language == 'en') ? $district->name_en : $district->name_th;
            return $district;
        });

        return response()->json($districts);
    }

    public function getSubdistricts($district_id, Request $request)
    {
        $language = app()->getLocale();
        $subdistricts = DB::table('tambons')
            ->select('id', 'zip_code', 'name_th', 'name_en', 'amphure_id')
            ->where('amphure_id', $district_id)
            ->get();

        $subdistricts = $subdistricts->map(function ($subdistrict) use ($language) {
            $subdistrict->name = ($language == 'en') ? $subdistrict->name_en : $subdistrict->name_th;
            return $subdistrict;
        });

        return response()->json($subdistricts);
    }
}
