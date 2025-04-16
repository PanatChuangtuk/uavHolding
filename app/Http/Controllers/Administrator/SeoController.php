<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use App\Models\{Language, Seo, SeoContent};
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Enum\AboutEnum;
use Illuminate\Support\Facades\{Auth, Validator};

class SeoController extends Controller
{
    private $main_menu = 'setting';
    public function index(Request $request)
    {
        $query = $request->input('query');
        $status = $request->input('status');
        $seoQuery  = Seo::query();
        if ($query) {
            $seoQuery->where('tag_title', 'LIKE', "%{$query}%")
                ->orWhere('tag_description', 'LIKE', "%{$query}%")
                ->orWhere('tag_keywords', 'LIKE', "%{$query}%");
        }

        if ($status) {
            $statusValue = ($status === 'active') ? 1 : 0;
            $seoQuery->where('status', $statusValue);
        }

        $seo = $seoQuery->paginate(10)->appends([
            'query' => $query,
            'status' => $status,
        ]);

        $main_menu = $this->main_menu;

        return view('administrator.seo.index', compact('seo', 'query', 'status', 'main_menu'));
    }

    public function edit($id)
    {
        $main_menu = $this->main_menu;
        $languages = Language::get();
        $seo = Seo::find($id);
        $seoContent = SeoContent::where('seo_id', $id)->get()->keyBy('language_id');
        return view('administrator.seo.edit', compact('languages', 'seo', 'seoContent', 'main_menu'));
    }

    public function update(Request $request, $id)
    {
        // dd($request->all());
        $tag_title = $request->input('tag_title');
        $tag_description = $request->input('tag_description');
        $tag_keywords = $request->input('tag_keywords');
        $seo = Seo::find($id);
        $languages = Language::all();

        foreach ($languages as $language) {
            $seoContent = SeoContent::where('seo_id', $seo->id)
                ->where('language_id', $language->id)
                ->first();
            if ($seoContent) {
                $seoContent->update([
                    'tag_title' => $tag_title[$language->id] ?? $seoContent->tag_title,
                    'tag_description' =>  $tag_description[$language->id] ?? $seoContent->tag_description,
                    'tag_keywords' =>  $tag_keywords[$language->id] ?? $seoContent->tag_keywords,
                ]);
            }
        }
        return redirect()->back()->with('success', 'ข้อมูลถูกบันทึกเรียบร้อยแล้ว');
    }
}
