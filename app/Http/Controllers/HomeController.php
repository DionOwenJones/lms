<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Category;
use App\Models\Testimonial;
use Illuminate\View\View;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{
    /**
     * Display the homepage with featured content
     */
    public function index(): View
    {
        try {
            $featuredContent = cache()->remember('home_featured_content', 3600, function () {
                return [
                    'featuredCourses' => Course::featured()
                        ->where('status', 'published')
                        ->with('category')
                        ->take(6)
                        ->get(),
                    
                    'popularCategories' => Category::withCount(['courses' => function ($query) {
                        $query->where('status', 'published');
                    }])
                    ->having('courses_count', '>', 0)
                    ->take(4)
                    ->get(),

                    'statistics' => [
                        'courses' => Course::where('status', 'published')->count(),
                        'students' => User::where('role', 'user')->count(),
                        'instructors' => User::where('role', 'instructor')->count(),
                        'completions' => CourseUser::where('completed', true)->count(),
                    ]
                ];
            });

            return view('home', $featuredContent);
        } catch (\Exception $e) {
            report($e);
            return view('home', [
                'featuredCourses' => collect(),
                'popularCategories' => collect(),
                'statistics' => [
                    'courses' => 0,
                    'students' => 0,
                    'instructors' => 0,
                    'completions' => 0
                ]
            ]);
        }
    }

    /**
     * Display the about page
     */
    public function about(): View
    {
        return view('about', [
            'teamMembers' => Cache::remember('team.members', 86400, function () {
                return \App\Models\TeamMember::active()->orderBy('order')->get();
            }),
            'partners' => Cache::remember('partners', 86400, function () {
                return \App\Models\Partner::active()->orderBy('order')->get();
            })
        ]);
    }

    /**
     * Display the contact page
     */
    public function contact(): View
    {
        return view('contact');
    }

    /**
     * Display the terms page
     */
    public function terms(): View
    {
        return view('terms');
    }

    /**
     * Display the privacy policy page
     */
    public function privacy(): View
    {
        return view('privacy');
    }
} 