<?php

namespace App\Http\Controllers;

use App\Http\Controllers\MainController;
use App\Models\{Common, Language};
use Illuminate\Support\Facades\{Auth};

class PrivacyController extends MainController
{
    public function privacyIndex()
    {
        if (!Auth::guard('member')->check()) {
            return redirect()->route('login', ['lang' => app()->getLocale()]);
        }
        $locale = app()->getLocale();
        $language = Language::where('code', $locale)->first();
        $privacy = Common::select(
            'common.*',
            'common_content.*',
            'common_content.name as content_name',
        )
            ->where('common.id', 2)
            ->join('common_content', 'common_content.common_id', '=', 'common.id')
            ->where('common_content.language_id', $language->id)->where('common_content.common_id', 2)->first();

        return view('privacy-policy', compact('privacy'));
    }
}
