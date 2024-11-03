<?php

namespace App\Http\Requests\Business;

use Illuminate\Foundation\Http\FormRequest;

class CourseAssignRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'user_ids' => ['required', 'array'],
            'user_ids.*' => ['exists:users,id']
        ];
    }
} 