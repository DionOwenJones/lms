<?php

namespace App\Http\Controllers;

use App\Models\Lesson;
use App\Models\LessonProgress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LessonController extends Controller
{
    public function show(Lesson $lesson)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }

        // Get course details
        $course = DB::table('courses')
            ->where('id', $lesson->course_id)
            ->first();

        if (!$course) {
            return redirect()->route('courses.index')
                ->with('error', 'Course not found.');
        }

        // Check if user has access through direct enrollment
        $hasDirectAccess = DB::table('course_user')
            ->where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->exists();

        // Check if user has access through business
        $hasBusinessAccess = false;
        if ($user->business_id) {
            $hasBusinessAccess = DB::table('business_courses')
                ->where('business_id', $user->business_id)
                ->where('course_id', $course->id)
                ->exists();
        }

        if (!$hasDirectAccess && !$hasBusinessAccess) {
            return redirect()->route('courses.show', $course->id)
                ->with('error', 'Please enroll in this course to access the lessons.');
        }

        // Get or create lesson progress
        $progress = DB::table('lesson_progress')
            ->where('user_id', $user->id)
            ->where('lesson_id', $lesson->id)
            ->first();

        if (!$progress) {
            $progress = DB::table('lesson_progress')->insert([
                'user_id' => $user->id,
                'lesson_id' => $lesson->id,
                'completed' => false,
                'watch_time' => 0,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        return view('lessons.show', compact('lesson', 'progress'));
    }

    public function updateProgress(Request $request, Lesson $lesson)
    {
        $validated = $request->validate([
            'watch_time' => 'required|integer|min:0',
            'completed' => 'boolean'
        ]);

        $userId = Auth::id();
        if (!$userId) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        try {
            DB::beginTransaction();

            // Update lesson progress
            DB::table('lesson_progress')
                ->updateOrInsert(
                    [
                        'user_id' => $userId,
                        'lesson_id' => $lesson->id
                    ],
                    [
                        'watch_time' => $validated['watch_time'],
                        'completed' => $validated['completed'] ?? false,
                        'last_watched_at' => now(),
                        'updated_at' => now()
                    ]
                );

            // Get course progress statistics
            $course = DB::table('courses')->find($lesson->course_id);
            $totalLessons = DB::table('lessons')
                ->where('course_id', $course->id)
                ->count();

            $completedLessons = DB::table('lesson_progress')
                ->join('lessons', 'lesson_progress.lesson_id', '=', 'lessons.id')
                ->where('lessons.course_id', $course->id)
                ->where('lesson_progress.user_id', $userId)
                ->where('lesson_progress.completed', true)
                ->count();

            $courseProgress = ($completedLessons / $totalLessons) * 100;

            // Update course progress
            DB::table('course_user')
                ->where('user_id', $userId)
                ->where('course_id', $course->id)
                ->update([
                    'progress' => $courseProgress,
                    'completed' => $courseProgress === 100,
                    'updated_at' => now()
                ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'progress' => $courseProgress
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            report($e);
            return response()->json(['error' => 'Failed to update progress'], 500);
        }
    }
} 