<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Business;
use Illuminate\Http\Request;

class AdminBusinessController extends Controller
{
    public function index(Request $request)
    {
        $query = Business::query();

        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('contact_email', 'like', "%{$request->search}%");
            });
        }

        if ($request->status) {
            $query->where('status', $request->status);
        }

        $businesses = $query->withCount('users')->latest()->paginate(10);
        return view('admin.businesses.index', compact('businesses'));
    }

    public function create()
    {
        return view('admin.businesses.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'address' => ['nullable', 'string'],
            'contact_email' => ['required', 'email', 'max:255'],
            'contact_phone' => ['nullable', 'string', 'max:20'],
            'status' => ['required', 'in:active,inactive']
        ]);

        Business::create($validated);

        return redirect()->route('admin.businesses.index')
            ->with('success', 'Business created successfully.');
    }

    public function show(Business $business)
    {
        $business->load(['users', 'courses']);
        return view('admin.businesses.show', compact('business'));
    }

    public function edit(Business $business)
    {
        return view('admin.businesses.edit', compact('business'));
    }

    public function update(Request $request, Business $business)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'address' => ['nullable', 'string'],
            'contact_email' => ['required', 'email', 'max:255'],
            'contact_phone' => ['nullable', 'string', 'max:20'],
            'status' => ['required', 'in:active,inactive']
        ]);

        $business->update($validated);

        return redirect()->route('admin.businesses.index')
            ->with('success', 'Business updated successfully.');
    }

    public function destroy(Business $business)
    {
        if ($business->users()->count() > 0) {
            return redirect()->route('admin.businesses.index')
                ->with('error', 'Cannot delete business with associated users.');
        }

        $business->delete();

        return redirect()->route('admin.businesses.index')
            ->with('success', 'Business deleted successfully.');
    }
} 