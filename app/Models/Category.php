<?php

namespace App\Models;

use App\Models\Concerns\Slug\HasSlug;
use App\Models\Concerns\Slug\SlugOptions;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Http\DTO\CategoryData;

class Category extends Model
{
    use HasSlug;

    /** @var array */
    protected $appends = [
        'display_name',
    ];

    /**
     * Get all of categories.
     *
     * @param  array|mixed  $columns
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public static function all($columns = ['*'])
    {
        return static::query()->where('name', '!=', 'none')->get(
            is_array($columns) ? $columns : func_get_args()
        );
    }

    /**
     * Get the display name attribute.
     */
    public function getDisplayNameAttribute(): string
    {
        return ucfirst($this->name);
    }

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

    /**
     * Create a new Category
     *
     * @param  \App\Http\DTO\CategoryData  $data
     */
    public function createCategory(CategoryData $data): Category
    {
        return $this->create([
            'name' => strtolower($data->name),
        ]);
    }

    /**
     * Delete a Category
     */
    public function deleteCategory(): Category
    {
        return tap($this, function($instance) {
            return $instance->delete();
        });
    }
}
