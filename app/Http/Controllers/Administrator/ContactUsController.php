<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use App\Models\ContactUs;
use Illuminate\Http\Request;

class ContactUsController extends Controller
{
    private $main_menu = 'contact';

    public function index(Request $request)
    {
        $main_menu = $this->main_menu;
        $status = $request->input('status');
        $query = ContactUs::query();

        // ค้นหาด้วยชื่อ อีเมล หรือเบอร์โทร
        if ($request->has('query')) {
            $search = $request->query('query');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone_number', 'like', "%{$search}%");
            });
        }

        // กรองตามสถานะ (active / inactive)
        if ($status !== null) {
            $statusValue = ($status === 'active') ? 1 : 0;
            $query->where('status', $statusValue);
        }

        // ใช้ clone() เพื่อป้องกันปัญหากับ appends()
        $contacts = clone $query;
        $contacts = $contacts->latest()->paginate(10)->appends([
            'query' => $request->query('query'),
            'status' => $status,
        ]);

        return view('administrator.list-contact.index', compact('contacts', 'main_menu'));
    }

    public function add()
    {
        $main_menu = $this->main_menu;
        return view('administrator.list-contact.add', compact('main_menu'));
    }

    public function edit($id)
    {
        $main_menu = $this->main_menu;
        $contact = ContactUs::findOrFail($id);

        return view('administrator.list-contact.edit', compact('contact', 'main_menu'));
    }

    public function submit(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone_number' => 'nullable|string|max:15',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
            'image' => 'nullable|image|max:2048'
        ]);

        if ($request->hasFile('image')) {
            $validatedData['image'] = $request->file('image')->store('contact_images', 'public');
        }

        ContactUs::create($validatedData);

        return redirect()->route('administrator.list-contact.index')->with('success', 'Contact added successfully.');
    }

    public function update(Request $request, $id)
    {
        $contact = ContactUs::findOrFail($id);

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone_number' => 'nullable|string|max:15',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
            'image' => 'nullable|image|max:2048'
        ]);

        if ($request->hasFile('image')) {
            if ($contact->image) {
                \Storage::disk('public')->delete($contact->image);
            }
            $validatedData['image'] = $request->file('image')->store('contact_images', 'public');
        }

        $contact->update($validatedData);

        return redirect()->route('administrator.list-contact.index')->with('success', 'Contact updated successfully.');
    }

    public function destroy($id)
    {
        $contact = ContactUs::findOrFail($id);

        if ($contact->image) {
            \Storage::disk('public')->delete($contact->image);
        }

        $contact->delete();

        return redirect()->route('administrator.list-contact.index')->with('success', 'Contact deleted successfully.');
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids', []);

        foreach ($ids as $id) {
            $contact = ContactUs::find($id);
            if ($contact && $contact->image) {
                \Storage::disk('public')->delete($contact->image);
            }
            $contact?->delete();
        }

        return response()->json(['message' => 'Contacts deleted successfully.']);
    }

    public function deleteImage($id)
    {
        $contact = ContactUs::findOrFail($id);

        if ($contact->image) {
            \Storage::disk('public')->delete($contact->image);
            $contact->update(['image' => null]);
        }

        return response()->json(['message' => 'Image deleted successfully.']);
    }
    public function updateStatus(Request $request, $id)
    {
        // dd($id);
        $contact = ContactUs::find($id);
        $contact->status = 1;
        $contact->save();

        return response()->json(['success' => true, 'status' => 1]);
    }
}
