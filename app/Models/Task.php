<?php

namespace App\Models;

use App\Models\Traits\Slug\HasSlug;
use App\Models\Traits\Uuid\HasUuids;
use App\Models\Traits\Slug\SlugOptions;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Task extends Model
{
    use HasUuids;
    use HasSlug;

    /** @var array */
    protected $casts = [
        'complete' => 'boolean',
    ];

    /**
     * Get the options for generating the slug.
     */
    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug');
    }

    /**
     * A Task belongs to many departments.
     */
    public function departments(): BelongsToMany
    {
        return $this->belongsToMany(Department::class);
    }

    /**
     * A Task belongs to a User.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * A Task belongs to a Category.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Scope the query to Tasks which are incomplete.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     */
    public function scopeIncomplete(Builder $query): Builder
    {
        return $query->where('complete', false);
    }
}
