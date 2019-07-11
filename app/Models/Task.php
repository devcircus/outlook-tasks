<?php

namespace App\Models;

use App\Http\DTO\TaskData;
use App\Models\Concerns\Slug\HasSlug;
use App\Models\Concerns\Uuid\HasUuids;
use App\Models\Concerns\Slug\SlugOptions;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Task extends Model
{
    use HasSlug;
    use HasUuids;
    use SoftDeletes;

    /** @var array */
    protected $casts = [
        'complete' => 'boolean',
        'due_date' => 'date:Y-m-d',
    ];

    /** @var array */
    protected $with = [
        'category',
    ];

    /** @var array */
    protected $dates = [
        'due_date',
    ];

    /** @var array */
    protected $appends = [
        'category_name',
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
     * Get the category name for the task.
     */
    public function getCategoryNameAttribute(): string
    {
        return $this->category->name;
    }

    /**
     * Scope the query to Tasks from the given category.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $category
     */
    public function scopeForCategory(Builder $query, string $category): Builder
    {
        return $query->whereHas('category', function ($query) use ($category) {
            $query->where('name', $category);
        });
    }

    /**
     * Scope the query to Tasks which are incomplete.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     */
    public function scopeIncomplete(Builder $query): Builder
    {
        return $query->where('complete', 0);
    }

    /**
     * Scope the query to Tasks which are complete.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     */
    public function scopeComplete(Builder $query): Builder
    {
        return $query->where('complete', 1);
    }

    /**
     * Create a task for a specific user, using the given data.
     *
     * @param  \App\Http\DTO\TaskData  $data
     * @param  \App\Models\User  $user
     * @param  int|null  $email
     */
    public function createTaskForUser(TaskData $data, User $user, ?int $email = null): Task
    {
        $task = $user->tasks()->create([
            'title' => $data->title,
            'description' => $data->description,
            'report_to' => $data->report_to,
            'due_date' => $data->due_date,
            'complete' => 0,
            'category_id' => Category::where('name', $data->category->name)->first()->id,
        ]);

        if ($email) {
            Email::find($email)->setAssigned();
        }

        return $task;
    }

    /**
     * Create a task from the given email.
     */
    public function createFromEmail(Email $email): void
    {
        $subject = $this->formatTitle($this->normalizeTitle($email->subject));
        $body = $email->body;

        if ($this->isNotDuplicate($subject, $body)) {
            $this->create([
                'title' => $subject,
                'description' => $body,
                'category_id' => $email->category_id,
                'user_id' => $email->user->id,
            ]);

            $email->setAssigned();
        }
    }

    /**
     * Update a task.
     *
     * @param  \App\Http\DTO\TaskData  $data
     */
    public function updateTaskData(TaskData $data): Task
    {
        return tap($this, function ($task) use ($data) {
            return $task->update($data->only(['title', 'description', 'report_to', 'due_date', 'complete']));
        })->fresh();
    }

    /**
     * Delete a task.
     */
    public function deleteTask(): Task
    {
        return tap($this, function ($instance) {
            return $instance->delete();
        });
    }

    /**
     * Does this task already exist?
     *
     * @param  string  $title
     * @param  string  $body
     */
    private function isNotDuplicate(string $title, string $body): bool
    {
        $task = $this->incomplete()->where('title', $title)->first();

        if (! $task) {
            return true;
        }

        $first = replace_new_lines(str_replace(config('outlook.ignore'), '', $task->description));
        $second = replace_new_lines(str_replace(config('outlook.ignore'), '', $body));

        similar_text($first, $second, $percent);
        if (\Illuminate\Support\Str::contains($title, 'arkansas')) {
            \Log::info($percent);
        }

        return $percent < 40;
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

    /**
     * Format the title based on the task category.
     *
     * @param  string  $title
     */
    private function formatTitle(string $title): string
    {
        $prototypePattern = "/(.*)\s(\d{8,}-\d+[,-]\d+)/";

        if (preg_match($prototypePattern, $title, $matches)) {
            $first = ucwords($matches[1]);
            $second = $matches[2];

            return "{$first} {$second}";
        }

        return $title;
    }
}
