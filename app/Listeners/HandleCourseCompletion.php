<?php

namespace App\Listeners;

use App\Events\CourseCompleted;
use App\Models\Certificate;
use Illuminate\Contracts\Queue\ShouldQueue;

class HandleCourseCompletion implements ShouldQueue
{
    public function handle(CourseCompleted $event): void
    {
        // Generate certificate
        Certificate::create([
            'user_id' => $event->user->id,
            'course_id' => $event->course->id,
            'completed_at' => now(),
            'certificate_number' => $this->generateCertificateNumber()
        ]);

        // Send completion notification
        $event->user->notify(new \App\Notifications\CourseCompletedNotification($event->course));
    }

    private function generateCertificateNumber(): string
    {
        return strtoupper(uniqid('CERT-'));
    }
} 