<?php

namespace App\Http\Controllers;

use App\Http\Controllers\MainController;
use App\Models\{Common, Language};
use Illuminate\Support\Facades\{Auth};

class TermUserController extends MainController
{
    public function termIndex()
    {
        $locale = app()->getLocale();
        $language = Language::where('code', $locale)->first();
        $condition = Common::select(
            'common.*',
            'common_content.*',
            'common_content.name as content_name',
        )
            ->where('common.id', 3)
            ->join('common_content', 'common_content.common_id', '=', 'common.id')
            ->where('common_content.language_id', $language->id)->where('common_content.common_id', 3)->first();
        return view('term-condition-user', compact('condition'));
    }
}
