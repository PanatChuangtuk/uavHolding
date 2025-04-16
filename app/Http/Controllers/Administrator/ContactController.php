<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use App\Models\{Language, Contact, ContactContent};
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, Storage, Validator};

class ContactController extends Controller
{
    private $main_menu = 'contact';
    public function index(Request $request)
    {
        $main_menu = $this->main_menu;
        $query = $request->input('query');
        $status = $request->input('status');

        $contactQuery = Contact::with('content');

        if ($query) {
            $contactQuery->where('name', 'LIKE', "%{$query}%");
        }

        if ($status) {
            $statusValue = ($status === 'active') ? 1 : 0;
            $contactQuery->where('status', $statusValue);
        }

        $contact = $contactQuery->paginate(10)->appends([
            'query' => $query,
            'status' => $status,
        ]);

        return view('administrator.contact.index', compact('contact', 'query', 'status', 'main_menu'));
    }

    public function add()
    {
        $main_menu = $this->main_menu;
        $language = Language::get();
        return view('administrator.contact.add', compact('language', 'main_menu'));
    }

    public function edit($id)
    {
        $main_menu = $this->main_menu;
        $language = Language::all();
        $contact = Contact::find($id);
        $contactContent = ContactContent::where('contact_id', $contact->id)->get()->keyBy('language_id');

        return view('administrator.contact.edit', compact('contact', 'contactContent', 'language', 'main_menu'));
    }

    public function submit(Request $request)
    {
        $languages = Language::all();

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
        $rules['phone'] = 'required|digits:10';
        $rules['image'] = 'required';
        $messages['image.required'] = 'กรุณาใส่รูปภาพ';
        $messages['phone.required'] = 'กรุณาป้อนหมายเลขโทรศัพท์มือถือ';
        $messages['phone.digits'] = 'หมายเลขโทรศัพท์มือถือต้องมี 10 หลัก';
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        $createdAt = Carbon::now();
        $createdBy = Auth::user()->id;
        $nameArray = $request->input('name');
        $addressArray = $request->input('address');
        $phone = $request->input('phone');
        $fax = $request->input('fax');
        $email = $request->input('email');


        $imageNames = null;
        if ($request->hasFile('image')) {
            $imageNames = $this->uploadsImage($request->file('image'), 'contact');
        }
        $contact = Contact::create([
            'name' => $nameArray[1] ?? $nameArray[2],
            'phone' => $phone,
            'fax' => $fax,
            'email' => $email,
            'image' => $imageNames,
            'created_at' => $createdAt,
            'created_by' => $createdBy
        ]);

        foreach ($languages as $language) {
            ContactContent::create([
                'contact_id' => $contact->id,
                'language_id' => $language->id,
                'name' => $nameArray[$language->id] ?? null,
                'address' => $addressArray[$language->id] ?? null,
            ]);
        }

        return redirect()->back()->with('success', 'ข้อมูลถูกสร้างเรียบร้อยแล้ว');
    }

    public function update(Request $request, $id)
    {
        $languages = Language::all();

        $nameArray = $request->input('name');
        $addressArray = $request->input('address');
        $phone = $request->input('phone');
        $fax = $request->input('fax');
        $email = $request->input('email');
        $updatedBy = Auth::user()->id;
        $contact = Contact::find($id);
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
        $rules['phone'] = 'required|digits:10';
        $rules['image'] = $contact->image ? 'nullable' : 'required';
        $messages['image.required'] = 'กรุณาใส่รูปภาพ';
        $messages['phone.required'] = 'กรุณาป้อนหมายเลขโทรศัพท์มือถือ';
        $messages['phone.digits'] = 'หมายเลขโทรศัพท์มือถือต้องมี 10 หลัก';
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        $imageName = $contact->image;
        if ($request->hasFile('image')) {
            $filename = $this->uploadsImage($request->file('image'), 'contact');

            if (isset($contact) && $contact->image !== $filename) {
                $oldImagePath = str_replace(asset('public'), 'file/contact/', $contact->image);
                $relativeUrl = ltrim(str_replace(url(''), '', $oldImagePath), '/');
                Storage::disk('public')->delete('file/contact/' . $relativeUrl);

                $contact->update([
                    'name' => $nameArray[1] ?? $nameArray[2],
                    'image' => $filename,
                    'phone' => $phone,
                    'fax' => $fax,
                    'email' => $email,
                    'updated_at' => now(),
                    'updated_by' => $updatedBy
                ]);
            }
        } else {
            $contact->update([
                'name' => $nameArray[1] ?? $nameArray[2],
                'image' => $imageName,
                'phone' => $phone,
                'fax' => $fax,
                'email' => $email,
                'updated_at' => now(),
                'updated_by' => $updatedBy
            ]);
        }

        foreach ($languages as $language) {
            $contactContent = ContactContent::where('contact_id', $contact->id)
                ->where('language_id', $language->id)
                ->first();

            if ($contactContent) {
                $contactContent->update([
                    'name' => $nameArray[$language->id] ?? null,
                    'address' => $addressArray[$language->id] ?? null,
                ]);
            }
        }

        return redirect()->back()->with('success', 'ข้อมูลถูกบันทึกเรียบร้อยแล้ว');
    }

    public function destroy($id, Request $request)
    {
        $contact = Contact::findOrFail($id);
        $contact->delete();

        $currentPage = $request->query('page', 1);

        return redirect()->route('administrator.contact', ['page' => $currentPage])->with([
            'success' => 'Contact deleted successfully!',
            'id' => $id
        ]);
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids');

        if (is_array($ids) && count($ids) > 0) {
            Contact::whereIn('id', $ids)->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Selected contact have been deleted successfully.',
                'deleted_ids' => $ids
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'No contact selected for deletion.'
        ], 400);
    }

    public function deleteImage($id)
    {
        $contact = contact::find($id);

        if ($contact) {
            $oldImagePath = str_replace(asset('public'), 'file/contact/', $contact->image);
            $relativeUrl = ltrim(str_replace(url(''), '', $oldImagePath), '/');

            if (Storage::disk('public')->exists('file/contact/' . $relativeUrl)) {
                Storage::disk('public')->delete('file/contact/' . $relativeUrl);
            }

            $contact->update([
                'image' => null,
                'updated_at' => now(),
                'updated_by' => Auth::user()->id
            ]);

            return response()->json(['success' => 'Image deleted successfully']);
        }
    }
}
