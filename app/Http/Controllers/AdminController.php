<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\User;
use App\Models\Business;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'total_users' => User::count(),
            'total_courses' => Course::count(),
            'total_businesses' => Business::count(),
            'total_revenue' => DB::table('business_courses')->sum(DB::raw('purchased_seats * courses.price')),
        ];

        $recentUsers = User::latest()->take(5)->get();
        $recentBusinesses = Business::latest()->take(5)->get();
        $popularCourses = Course::withCount('users')->orderByDesc('users_count')->take(5)->get();

        return view('admin.dashboard', compact('stats', 'recentUsers', 'recentBusinesses', 'popularCourses'));
    }

    public function reports()
    {
        $monthlyRevenue = DB::table('business_courses')
            ->join('courses', 'business_courses.course_id', '=', 'courses.id')
            ->select(
                DB::raw('DATE_FORMAT(business_courses.created_at, "%Y-%m") as month'),
                DB::raw('SUM(purchased_seats * courses.price) as revenue')
            )
            ->groupBy('month')
            ->orderBy('month', 'desc')
            ->take(12)
            ->get();

        $courseEnrollments = Course::withCount('users')
            ->orderByDesc('users_count')
            ->take(10)
            ->get();

        $businessActivity = Business::withCount('users')
            ->withCount('courses')
            ->orderByDesc('users_count')
            ->take(10)
            ->get();

        return view('admin.reports', compact('monthlyRevenue', 'courseEnrollments', 'businessActivity'));
    }
} 