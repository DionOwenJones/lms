<?php

namespace App\Http\Controllers\Business;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\User;
use App\Models\Business;
use App\Http\Requests\Business\CourseAssignRequest;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CourseController extends Controller
{
    protected $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    /**
     * Display a listing of business courses.
     */
    public function index(): View
    {
        $business = Business::findOrFail(Auth::user()->business_id);

        $courses = Course::with(['category', 'instructor'])
            ->whereHas('businesses', function ($query) use ($business) {
                $query->where('business_id', $business->id);
            })
            ->withCount(['students' => function ($query) use ($business) {
                $query->where('business_id', $business->id);
            }])
            ->paginate(10);

        return view('business.courses.index', compact('courses'));
    }

    /**
     * Purchase a course for the business.
     */
    public function purchase(Course $course): RedirectResponse
    {
        try {
            DB::beginTransaction();

            $business = Business::findOrFail(Auth::user()->business_id);

            // Process payment
            $payment = $this->paymentService->processPurchase([
                'amount' => $course->business_price,
                'business_id' => $business->id,
                'course_id' => $course->id
            ]);

            // Attach course to business
            $business->courses()->attach($course->id, [
                'purchased_at' => now(),
                'payment_id' => $payment->id
            ]);

            DB::commit();

            return redirect()
                ->route('business.courses.index')
                ->with('success', 'Course purchased successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            report($e);

            return back()
                ->with('error', 'Failed to purchase course. Please try again.');
        }
    }

    /**
     * Assign course to business employees.
     */
    public function assign(Course $course, CourseAssignRequest $request): RedirectResponse
    {
        try {
            $business = Business::findOrFail(Auth::user()->business_id);

            $users = User::whereIn('id', $request->user_ids)
                ->where('business_id', $business->id)
                ->get();

            $course->students()->attach($users->pluck('id')->toArray(), [
                'assigned_by' => Auth::id(),
                'assigned_at' => now(),
                'business_id' => $business->id
            ]);

            return back()->with('success', 'Course assigned successfully.');

        } catch (\Exception $e) {
            report($e);
            return back()->with('error', 'Failed to assign course.');
        }
    }

    /**
     * Revoke course access from a business employee.
     */
    public function revokeAccess(Course $course, User $user): RedirectResponse
    {
        try {
            $business = Business::findOrFail(Auth::user()->business_id);

            if ($user->business_id !== $business->id) {
                return back()->with('error', 'Unauthorized action.');
            }

            $course->students()->detach($user->id);

            return back()->with('success', 'Course access revoked successfully.');

        } catch (\Exception $e) {
            report($e);
            return back()->with('error', 'Failed to revoke course access.');
        }
    }

    /**
     * View course progress for business employees.
     */
    public function progress(Course $course): View
    {
        $business = Business::findOrFail(Auth::user()->business_id);

        $students = $course->students()
            ->where('business_id', $business->id)
            ->withPivot(['progress', 'completed', 'last_accessed'])
            ->paginate(15);

        return view('business.courses.progress', compact('course', 'students'));
    }
} 