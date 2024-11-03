<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class CourseUser extends Pivot
{
    protected $table = 'course_user';

    protected $fillable = [
        'user_id',
        'course_id',
        'progress',
        'completed'
    ];

    protected $casts = [
        'completed' => 'boolean',
        'progress' => 'integer'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
} 