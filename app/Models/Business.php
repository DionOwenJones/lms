<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Business extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'status'
    ];

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function purchasedCourses(): BelongsToMany
    {
        return $this->belongsToMany(Course::class, 'business_courses')
            ->withPivot(['purchased_seats', 'total_price', 'expires_at'])
            ->withTimestamps();
    }
} 