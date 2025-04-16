<?php

namespace App\Http\Controllers;

use App\Models\{Contact, Language, ContactUs};
use Illuminate\Support\Facades\{Auth, DB, Validator, Hash};
use Illuminate\Http\Request;

class ContactController extends MainController
{
    function contactIndex()
    {
        $locale = app()->getLocale();
        $language = Language::where('code', $locale)->first();
        $contact = Contact::select(
            'contact.*',
            'contact_content.*',
            'contact_content.name as content_name',
        )
            ->where('contact.id', 1)
            ->join('contact_content', 'contact_content.contact_id', '=', 'contact.id')
            ->where('contact_content.language_id', $language->id)->first();
        return view('contact', compact('contact'));
    }
    public function submit($lang, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'subject' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone_number' => 'required|digits_between:10,15',
            'image' => 'nullable|mimes:jpg,jpeg,png,bmp,gif,heic,heif,tiff,pdf,doc,docx,xls,xlsx|max:2048',
            'message' => 'required',
        ], [
            'name.required' => __('messages.name_required'),
            'subject.required' => __('messages.subject_required'),
            'email.required' => __('messages.email_required'),
            'email.email' => __('messages.email_invalid'),
            'phone_number.required' => __('messages.phone_required'),
            'phone_number.digits_between' => __('messages.phone_invalid'),
            'image.mimes' => __('messages.image_invalid_type'),
            'image.max' => __('messages.image_max_size'),
            'message.required' => __('messages.message_required'),
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }
        $filename = $request->file('image');
        $filename = null;
        if ($request->hasFile('image')) {
            $filename = $this->uploadsImage($request->file('image'), 'contact_us');
        }


        ContactUs::create([
            'name' => $request->name,
            'subject' => $request->subject,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'image' => $filename,
            'message' => $request->message,
            'created_at' => now(),

        ]);

        return redirect()->back()->with('success', __('messages.contact_us_success'));
    }
}
