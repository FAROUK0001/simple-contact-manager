<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class ContactController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __construct(){
        $this->authorizeResource(Contact::class, 'contact');
    }
    public function index(Request $request){
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
        // Validate and store the contact data
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'notes' => 'nullable|string|max:20',
            'photo' => 'nullable|image|max:2048', // Optional photo upload
        ]);
        if ($request->hasFile('photo')) {
            // Store the photo and get its path
            $validatedData['photo'] = $request->file('photo')->store('contacts', 'public');
        }
        // Here you would typically save the contact to the database
        $request->user()->contacts()->create($validatedData);
        // For demonstration, we'll just return the validated data
        return redirect()->route('contact.index')->with('success', 'Contact created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return Inertia::render('contact/Show', [
            'contact' => request()->user()->contacts()->findOrFail($id),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Contact $contact)
    {
        return Inertia::render('contact/Edit', [
            'contact' => $contact,]);
    }


    /**
     * Update the specified resource in storage.
     */

    public function update(Request $request, Contact $contact)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'photo' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            // Delete an old photo if it exists
            if ($contact->photo) {
              //      dd(Storage::disk('public'));
                Storage::disk('public')->delete($contact->photo);
            }
            // Store a new photo
            $data['photo'] = $request->file('photo')->store('contacts', 'public');
        } else {
            unset($data['photo']); // do not change an existing photo
        }

        $contact->update($data);

        return redirect()->route('contact.edit', $contact->id)
            ->with('success', 'Contact updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Here you would typically delete the contact from the database
        $contact = request()->user()->contacts()->findOrFail($id);
        $contact->delete();
        return redirect()->route('contact.index')->with('success', 'Contact deleted successfully!');
    }

// ContactController.php
    public function inlineUpdate(Request $request, string $id)
    {

        $contact = $request->user()->contacts()->findOrFail($id);

        $data = $request->only(['phone', 'notes']);

        $validated = validator($data, [
            'phone' => 'nullable|string|max:20',
            'notes' => 'nullable|string|max:255',
        ])->validate();

        $contact->update($validated);

        return redirect()->back();
    }



}
