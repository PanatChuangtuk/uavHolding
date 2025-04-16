<?php

namespace App\Http\Controllers;

use App\Http\Controllers\MainController;
use App\Models\{Common, Language};
use Illuminate\Support\Facades\{Auth};

class TermController extends MainController
{
    public function termIndex()
    {
        if (!Auth::guard('member')->check()) {
            return redirect()->route('login', ['lang' => app()->getLocale()]);
        }
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
        return view('term-condition', compact('condition'));
    }
}
