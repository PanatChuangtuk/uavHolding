<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use App\Models\{NewsContent, NewsImage, News, Language, Notification, Member};
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Storage, Validator, Auth};
use App\Enum\NotificationType;

class NewsController extends Controller
{
    private $main_menu = 'contents';
    public function index(Request $request)
    {
        $main_menu = $this->main_menu;
        $query = $request->input('query');
        $status = $request->input('status');

        $newsQuery = News::with('images');

        if ($query) {
            $newsQuery->where('name', 'LIKE', "%{$query}%");
        }

        if ($status) {
            $statusValue = ($status === 'active') ? 1 : 0;
            $newsQuery->where('status', $statusValue);
        }

        $news = $newsQuery->paginate(10)->appends([
            'query' => $query,
            'status' => $status,
        ]);
        return view('administrator.news.index', compact('news', 'query', 'status', 'main_menu'));
    }

    public function add()
    {
        $main_menu = $this->main_menu;
        $language = Language::get();
        return view('administrator.news.add', compact('language', 'main_menu'));
    }

    public function edit($id)
    {
        $main_menu = $this->main_menu;
        $languages = Language::all();
        $news = News::find($id);
        $newsContents = NewsContent::where('news_id', $news->id)->get()->keyBy('language_id');
        $newsImage = NewsImage::where('news_id', $news->id)->first();

        return view('administrator.news.edit', compact('news', 'newsContents', 'newsImage', 'languages', 'main_menu'));
    }

    public function submit(Request $request)
    {

        $languages = Language::all();
        $nameArray = $request->input('name');
        $contentArray = $request->input('content');
        $descriptionArray = $request->input('description');
        $slug = $request->input('slug');
        $status = $request->input('status', 0);

        $rules = [];
        $messages = [];
        foreach ($languages as $language) {
            $rules['name.' . $language->id] = 'required_without_all:name.' . implode(',', $languages->pluck('id')->toArray());
            if ($language->name == 'Thai') {
                $messages['name.' . $language->id . '.required_without_all'] = "กรุณากรอกชื่อสำหรับภาษาไทย";
            } elseif ($language->name == 'Eng') {
                $messages['name.' . $language->id . '.required_without_all'] = "กรุณากรอกชื่อสำหรับภาษาอังกฤษ";
            }
        }
        $rules['image'] = 'required';
        $messages['image.required'] = 'กรุณาใส่รูปภาพ';
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }


        $news = News::create([
            'name' => $nameArray[1] ?? $nameArray[2],
            'slug' => $slug,
            'status' => $status,
            'created_at' => Carbon::now(),
            'created_by' =>  Auth::user()->id
        ]);

        foreach ($languages as $language) {
            $newData = [
                'news_id' => $news->id,
                'language_id' => $language->id,
                'name' => $nameArray[$language->id] ?? null,
                'content' => $contentArray[$language->id] ?? null,
                'description' => $descriptionArray[$language->id] ?? null,
            ];
            NewsContent::create($newData);
        }

        $filename = null;
        if ($request->hasFile('image')) {
            $filename = $this->uploadsImage($request->file('image'), 'news');
        }
        NewsImage::create([
            'news_id' => $news->id,
            'language_id' => 1,
            'image' => $filename,
            'sort' => 1
        ]);

