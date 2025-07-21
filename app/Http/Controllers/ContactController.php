<?php

namespace App\Http\Controllers;

use App\Jobs\SendContactNotification;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class ContactController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $contacts = $request->user()->contacts()
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%")
                        ->orWhere('notes', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(7)
            ->withQueryString();

        return Inertia::render('contact/Index', [
            'contacts' => $contacts,
            'search' => $search,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('contact/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create', Contact::class);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'notes' => 'nullable|string|max:255',
            'photo' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('contacts', 'public');
        }

        $contact = $request->user()->contacts()->create($validated);

        // Send notification on create
        $contactData = $contact->toArray();
        $email = $request->user()->email;
        dispatch(new SendContactNotification($contactData, 'created', $email));

        return redirect()->route('contact.index')->with('success', 'Contact created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, $id)
    {
        $contact = $request->user()->contacts()->findOrFail($id);

        $this->authorize('view', $contact);

        return Inertia::render('contact/Show', [
            'contact' => $contact,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, $id)
    {
        $contact = $request->user()->contacts()->findOrFail($id);

        $this->authorize('update', $contact);

        return Inertia::render('contact/Edit', [
            'contact' => $contact,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $contact = $request->user()->contacts()->findOrFail($id);

        $this->authorize('update', $contact);

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'notes' => 'nullable|string|max:255',
            'photo' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            if ($contact->photo) {
                Storage::disk('public')->delete($contact->photo);
            }
            $data['photo'] = $request->file('photo')->store('contacts', 'public');
        } else {
            unset($data['photo']);
        }

        $contactData = $contact->toArray();
        $email = $request->user()->email;

        $contact->update($data);

        dispatch(new SendContactNotification($contactData, 'updated', $email));

        return redirect()->route('contact.edit', $contact->id)
            ->with('success', 'Contact updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, $id)
    {
        $contact = $request->user()->contacts()->findOrFail($id);

        $this->authorize('delete', $contact);

        $contactData = $contact->toArray();
        $email = $request->user()->email;

        $contact->delete();

        dispatch(new SendContactNotification($contactData, 'deleted', $email));

        return redirect()->route('contact.index')->with('success', 'Contact deleted successfully!');
    }

    /**
     * Inline update for phone and notes.
     */
    public function inlineUpdate(Request $request, $id)
    {
        $contact = $request->user()->contacts()->findOrFail($id);

        $this->authorize('update', $contact);

        $data = $request->only(['phone', 'notes']);

        $validated = validator($data, [
            'phone' => 'nullable|string|max:20',
            'notes' => 'nullable|string|max:255',
        ])->validate();

        $contactData = $contact->toArray();
        $email = $request->user()->email;

        $contact->update($validated);

        dispatch(new SendContactNotification($contactData, 'updated', $email));

        return redirect()->back();
    }

    private function authorize(string $string, string $class)
    {
    }
}
