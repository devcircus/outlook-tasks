<?php

namespace App\Models;

use App\Http\DTO\CategoryData;
use App\Models\Concerns\Slug\HasSlug;
use App\Models\Concerns\Slug\SlugOptions;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Category extends Model
{
    use HasSlug;
    use SoftDeletes;

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
     * A Category has one Definition.
     */
    public function definition(): HasOne
    {
        return $this->hasOne(Definition::class);
    }

    /**
     * Create a new Category
     *
     * @param  array  $data
     */
    public function createCategory(array $data): Category
    {
        return $this->create([
            'name' => strtolower($data['name']),
        ]);
    }

    /**
     * Update a Category
     *
     * @param  array  $data
     */
    public function updateCategory(array $data): Category
    {
        return tap($this, function ($category) use ($data) {
            return $category->update($data);
        })->fresh();
    }

    /**
     * Delete a Category and the associated related models.
     */
    public function deleteCategory(): Category
    {
        return tap($this, function($instance) {
            $instance->tasks()->withTrashed()->delete();
            $instance->emails()->delete();
            $instance->delete();
        });
    }

    /**
     * Restore a deleted category.
     */
    public function restoreCategory(): Category
    {
        return tap($this, function ($instance) {
            return $instance->restore();
        });
    }

    /**
     * Store a new Definition for a Category.
     *
     * @param  array  $data
     */
    public function storeDefinition(array $data): Definition
    {
        return $this->definition()->create($data);
    }
}
