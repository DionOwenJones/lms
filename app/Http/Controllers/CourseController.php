<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CourseUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CourseController extends Controller
{
    public function index()
    {
        $courses = Course::where('status', 'published')
            ->withCount('users')
            ->paginate(12);

        return view('courses.index', compact('courses'));
    }

    public function show(Course $course)
    {
        $course->load(['modules', 'category']);
        $userProgress = null;
        
        if (Auth::check()) {
            $userProgress = DB::table('course_user')
                ->where('user_id', Auth::id())
                ->where('course_id', $course->id)
                ->first();
        }

        return view('courses.show', compact('course', 'userProgress'));
    }

    public function enrolled()
    {
        $enrolledCourses = DB::table('courses')
            ->join('course_user', 'courses.id', '=', 'course_user.course_id')
            ->where('course_user.user_id', Auth::id())
            ->select('courses.*', 'course_user.progress', 'course_user.completed')
            ->paginate(12);

        return view('courses.enrolled', compact('enrolledCourses'));
    }

    public function learn(Course $course)
    {
        $enrollment = DB::table('course_user')
            ->where('user_id', Auth::id())
            ->where('course_id', $course->id)
            ->firstOrFail();

        return view('courses.learn', [
            'course' => $course->load('modules'),
            'progress' => $enrollment->progress,
            'completed' => $enrollment->completed
        ]);
    }

    public function updateProgress(Request $request, Course $course)
    {
        $validated = $request->validate([
            'progress' => 'required|integer|min:0|max:100',
            'completed' => 'boolean'
        ]);

        DB::table('course_user')
            ->where('user_id', Auth::id())
            ->where('course_id', $course->id)
            ->update([
                'progress' => $validated['progress'],
                'completed' => $validated['completed'] ?? false,
                'updated_at' => now()
            ]);

        if ($validated['completed']) {
            event(new \App\Events\CourseCompleted(Auth::user(), $course));
        }

        return response()->json(['success' => true]);
    }
} 