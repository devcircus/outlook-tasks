<?php

namespace App\Models;

use App\Models\Concerns\Slug\HasSlug;
use App\Models\Concerns\Slug\SlugOptions;
use Illuminate\Database\Eloquent\Builder;
use App\Services\Cache\CacheForgetService;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasSlug;
    use SoftDeletes;

    /** @var array */
    protected $appends = [
        'display_name',
        'has_definition',
        'has_from_definition',
        'has_subject_definition',
        'has_body_definition',
    ];

    /** @var array */
    protected $with = [
        'definitions',
    ];

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
    public function getSlugOptions(): SlugOptions
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
     * A Category has many definitions.
     */
    public function definitions(): HasMany
    {
        return $this->hasMany(Definition::class);
    }

    /**
     * Scope the query to categories that are ready to be used in task generation.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeReady(Builder $query)
    {
        return $query->has('definitions');
    }

    /**
     * Get has_from_definition attribute.
     */
    public function getHasFromDefinitionAttribute(): bool
    {
        return null === $this->definitions()->where('definition_type', 'from');
    }

    /**
     * Get has_subject_definition attribute.
     */
    public function getHasSubjectDefinitionAttribute(): bool
    {
        return null === $this->definitions()->where('definition_type', 'subject');
    }

    /**
     * Get has_body_definition attribute.
     */
    public function getHasBodyDefinitionAttribute(): bool
    {
        return null === $this->definitions()->where('definition_type', 'body');
    }

    /**
     * Get has_definition attribute.
     */
    public function getHasDefinitionAttribute(): bool
    {
        return $this->hasDefinition();
    }

    /**
     * Does the category have any definitions?
     */
    public function hasDefinition(): bool
    {
        return $this->definitions->count() > 0;
    }

    /**
     * Create a new Category.
     *
     * @param  array  $data
     * @param  int  $userId
     */
    public function createCategory(array $data, int $userId): Category
    {
        CacheForgetService::call('quantities', $userId);
        Total::forUser($userId)->delete();

        return $this->create([
            'name' => strtolower($data['name']),
        ]);
    }

    /**
     * Update a Category.
     *
     * @param  array  $data
     * @param  int  $userId
     */
    public function updateCategory(array $data, int $userId): Category
    {
        CacheForgetService::call('quantities', $userId);
        Total::forUser($userId)->delete();

        return tap($this, function ($category) use ($data) {
            return $category->update($data);
        })->fresh();
    }

    /**
     * Delete a Category and the associated related models.
     *
     * @param  int  $userId
     */
    public function deleteCategory(int $userId): Category
    {
        CacheForgetService::call('quantities', $userId);
        Total::forUser($userId)->delete();

        return tap($this, function ($instance) {
            $instance->tasks()->withTrashed()->delete();
            $instance->emails()->delete();
            $instance->delete();
        });
    }

    /**
     * Restore a deleted category.
     *
     * @param  int  $userId
     */
    public function restoreCategory(int $userId): Category
    {
        CacheForgetService::call('quantities', $userId);
        Total::forUser($userId)->delete();

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
        return $this->definitions()->create([
            'definition_type' => $data['definition_type'],
            'rule_type' => $data['rule_type'],
            'definition' => $data['definition'],
            'optional' => $data['optional'],
        ]);
    }
}
