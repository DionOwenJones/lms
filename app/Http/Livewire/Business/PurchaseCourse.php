<?php

namespace App\Http\Livewire\Business;

use App\Models\Course;
use App\Models\Business;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class PurchaseCourse extends Component
{
    public $selectedCourse = '';
    public $seats = 1;
    public $duration = '3';
    public $selectedCourseDetails;
    public $totalPrice = 0;
    public $isOpen = false;

    protected $rules = [
        'selectedCourse' => 'required|exists:courses,id',
        'seats' => 'required|integer|min:1|max:1000',
        'duration' => 'required|in:3,6,12,forever'
    ];

    public function openModal()
    {
        $this->isOpen = true;
    }

    public function closeModal()
    {
        $this->isOpen = false;
        $this->resetExcept('isOpen');
    }

    public function mount()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        if (!$user->business_id) {
            return redirect()->route('dashboard')->with('error', 'Unauthorized access.');
        }
    }

    public function updatedSelectedCourse()
    {
        if ($this->selectedCourse) {
            $this->selectedCourseDetails = Course::find($this->selectedCourse);
            $this->calculateTotal();
        }
    }

    public function updatedSeats()
    {
        $this->seats = max(1, min(1000, $this->seats));
        $this->calculateTotal();
    }

    public function updatedDuration()
    {
        $this->calculateTotal();
    }

    protected function calculateTotal()
    {
        if ($this->selectedCourseDetails && $this->seats > 0) {
            $basePrice = $this->selectedCourseDetails->price;
            $durationMultiplier = match($this->duration) {
                '3' => 1,
                '6' => 1.8,
                '12' => 3,
                'forever' => 5,
                default => 0
            };
            
            $this->totalPrice = $basePrice * $this->seats * $durationMultiplier;
        }
    }

    public function purchase()
    {
        if (!Auth::check()) {
            session()->flash('error', 'Please login to continue.');
            return redirect()->route('login');
        }

        $user = Auth::user();
        if (!$user->business_id) {
            session()->flash('error', 'Unauthorized access.');
            return;
        }

        $this->validate();

        try {
            DB::beginTransaction();

            $expiresAt = $this->duration === 'forever' 
                ? null 
                : now()->addMonths(intval($this->duration));

            // Get business and check existing purchase
            $business = Business::findOrFail($user->business_id);
            $existingPurchase = DB::table('business_courses')
                ->where('business_id', $business->id)
                ->where('course_id', $this->selectedCourse)
                ->first();

            if ($existingPurchase) {
                // Update existing purchase
                DB::table('business_courses')
                    ->where('business_id', $business->id)
                    ->where('course_id', $this->selectedCourse)
                    ->update([
                        'purchased_seats' => $existingPurchase->purchased_seats + $this->seats,
                        'expires_at' => $expiresAt ?? $existingPurchase->expires_at,
                        'updated_at' => now()
                    ]);
            } else {
                // Create new purchase
                DB::table('business_courses')->insert([
                    'business_id' => $business->id,
                    'course_id' => $this->selectedCourse,
                    'purchased_seats' => $this->seats,
                    'expires_at' => $expiresAt,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }

            DB::commit();
            session()->flash('success', 'Course successfully purchased.');
            $this->closeModal();
            $this->emit('refreshComponent');

        } catch (\Exception $e) {
            DB::rollBack();
            report($e);
            session()->flash('error', 'Failed to process purchase. Please try again.');
        }
    }

    public function render()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        return view('livewire.business.purchase-course', [
            'availableCourses' => Course::where('status', 'published')
                ->orderBy('title')
                ->get()
        ]);
    }
} 