        return redirect()->back()->with('success', 'ข้อมูลถูกสร้างเรียบร้อยแล้ว');
    }

    public function update(Request $request, $id)
    {
        $languages = Language::all();
        $nameArray = $request->input('name');
        $contentArray = $request->input('content');
        $descriptionArray = $request->input('description');
        $slug = $request->input('slug');
        $status = $request->input('status', 0);
        $news = News::find($id);
        $newsImage = NewsImage::where('news_id', $news->id)
            ->whereIn('language_id', $languages->pluck('id')->toArray())
            ->first();
        $rules = [];
        $messages = [];
        foreach ($languages as $language) {
            $rules['name.' . $language->id] = 'required_without_all:name.' . implode(',', $languages->pluck('id')->toArray());
            if ($language->name == 'Thai') {
                $messages['name.' . $language->id . '.required_without_all'] = "กรุณากรอกชื่อสำหรับภาษาไทย";
            } elseif ($language->name == 'Eng') {
                $messages['name.' . $language->id . '.required_without_all'] = "กรุณากรอกชื่อสำหรับภาษาอังกฤษ";
            }
        }
        if ($newsImage && $newsImage->image) {
            $rules['image'] = 'nullable';
        } else {
            $rules['image'] = 'required';
        }
        $messages['image.required'] = 'กรุณาใส่รูปภาพ';
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }
        $news->update([
            'name' => $nameArray[1] ?? $nameArray[2],
            'slug' => $slug,
            'status' => $status,
            'updated_by' =>  Auth::user()->id
        ]);

        foreach ($languages as $language) {
            $name = $nameArray[$language->id] ?? null;
            $description = $descriptionArray[$language->id] ?? null;
            $content = $contentArray[$language->id] ?? null;

            $newsContent = NewsContent::where('news_id', $news->id)
                ->where('language_id', $language->id)
                ->first();

            if ($newsContent) {
                $newsContent->update([
                    'name' => $name,
                    'description' => $description,
                    'content' => $content,
                ]);
            } else {
                NewsContent::create([
                    'news_id' => $news->id,
                    'language_id' => $language->id,
                    'name' => $name,
                    'description' => $description,
                    'content' => $content,
                ]);
            }
        }

        if ($request->hasFile('image')) {
            $filename = $this->uploadsImage($request->file('image'), 'news');
            $newsImage = NewsImage::where('news_id', $news->id)
                ->where('language_id', $languages->first()->id)
                ->first();

            if (isset($newsImage) && $newsImage->image !== $filename) {
                $oldImagePath = str_replace(asset('public'), 'file/news/', $newsImage->image);
                $relativeUrl = ltrim(str_replace(url(''), '', $oldImagePath), '/');
                Storage::disk('public')->delete('file/news/' . $relativeUrl);
                $newsImage->update([
                    'image' => $filename,
                    'sort' => 1
                ]);
            } else {
                NewsImage::create([
                    'news_id' => $news->id,
                    'language_id' => 1,
                    'image' => $filename,
                    'sort' => 1
                ]);
            }
        }
        return redirect()->back()->with('success', 'ข้อมูลถูกบันทึกเรียบร้อยแล้ว');
    }

    public function destroy($id, Request $request)
    {
        $news = News::findOrFail($id);
        $news->delete();

        $currentPage = $request->query('page', 1);

        return redirect()->route('administrator.news', ['page' => $currentPage])->with([
            'success' => 'News deleted successfully!',
            'id' => $id
        ]);
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids');

        if (is_array($ids) && count($ids) > 0) {
            News::whereIn('id', $ids)->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Selected news have been deleted successfully.',
                'deleted_ids' => $ids
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'No news selected for deletion.'
        ], 400);
    }

    public function deleteImage($id)
    {
        $news = News::find($id);
        $newsImage = NewsImage::where('news_id', $news->id)->first();

        if ($newsImage) {
            $oldImagePath = str_replace(asset('public'), 'file/news/', $newsImage->image);
            $relativeUrl = ltrim(str_replace(url(''), '', $oldImagePath), '/');

            if (Storage::disk('public')->exists('file/news/' . $relativeUrl)) {
                Storage::disk('public')->delete('file/news/' . $relativeUrl);
            }

            $news->update(['updated_by' => Auth::user()->id]);
            $newsImage->delete();

            return response()->json(['success' => 'Image deleted successfully']);
        }
    }
    public function notifications($id)
    {
        $member = Member::all();
        foreach ($member as $members) {
            Notification::create([
                'member_id' => $members->id,
                'module_id' => $id,
                'module_name' => NotificationType::news->value,
                'created_at' => Carbon::now()
            ]);
        }
        return response()->json(['success' => 'Notification successfully']);
    }
}
