<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\{Controller};
use App\Models\{Notification};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function getNotificationCount($lang)
    {
        $notificationCount = Notification::where('member_id', Auth::guard('member')->user()->id)
            ->whereNull('updated_at')
            ->count();

        return response()->json(['count' => $notificationCount]);
    }
}
