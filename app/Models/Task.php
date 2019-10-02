<?php

namespace App\Models;

use Carbon\CarbonImmutable;
use Illuminate\Support\Str;
use App\Models\Concerns\Slug\HasSlug;
use App\Models\Concerns\Uuid\HasUuids;
use App\Models\Concerns\Slug\SlugOptions;
use Illuminate\Database\Eloquent\Builder;
use App\Services\Cache\CacheForgetService;
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
        'short_title',
        'category_name',
        'display_due_date',
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
     * Get the short description for the task.
     */
    public function getShortTitleAttribute(): string
    {
        return Str::limit($this->title);
    }

    /**
     * Get the display_due_date attribute for the task.
     */
    public function getDisplayDueDateAttribute(): string
    {
        if ($date = $this->due_date) {
            return CarbonImmutable::parse($date)->format('M d');
        }

        return '';
    }

    /**
     * Scope the query to Tasks from the given category.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $category
     */
    public function scopeForCategory(Builder $query, string $category): Builder
    {
        if ('all' === $category) {
            return $query;
        }

        return $query->whereHas('category', function ($query) use ($category) {
            return $query->where('name', $category);
        });
    }

    /**
     * Scope the query to Tasks from the given calendar option.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $calendar
     */
    public function scopeForCalendar(Builder $query, string $calendar): Builder
    {
        if ('today' === $calendar) {
            return $query->forToday();
        }

        if ('overdue' === $calendar) {
            return $query->overdueTasks();
        }

        if ('week' === $calendar) {
            return $query->thisWeeksTasks();
        }

        return $query;
    }

    /**
     * Scope the query to Tasks due on the given date.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string|\Carbon\CarbonImmutable  $date
     */
    public function scopeForDate(Builder $query, $date): Builder
    {
        if ($date instanceof CarbonImmutable) {
            $date = $date->format('Y-m-d');
        }

        return $query->whereDate('due_date', $date);
    }

    /**
     * Scope the query to Tasks due today.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string|\Carbon\CarbonImmutable  $date
     */
    public function scopeForToday(Builder $query): Builder
    {
        return $query->forDate(CarbonImmutable::today('America/Chicago'));
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
     * Scope the current query to tasks that are overdue.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     */
    public function scopeOverdueTasks(Builder $query): Builder
    {
        return $query->where('due_date', '<', today());
    }

    /**
     * Scope the current query to tasks that are due this week.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     */
    public function scopeThisWeeksTasks(Builder $query): Builder
    {
        return $query->whereBetween('due_date', [CarbonImmutable::today()->startOfWeek(), CarbonImmutable::today()->endOfWeek()]);
    }

    /**
     * Create a task for a specific user, using the given data.
     *
     * @param  array  $data
     * @param  \App\Models\User  $user
     * @param  int|null  $emailId
     */
    public function createTaskForUser(array $data, User $user, ?int $emailId = null): Task
    {
        CacheForgetService::call('quantities', $user->id);

        $category_id = Category::where('name', $data['category'])->first()->id;
        $task = $user->tasks()->create([
            'title' => $data['title'],
            'description' => $data['description'],
            'report_to' => $data['report_to'],
            'due_date' => $data['due_date'],
            'complete' => $data['complete'],
            'category_id' => $category_id,
        ]);

        if ($emailId) {
            $email = Email::find($emailId);
            $email->setAssigned();
            $email->setCategory(Category::find($category_id));
        }

        return $task;
    }

    /**
     * Create a task from the given email.
     */
    public function createFromEmail(Email $email): void
    {
        CacheForgetService::call('quantities', $email->user->id);

        $subject = $this->formatTitle($this->normalizeTitle($email->subject));
        $body = $email->body;

        if ($this->isNotDuplicate($subject, $body)) {
            $this->create([
                'title' => $subject,
                'description' => $body,
                'category_id' => $email->category_id,
                'user_id' => $email->user->id,
            ]);
        }
        $email->setAssigned();
    }

    /**
     * Update a task.
     *
     * @param  array  $data
     * @param  int  $userId
     */
    public function updateTaskData(array $data, int $userId): Task
    {
        CacheForgetService::call('quantities', $userId);

        return tap($this, function ($task) use ($data) {
            $task->update([
                'title' => $data['title'],
                'description' => $data['description'],
                'report_to' => $data['report_to'],
                'due_date' => $data['due_date'],
                'complete' => $data['complete'],
            ]);
            $task->category()->associate(Category::where('name', $data['category'])->first());

            return $task->save();
        })->fresh();
    }

    /**
     * Delete a task.
     *
     * @param  int  $userId
     */
    public function deleteTask(int $userId): Task
    {
        CacheForgetService::call('quantities', $userId);

        return tap($this, function ($instance) {
            return $instance->delete();
        });
    }

    /**
     * Restore a deleted task.
     *
     * @param  int  $userId
     */
    public function restoreTask(int $userId): Task
    {
        CacheForgetService::call('quantities', $userId);

        return tap($this, function ($instance) {
            return $instance->restore();
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
