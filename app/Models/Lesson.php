<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'content',
        'video_url',
        'duration',
        'order',
        'course_id'
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function userProgress()
    {
        return $this->hasMany(LessonProgress::class);
    }

    public function completedByUser(User $user)
    {
        return $this->userProgress()
            ->where('user_id', $user->id)
            ->where('completed', true)
            ->exists();
    }
} 