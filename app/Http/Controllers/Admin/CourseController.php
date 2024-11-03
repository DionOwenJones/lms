<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Http\Requests\Admin\CourseRequest;

class CourseController extends Controller
{
    public function index()
    {
        $courses = Course::query()
            ->with('category')
            ->withCount(['enrollments', 'modules'])
            ->latest()
            ->paginate(10);

        return view('admin.courses.index', [
            'courses' => $courses,
            'totalCourses' => Course::count(),
            'publishedCourses' => Course::where('status', 'published')->count(),
            'totalEnrollments' => Course::withCount('enrollments')->get()->sum('enrollments_count')
        ]);
    }

    public function create()
    {
        $categories = Category::all();
        
        if ($categories->isEmpty()) {
            // Seed default categories if none exist
            $this->seedDefaultCategories();
            $categories = Category::all();
        }
        
        return view('admin.courses.create', compact('categories'));
    }

    private function seedDefaultCategories()
    {
        $seeder = new CategorySeeder();
        $seeder->run();
    }

    public function store(CourseRequest $request)
    {
        try {
            $data = $request->validated();
            
            // Ensure category exists
            if (!Category::find($data['category_id'])) {
                return back()
                    ->withInput()
                    ->with('error', 'Selected category does not exist.');
            }

            // Handle thumbnail upload
            if ($request->hasFile('thumbnail')) {
                $data['thumbnail'] = $request->file('thumbnail')
                    ->store('courses/thumbnails', 'public');
            }

            $data['slug'] = Str::slug($data['title']);
            
            $course = Course::create($data);

            // Handle modules if present
            if (isset($data['modules'])) {
                foreach ($data['modules'] as $moduleData) {
                    $module = $course->modules()->create([
                        'title' => $moduleData['title'],
                        'description' => $moduleData['description'],
                        'duration' => $moduleData['duration'],
                        'order' => $moduleData['order']
                    ]);

                    // Handle module content file if present
                    if (isset($moduleData['content']) && $moduleData['content']->isValid()) {
                        $module->update([
                            'content_path' => $moduleData['content']->store('courses/modules', 'public')
                        ]);
                    }
                }
            }

            return redirect()
                ->route('admin.courses.index')
                ->with('success', 'Course created successfully');

        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Failed to create course: ' . $e->getMessage());
        }
    }

    public function edit(Course $course)
    {
        $course->load(['modules', 'category']);
        
        return view('admin.courses.edit', [
            'course' => $course,
            'categories' => Category::all()
        ]);
    }

    public function update(CourseRequest $request, Course $course)
    {
        try {
            $data = $request->validated();

            // Handle thumbnail update
            if ($request->hasFile('thumbnail')) {
                // Delete old thumbnail
                if ($course->thumbnail) {
                    Storage::disk('public')->delete($course->thumbnail);
                }
                $data['thumbnail'] = $request->file('thumbnail')
                    ->store('courses/thumbnails', 'public');
            }

            $data['slug'] = Str::slug($data['title']);
            $course->update($data);

            // Handle modules update
            if (isset($data['modules'])) {
                // Get existing module IDs
                $existingModuleIds = $course->modules->pluck('id')->toArray();
                $updatedModuleIds = collect($data['modules'])->pluck('id')->filter()->toArray();
                
                // Delete modules that are not in the update
                $modulesToDelete = array_diff($existingModuleIds, $updatedModuleIds);
                foreach ($modulesToDelete as $moduleId) {
                    $module = $course->modules()->find($moduleId);
                    if ($module) {
                        Storage::disk('public')->delete($module->content_path);
                        $module->delete();
                    }
                }

                // Update or create modules
                foreach ($data['modules'] as $moduleData) {
                    $module = $course->modules()->updateOrCreate(
                        ['id' => $moduleData['id'] ?? null],
                        [
                            'title' => $moduleData['title'],
                            'description' => $moduleData['description'],
                            'duration' => $moduleData['duration'],
                            'order' => $moduleData['order']
                        ]
                    );

                    // Handle module content file if present
                    if (isset($moduleData['content']) && $moduleData['content']->isValid()) {
                        if ($module->content_path) {
                            Storage::disk('public')->delete($module->content_path);
                        }
                        $module->update([
                            'content_path' => $moduleData['content']->store('courses/modules', 'public')
                        ]);
                    }
                }
            }

            return redirect()
                ->route('admin.courses.index')
                ->with('success', 'Course updated successfully');

        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Failed to update course: ' . $e->getMessage());
        }
    }

    public function destroy(Course $course)
    {
        try {
            // Delete course files
            if ($course->thumbnail) {
                Storage::disk('public')->delete($course->thumbnail);
            }

            // Delete module files
            foreach ($course->modules as $module) {
                if ($module->content_path) {
                    Storage::disk('public')->delete($module->content_path);
                }
            }

            $course->delete();

            return redirect()
                ->route('admin.courses.index')
                ->with('success', 'Course deleted successfully');

        } catch (\Exception $e) {
            return back()->with('error', 'Failed to delete course: ' . $e->getMessage());
        }
    }
} 