<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Course;
use App\Models\Business;
use App\Models\CourseUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class BusinessController extends Controller
{
    public function employees()
    {
        $user = Auth::user();
        if (!$user || !$user->business) {
            return redirect()->route('dashboard')
                ->with('error', 'Unauthorized access.');
        }

        $business = $user->business;
        
        // Get employees with their course enrollments
        $employees = User::where('business_id', $business->id)
            ->with(['courses' => function($query) {
                $query->select('courses.id', 'title', 'course_user.progress', 'course_user.completed')
                    ->withPivot('progress', 'completed');
            }])
            ->paginate(10);

        // Get courses purchased by the business
        $availableCourses = DB::table('business_courses')
            ->join('courses', 'business_courses.course_id', '=', 'courses.id')
            ->where('business_courses.business_id', $business->id)
            ->select('courses.*', 'business_courses.purchased_seats', 'business_courses.expires_at')
            ->get();

        return view('business.employees', compact('employees', 'availableCourses'));
    }

    public function assignCourse(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'course_id' => 'required|exists:courses,id',
        ]);

        $user = Auth::user();
        if (!$user || !$user->business) {
            return back()->with('error', 'Unauthorized access.');
        }

        $business = $user->business;
        
        // Check if the business has purchased this course
        $businessCourse = DB::table('business_courses')
            ->where('business_id', $business->id)
            ->where('course_id', $validated['course_id'])
            ->first();

        if (!$businessCourse) {
            return back()->with('error', 'Course not available for assignment.');
        }

        // Check if the employee belongs to this business
        $employee = User::where('id', $validated['user_id'])
            ->where('business_id', $business->id)
            ->first();

        if (!$employee) {
            return back()->with('error', 'Invalid employee selected.');
        }

        // Check available seats
        $usedSeats = CourseUser::whereHas('user', function($query) use ($business) {
                $query->where('business_id', $business->id);
            })
            ->where('course_id', $validated['course_id'])
            ->count();

        if ($usedSeats >= $businessCourse->purchased_seats) {
            return back()->with('error', 'No available seats for this course.');
        }

        // Check expiration
        if ($businessCourse->expires_at && now()->greaterThan($businessCourse->expires_at)) {
            return back()->with('error', 'Course access has expired.');
        }

        try {
            // Check if enrollment already exists
            $existingEnrollment = CourseUser::where('user_id', $validated['user_id'])
                ->where('course_id', $validated['course_id'])
                ->first();

            if (!$existingEnrollment) {
                CourseUser::create([
                    'user_id' => $validated['user_id'],
                    'course_id' => $validated['course_id'],
                    'progress' => 0,
                    'completed' => false
                ]);
            }

            return back()->with('success', 'Course assigned successfully.');
        } catch (\Exception $e) {
            report($e); // Log the error
            return back()->with('error', 'Failed to assign course. Please try again.');
        }
    }
} 