<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Business;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        
        return match ($user->role) {
            'admin' => $this->adminDashboard(),
            'business' => $this->businessDashboard($user),
            default => $this->userDashboard($user),
        };
    }

    private function adminDashboard()
    {
        $stats = [
            'total_users' => User::count(),
            'total_courses' => Course::count(),
            'total_businesses' => Business::count(),
            'total_revenue' => DB::table('business_courses')
                ->join('courses', 'business_courses.course_id', '=', 'courses.id')
                ->sum(DB::raw('purchased_seats * price')),
        ];

        $recentEnrollments = DB::table('course_user')
            ->join('users', 'course_user.user_id', '=', 'users.id')
            ->join('courses', 'course_user.course_id', '=', 'courses.id')
            ->select('users.name', 'courses.title', 'course_user.created_at')
            ->orderByDesc('course_user.created_at')
            ->limit(5)
            ->get();

        $popularCourses = Course::withCount('users')
            ->orderByDesc('users_count')
            ->limit(5)
            ->get();

        return view('dashboard.admin', compact('stats', 'recentEnrollments', 'popularCourses'));
    }

    private function businessDashboard($user)
    {
        $business = $user->business;
        
        $stats = [
            'total_employees' => $business->employees()->count(),
            'active_courses' => $business->purchasedCourses()->count(),
            'completion_rate' => $this->calculateCompletionRate($business),
        ];

        $recentEnrollments = DB::table('course_user')
            ->join('users', 'course_user.user_id', '=', 'users.id')
            ->join('courses', 'course_user.course_id', '=', 'courses.id')
            ->where('users.business_id', $business->id)
            ->select('users.name', 'courses.title', 'course_user.created_at')
            ->orderByDesc('course_user.created_at')
            ->limit(5)
            ->get();

        $courseProgress = $business->purchasedCourses()
            ->withCount(['users as completion_rate' => function($query) use ($business) {
                $query->where('users.business_id', $business->id)
                    ->where('completed', true);
            }])
            ->get()
            ->map(function($course) use ($business) {
                $total = $business->employees()->count();
                $course->completion_rate = $total > 0 ? 
                    round(($course->completion_rate / $total) * 100) : 0;
                return $course;
            });

        return view('dashboard.business', compact('stats', 'recentEnrollments', 'courseProgress'));
    }

    private function userDashboard($user)
    {
        $enrolledCourses = $user->enrolledCourses()
            ->withPivot(['progress', 'completed'])
            ->get();

        $completedCourses = $enrolledCourses->where('pivot.completed', true);
        $inProgressCourses = $enrolledCourses->where('pivot.completed', false);
        $certificates = $user->certificates()->with('course')->latest()->get();

        $stats = [
            'courses_in_progress' => $inProgressCourses->count(),
            'completed_courses' => $completedCourses->count(),
            'total_certificates' => $certificates->count(),
        ];

        return view('dashboard.user', compact(
            'stats',
            'enrolledCourses',
            'completedCourses',
            'inProgressCourses',
            'certificates'
        ));
    }

    private function calculateCompletionRate($business)
    {
        $totalEnrollments = DB::table('course_user')
            ->join('users', 'users.id', '=', 'course_user.user_id')
            ->where('users.business_id', $business->id)
            ->count();

        $completedCourses = DB::table('course_user')
            ->join('users', 'users.id', '=', 'course_user.user_id')
            ->where('users.business_id', $business->id)
            ->where('completed', true)
            ->count();

        return $totalEnrollments > 0 ? 
            round(($completedCourses / $totalEnrollments) * 100) : 0;
    }
} 