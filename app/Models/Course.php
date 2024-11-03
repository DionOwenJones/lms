<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class Course extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'thumbnail',
        'price',
        'duration',
        'status',
        'level',
        'featured',
        'category_id',
        'instructor_id'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'duration' => 'float',
        'featured' => 'boolean'
    ];

    public function modules(): HasMany
    {
        return $this->hasMany(Module::class)->orderBy('order');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function instructor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }

    public function students(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'course_user')
            ->withPivot(['progress', 'completed', 'enrolled_at', 'last_accessed_module_id'])
            ->withTimestamps();
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function businesses(): BelongsToMany
    {
        return $this->belongsToMany(Business::class, 'business_courses')
            ->withPivot(['purchased_seats', 'expires_at'])
            ->withTimestamps();
    }

    /**
     * Scope a query to only include featured courses.
     */
    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('featured', true);
    }

    /**
     * Scope a query to only include published courses.
     */
    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'published');
    }
} 