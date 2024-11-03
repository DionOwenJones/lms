<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\User;
use App\Models\CourseProgress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CourseController extends Controller
{
    /**
     * Display a listing of enrolled courses.
     */
    public function index(): View
    {
        $enrolledCourses = DB::table('courses')
            ->join('course_user', 'courses.id', '=', 'course_user.course_id')
            ->where('course_user.user_id', Auth::id())
            ->select('courses.*', 'course_user.progress', 'course_user.completed', 'course_user.last_accessed')
            ->paginate(12);

        return view('user.courses.index', compact('enrolledCourses'));
    }

    /**
     * Display the specified course.
     */
    public function show(Course $course): View|RedirectResponse
    {
        try {
            $enrollment = DB::table('course_user')
                ->where('user_id', Auth::id())
                ->where('course_id', $course->id)
                ->firstOrFail();

            return view('user.courses.show', compact('course', 'enrollment'));

        } catch (\Exception $e) {
            return redirect()
                ->route('user.courses.index')
                ->with('error', 'You are not enrolled in this course.');
        }
    }

    /**
     * Display the course learning interface.
     */
    public function learn(Course $course): View|RedirectResponse
    {
        try {
            $enrollment = DB::table('course_user')
                ->where('user_id', Auth::id())
                ->where('course_id', $course->id)
                ->firstOrFail();

            $course->load(['modules' => function($query) {
                $query->orderBy('order');
            }]);

            $lastAccessed = CourseProgress::where('user_id', Auth::id())
                ->where('course_id', $course->id)
                ->orderBy('created_at', 'desc')
                ->first();

            $currentModule = $lastAccessed ? 
                $course->modules->find($lastAccessed->module_id) : 
                $course->modules->first();

            return view('user.courses.learn', compact(
                'course',
                'enrollment',
                'currentModule'
            ));

        } catch (\Exception $e) {
            return redirect()
                ->route('user.courses.index')
                ->with('error', 'You are not enrolled in this course.');
        }
    }

    /**
     * Update course progress.
     */
    public function updateProgress(Request $request, Course $course): RedirectResponse
    {
        try {
            $validated = $request->validate([
                'progress' => 'required|integer|min:0|max:100',
                'module_id' => 'required|exists:modules,id',
                'completed' => 'boolean'
            ]);

            $enrollment = DB::table('course_user')
                ->where('user_id', Auth::id())
                ->where('course_id', $course->id)
                ->firstOrFail();

            // Update enrollment progress
            DB::table('course_user')
                ->where('user_id', Auth::id())
                ->where('course_id', $course->id)
                ->update([
                    'progress' => $validated['progress'],
                    'completed' => $validated['completed'] ?? false,
                    'last_accessed' => now()
                ]);

            // Record module progress
            CourseProgress::create([
                'user_id' => Auth::id(),
                'course_id' => $course->id,
                'module_id' => $validated['module_id'],
                'progress' => $validated['progress']
            ]);

            if ($validated['completed'] ?? false) {
                event(new \App\Events\CourseCompleted(Auth::user(), $course));
            }

            return back()->with('success', 'Progress updated successfully.');

        } catch (\Exception $e) {
            report($e);
            return back()->with('error', 'Failed to update progress.');
        }
    }
} 