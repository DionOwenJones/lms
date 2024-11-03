<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class EmailVerificationRequest extends FormRequest
{
    public function authorize(): bool
    {
        if (!hash_equals(
            (string) $this->route('id'),
            (string) $this->user()->getKey()
        )) {
            return false;
        }

        if (!hash_equals(
            (string) $this->route('hash'),
            sha1($this->user()->getEmailForVerification())
        )) {
            return false;
        }

        return true;
    }

    public function rules(): array
    {
        return [];
    }
} 