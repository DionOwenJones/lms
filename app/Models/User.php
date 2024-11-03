<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'business_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function courses()
    {
        return $this->belongsToMany(Course::class, 'course_user')
            ->withPivot(['progress', 'completed', 'last_accessed'])
            ->withTimestamps();
    }

    public function business()
    {
        return $this->belongsTo(Business::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
}
