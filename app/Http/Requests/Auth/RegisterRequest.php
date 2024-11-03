<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                'unique:users'
            ],
            'password' => [
                'required',
                'confirmed',
                Password::min(8)
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
                    ->uncompromised()
            ],
            'terms' => ['required', 'accepted']
        ];
    }

    public function messages(): array
    {
        return [
            'terms.required' => 'You must accept the Terms of Service and Privacy Policy.',
            'terms.accepted' => 'You must accept the Terms of Service and Privacy Policy.',
            'password.uncompromised' => 'The given password has appeared in a data leak. Please choose a different password.'
        ];
    }
} 