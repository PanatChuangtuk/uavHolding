<?php

namespace App\Http\Controllers;

use App\Models\{Social, Contact, Language, Member, Notification, Seo};
use Illuminate\Support\Facades\{View, Auth, Http};
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class MainController extends Controller
{
    function __construct(Request $request)
    {
        $locale = app()->getLocale();
        $language = Language::where('code', $locale)->first();
        $currentRoute = $request->segment(2);
        if ($currentRoute  == null) {
            $currentRoute = 'home';
        }
        $seo = Seo::where('type', $currentRoute)->first();
        $contact = Contact::select(
            'contact.*',
            'contact_content.*',
            'contact_content.name as content_name',
        )
            ->where('contact.id', 1)
            ->join('contact_content', 'contact_content.contact_id', '=', 'contact.id')
            ->where('contact_content.language_id', $language->id)->first();
        $social = Social::select('social.*')->where('status', true)->get();
        $userId = Auth::guard('member')->user()->id ?? null;
        $profileUser = Member::join('member_infomation', 'member_infomation.member_id', '=', 'member.id')
            ->select('member.*', 'member_infomation.*')
            ->where('member.id', $userId)
            ->first();
        $cart = session()->get('cart', []) ?? [];
        $totalPrice = 0;
        foreach ($cart as $item) {
            $totalPrice += $item['price'];
        }
        $notification = Notification::where('member_id', $userId)->take(5)->orderBy('created_at', 'desc')->get();
        $notificationCount = Notification::where('member_id', $userId)->whereNull('updated_at')->count();
        View::share('notificationCount', $notificationCount);
        View::share('seo', $seo);
        View::share('language', $language);
        View::share('notification', $notification);
        View::share('cart',  $cart);
        View::share('social', $social);
        View::share('contact', $contact);
        View::share('profileUser', $profileUser);
        View::share('totalPrice', $totalPrice);
    }
    public function uploadsImage($file, $path)
    {
        $filename = substr(Str::uuid(), 0, 5) . '.' . $file->getClientOriginalExtension();
        $file->storeAs('file/' . $path . '/', $filename, 'public');
        return $filename;
    }
    public function sendOtp($phoneNumber, $text)
    {
        $response = Http::asForm()->post('https://www.etracker.cc/bulksms/mesapi.aspx', [
            'user' => 'GMH',
            'pass' => 'w36pbTW)',
            'type' => '5',
            'servid' => 'GMH001',
            'to' => '66' . substr($phoneNumber, 1),
            'from' => 'GramickxDev',
            'text' => $text,
        ]);

        return $response->successful();
    }
}
