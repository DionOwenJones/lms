<?php

namespace App\Http\Controllers;

use App\Http\Requests\Profile\UpdateProfileRequest;
use App\Http\Requests\Profile\UpdatePasswordRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
            'timezones' => \DateTimeZone::listIdentifiers(),
            'countries' => \App\Models\Country::pluck('name', 'id'),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(UpdateProfileRequest $request): RedirectResponse
    {
        $user = $request->user();

        // Handle profile photo upload
        if ($request->hasFile('photo')) {
            $user->updateProfilePhoto($request->file('photo'));
        }

        $user->fill($request->validated());

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
            $user->sendEmailVerificationNotification();
        }

        $user->save();

        return back()->with('status', 'profile-updated');
    }

    /**
     * Update the user's password.
     */
    public function updatePassword(UpdatePasswordRequest $request): RedirectResponse
    {
        $request->user()->update([
            'password' => Hash::make($request->get('password')),
        ]);

        return back()->with('status', 'password-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        auth()->logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    /**
     * Update user notification preferences.
     */
    public function updateNotifications(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'email_notifications' => 'boolean',
            'push_notifications' => 'boolean',
            'notification_types.*' => 'boolean',
        ]);

        $request->user()->updateNotificationPreferences($validated);

        return back()->with('status', 'notifications-updated');
    }

    /**
     * Generate new two factor authentication recovery codes.
     */
    public function generateTwoFactorCodes(Request $request): RedirectResponse
    {
        $request->user()->generateTwoFactorRecoveryCodes();

        return back()->with('status', 'recovery-codes-generated');
    }

    /**
     * Enable two factor authentication.
     */
    public function enableTwoFactor(Request $request): RedirectResponse
    {
        $request->validate([
            'code' => ['required', 'string'],
        ]);

        if (!$request->user()->enableTwoFactorAuthentication($request->code)) {
            return back()->withErrors(['code' => 'The provided two factor code was invalid.']);
        }

        return back()->with('status', 'two-factor-enabled');
    }
} 