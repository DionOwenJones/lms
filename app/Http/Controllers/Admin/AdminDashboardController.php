<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Business;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // Get key statistics
        $stats = [
            'total_courses' => Course::count(),
            'total_businesses' => Business::count(),
            'total_users' => User::count(),
            'active_enrollments' => DB::table('course_user')->count(),
        ];

        // Get recent course enrollments
        $recentEnrollments = DB::table('course_user')
            ->join('users', 'course_user.user_id', '=', 'users.id')
            ->join('courses', 'course_user.course_id', '=', 'courses.id')
            ->select('users.name as user_name', 'courses.title as course_title', 'course_user.created_at')
            ->orderBy('course_user.created_at', 'desc')
            ->limit(5)
            ->get();

        // Get recent business registrations
        $recentBusinesses = Business::orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Get course completion statistics
        $courseStats = Course::select('courses.title')
            ->selectRaw('COUNT(course_user.user_id) as total_enrollments')
            ->selectRaw('AVG(course_user.progress) as average_progress')
            ->leftJoin('course_user', 'courses.id', '=', 'course_user.course_id')
            ->groupBy('courses.id', 'courses.title')
            ->orderBy('total_enrollments', 'desc')
            ->limit(5)
            ->get();

        // Get revenue statistics
        $revenueStats = DB::table('business_courses')
            ->select(DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'))
            ->selectRaw('COUNT(*) as purchases')
            ->selectRaw('SUM(purchased_seats) as total_seats')
            ->groupBy('month')
            ->orderBy('month', 'desc')
            ->limit(6)
            ->get();

        return view('admin.dashboard', compact(
            'stats',
            'recentEnrollments',
            'recentBusinesses',
            'courseStats',
            'revenueStats'
        ));
    }
} 