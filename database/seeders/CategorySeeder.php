<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $defaultCategories = [
            'Web Development',
            'Mobile Development',
            'Data Science',
            'Business',
            'Design',
            'Marketing'
        ];

        foreach ($defaultCategories as $category) {
            Category::firstOrCreate(
                ['slug' => Str::slug($category)],
                [
                    'name' => $category,
                    'description' => "Courses related to $category"
                ]
            );
        }
    }
} 