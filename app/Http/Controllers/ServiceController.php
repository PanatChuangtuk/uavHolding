<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Common, Language};

class ServiceController extends MainController
{
    function serviceIndex()
    {
        $locale = app()->getLocale();
        $language = Language::where('code', $locale)->first();
        $service = Common::select(
            'common.*',
            'common_content.*',
            'common_content.name as content_name',
        )
            ->where('common.id', 1)
            ->join('common_content', 'common_content.common_id', '=', 'common.id')
            ->where('common_content.language_id', $language->id)->where('common_content.common_id', 1)->first();
        return view('service', compact('service'));
    }
}
