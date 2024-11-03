<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\CourseController as AdminCourseController;
use App\Http\Controllers\Business\DashboardController as BusinessDashboardController;
use App\Http\Controllers\Business\CourseController as BusinessCourseController;
use App\Http\Controllers\User\DashboardController;
use App\Http\Controllers\User\CourseController as UserCourseController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

// Home & Static Pages
Route::get('/', function () {
    $featuredCourses = \App\Models\Course::featured()
        ->published()
        ->with('category')
        ->take(6)
        ->get();

    $categories = \App\Models\Category::withCount('courses')
        ->whereHas('courses', function($query) {
            $query->published();
        })
        ->get();

    return view('home', compact('featuredCourses', 'categories'));
})->name('home');

// Public Course Routes
Route::controller(CourseController::class)->group(function () {
    Route::get('/courses', 'index')->name('courses.index');
    Route::get('/courses/{course:slug}', 'show')->name('courses.show');
    Route::get('/categories/{category:slug}', 'category')->name('courses.category');
});

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/

require __DIR__.'/auth.php';

/*
|--------------------------------------------------------------------------
| Protected Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'verified'])->group(function () {
    // User Dashboard & Profile
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');
    
    // User Course Management
    Route::controller(UserCourseController::class)
        ->prefix('dashboard/courses')
        ->name('user.courses.')
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/{course:slug}/learn', 'learn')->name('learn');
            Route::post('/{course}/progress', 'updateProgress')->name('progress.update');
            Route::get('/{course}/certificate', 'certificate')->name('certificate');
        });

    /*
    |--------------------------------------------------------------------------
    | Admin Routes
    |--------------------------------------------------------------------------
    */
    
    Route::middleware('role:admin')
        ->prefix('admin')
        ->name('admin.')
        ->group(function () {
            // Admin Dashboard
            Route::get('/', [AdminDashboardController::class, 'index'])
                ->name('dashboard');

            // Admin Course Management
            Route::controller(AdminCourseController::class)
                ->prefix('courses')
                ->name('courses.')
                ->group(function () {
                    Route::get('/', 'index')->name('index');
                    Route::get('/create', 'create')->name('create');
                    Route::post('/', 'store')->name('store');
                    Route::get('/{course}/edit', 'edit')->name('edit');
                    Route::put('/{course}', 'update')->name('update');
                    Route::delete('/{course}', 'destroy')->name('delete');
                    Route::get('/{course}/analytics', 'analytics')->name('analytics');
                });
        });

    /*
    |--------------------------------------------------------------------------
    | Business Routes
    |--------------------------------------------------------------------------
    */
    
    Route::middleware('role:business')
        ->prefix('business')
        ->name('business.')
        ->group(function () {
            // Business Dashboard
            Route::get('/', [BusinessDashboardController::class, 'index'])
                ->name('dashboard');

            // Business Course Management
            Route::controller(BusinessCourseController::class)
                ->prefix('courses')
                ->name('courses.')
                ->group(function () {
                    Route::get('/', 'index')->name('index');
                    Route::post('/{course}/purchase', 'purchase')->name('purchase');
                    Route::post('/{course}/assign', 'assign')->name('assign');
                    Route::delete('/{course}/users/{user}', 'revokeAccess')->name('revoke');
                    Route::get('/{course}/progress', 'progress')->name('progress');
                });
        });
});
