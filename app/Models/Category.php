<?php

namespace App\Models;

use App\Models\Concerns\Slug\HasSlug;
use App\Models\Concerns\Slug\SlugOptions;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasOne;
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
        'fromDefinition',
        'subjectDefinition',
        'bodyDefinition',
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
     * A Category has one From Definition.
     */
    public function fromDefinition(): HasOne
    {
        return $this->hasOne(Definition::class)->where(function ($query) {
            $query->where('definition_type', 'from');
        });
    }

    /**
     * A Category has one Subject Definition.
     */
    public function subjectDefinition(): HasOne
    {
        return $this->hasOne(Definition::class)->where(function ($query) {
            $query->where('definition_type', 'subject');
        });
    }

    /**
     * A Category has one Body Definition.
     */
    public function bodyDefinition(): HasOne
    {
        return $this->hasOne(Definition::class)->where(function ($query) {
            $query->where('definition_type', 'body');
        });
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
        return $this->fromDefinition != null;
    }

    /**
     * Get has_subject_definition attribute.
     */
    public function getHasSubjectDefinitionAttribute(): bool
    {
        return $this->subjectDefinition != null;
    }

    /**
     * Get has_body_definition attribute.
     */
    public function getHasBodyDefinitionAttribute(): bool
    {
        return $this->bodyDefinition != null;
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
     */
    public function createCategory(array $data): Category
    {
        return $this->create([
            'name' => strtolower($data['name']),
        ]);
    }

    /**
     * Update a Category.
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
        return tap($this, function ($instance) {
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
        return $this->definitions()->create([
            'definition_type' => $data['definition_type'],
            'rule_type' => $data['rule_type'],
            'definition' => $data['definition'],
        ]);
    }
}
