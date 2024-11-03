<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Business;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'account_type' => ['required', 'in:individual,business'],
            'business_name' => ['required_if:account_type,business', 'string', 'max:255'],
            'business_email' => ['required_if:account_type,business', 'email', 'max:255'],
            'business_phone' => ['nullable', 'string', 'max:20'],
        ]);

        // Determine user role
        $role = match($request->email) {
            'dionoj@gmail.com' => 'admin',
            $request->account_type === 'business' => 'business',
            default => 'user'
        };

        // Create business if applicable
        $businessId = null;
        if ($role === 'business') {
            $business = Business::create([
                'name' => $request->business_name,
                'contact_email' => $request->business_email,
                'contact_phone' => $request->business_phone,
                'status' => 'active'
            ]);
            $businessId = $business->id;
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $role,
            'business_id' => $businessId
        ]);

        event(new Registered($user));

        Auth::login($user);

        // Redirect based on role
        return match($role) {
            'admin' => redirect()->route('admin.dashboard'),
            'business' => redirect()->route('business.dashboard'),
            default => redirect()->route('dashboard')
        };
    }
} 