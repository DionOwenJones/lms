<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CourseRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'category_id' => ['required', 'exists:categories,id'],
            // ... other rules
        ];
    }
} 