<?php

namespace App\Models;

use App\Models\Concerns\Slug\HasSlug;
use App\Models\Concerns\Slug\SlugOptions;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasSlug;

    /**
     * Get the options for generating the slug.
     */
    public function getSlugOptions() : SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }

    /**
     * A Category has many tasks.
     */
    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

    /**
     * A Category has many emails.
     */
    public function emails(): HasMany
    {
        return $this->hasMany(Email::class);
    }
}
