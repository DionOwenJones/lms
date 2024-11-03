<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CourseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->isAdmin();
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255', 
                Rule::unique('courses')->ignore($this->course)],
            'description' => ['required', 'string', 'min:50'],
            'category_id' => ['required', 'exists:categories,id'],
            'price' => ['required', 'numeric', 'min:0'],
            'duration' => ['required', 'numeric', 'min:0.5'],
            'status' => ['required', Rule::in(['draft', 'published'])],
            'level' => ['required', Rule::in(['beginner', 'intermediate', 'advanced'])],
            'thumbnail' => [
                $this->isMethod('POST') ? 'required' : 'nullable',
                'image',
                'max:10240', // 10MB max
                'dimensions:min_width=600,min_height=400'
            ],

            // Modules validation
            'modules' => ['required', 'array', 'min:1'],
            'modules.*.title' => ['required', 'string', 'max:255'],
            'modules.*.description' => ['required', 'string'],
            'modules.*.duration' => ['required', 'numeric', 'min:1'],
            'modules.*.order' => ['required', 'integer', 'min:1'],
            'modules.*.content' => [
                $this->isMethod('POST') ? 'required' : 'nullable',
                'file',
                'max:102400', // 100MB max
                'mimes:mp4,pdf,doc,docx,ppt,pptx,zip'
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'The course title is required.',
            'title.unique' => 'This course title already exists.',
            'description.min' => 'The course description must be at least 50 characters.',
            'thumbnail.dimensions' => 'The thumbnail must be at least 600x400 pixels.',
            'modules.required' => 'At least one module is required.',
            'modules.*.title.required' => 'Each module must have a title.',
            'modules.*.duration.min' => 'Module duration must be at least 1 minute.',
            'modules.*.content.mimes' => 'Module content must be a video, PDF, document, presentation, or ZIP file.',
            'modules.*.content.max' => 'Module content must not exceed 100MB.',
        ];
    }

    protected function prepareForValidation(): void
    {
        if ($this->hasFile('thumbnail')) {
            $this->merge([
                'thumbnail_path' => $this->file('thumbnail')->store('courses/thumbnails', 'public')
            ]);
        }

        if ($this->has('modules')) {
            $modules = collect($this->modules)->map(function ($module, $index) {
                if (isset($module['content'])) {
                    $module['content_path'] = $module['content']->store('courses/modules', 'public');
                }
                return $module;
            })->toArray();

            $this->merge(['modules' => $modules]);
        }
    }
} 