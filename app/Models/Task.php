<?php

namespace App\Models;

use App\Models\Concerns\Slug\HasSlug;
use App\Models\Concerns\Uuid\HasUuids;
use App\Models\Concerns\Slug\SlugOptions;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Task extends Model
{
    use HasSlug;
    use HasUuids;

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

    /**
     * Create a task from the given email.
     *
     * @param  \App\Models\Email  $email
     */
    public function createFromEmail(Email $email): Task
    {
        $subject = $this->normalizeTitle($email->subject);
        $body = $email->body;

        if ($this->isNotDuplicate($subject, $body)){
            $task = $this->create([
                'title' => $subject,
                'description' => $body,
                'category_id' => $email->category_id,
                'user_id' => $email->user->id,
            ]);
            $email->setAsAssigned();

            return $task;
        }
    }

    /**
     * Does this task already exist?
     *
     * @param  string  $title
     * @param  string  $body
     */
    private function isNotDuplicate(string $title, string $body): bool
    {
        $task = $this->where('title', $title)->first();

        if (! $task) {
            return true;
        }

        $first = replace_new_lines(str_replace(config('outlook.ignore'), '', $task->body));
        $second = replace_new_lines(str_replace(config('outlook.ignore'), '', $body));

        similar_text($first, $second, $percent);

        return $percent < 30;
    }

    /**
     * Normalize the title.
     *
     * @param  string  $title
     */
    private function normalizeTitle(string $title): string
    {
        return trim(str_replace(['re:', 'fw:', 'fwd:'], '', strtolower($title)));
    }
}
