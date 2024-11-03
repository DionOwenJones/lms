<?php

namespace App\Notifications;

use App\Models\Course;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class CourseCompletedNotification extends Notification
{
    use Queueable;

    protected $course;

    public function __construct(Course $course)
    {
        $this->course = $course;
    }

    public function via($notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Course Completed: ' . $this->course->title)
            ->line('Congratulations! You have completed the course.')
            ->line('Course: ' . $this->course->title)
            ->action('View Certificate', route('certificates.show', [
                'course' => $this->course->id
            ]));
    }

    public function toArray($notifiable): array
    {
        return [
            'course_id' => $this->course->id,
            'course_title' => $this->course->title,
            'completed_at' => now(),
        ];
    }
} 