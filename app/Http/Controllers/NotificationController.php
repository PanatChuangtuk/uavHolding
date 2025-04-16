<?php

namespace App\Http\Controllers;

use App\Http\Controllers\MainController;
use App\Models\{Notification, Language};
use Illuminate\Support\Facades\{View, Auth, Http};

class NotificationController extends MainController
{
    public function notificationIndex()
    {
        $locale = app()->getLocale();
        $language = Language::where('code', $locale)->first();
        $userId = Auth::guard('member')->user()->id ?? null;
        $notification = Notification::where('member_id', $userId)->orderBy('created_at', 'desc')->get();
        return view('notification', compact('notification', 'language'));
    }
    public function notificationUpdate($lang, $id)
    {
        $notification = Notification::findOrFail($id);
        $notification->updated_at = now();
        $notification->save();
        return response()->json(['message' => 'News updated successfully']);
    }
}
