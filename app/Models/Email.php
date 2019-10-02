<?php

namespace App\Models;

use Carbon\CarbonImmutable;
use Illuminate\Support\Str;
use App\Models\Concerns\Uuid\HasUuids;
use Illuminate\Database\Eloquent\Builder;
use App\Services\Cache\CacheForgetService;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Email extends Model
{
    use HasUuids;
    use SoftDeletes;

    /** @var string */
    protected $table = 'emails';

    /** @var array */
    protected $dates = ['received_at'];

    /** @var array */
    protected $with = ['category'];

    /** @var array */
    protected $appends = ['short_subject'];

    /**
     * An Email belongs to a User.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * An Email belongs to a Category.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the display_received_at_date attribute for the task.
     */
    public function getDisplayReceivedAtAttribute(): string
    {
        if ($date = $this->received_at) {
            return CarbonImmutable::parse($date)->format('M d');
        }

        return '';
    }

    /**
     * Get the short subject for the task.
     */
    public function getShortSubjectAttribute(): string
    {
        return Str::limit($this->subject, 60);
    }

    /**
     * Set the category for the email.
     *
     * @param  \App\Models\Category  $category
     */
    public function setCategory(Category $category): bool
    {
        $this->category()->associate($category);

        return $this->save();
    }

    /**
     * Set the category for the email.
     *
     * @param  string  $category
     */
    public function setCategoryByName(string $category): bool
    {
        $this->setCategory(Category::where('name', $category)->first());
    }

    /**
     * Scope the query to emails that have not been assigned to a task.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeCategorized(Builder $query): Builder
    {
        return $query->whereHas('category');
    }

    /**
     * Scope the query to emails that have not been assigned to a task.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeNotCategorized(Builder $query): Builder
    {
        return $query->whereDoesntHave('category');
    }

    /**
     * Scope the query to emails that have been assigned to a task.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeAssignedToTask(Builder $query): Builder
    {
        return $query->where('assigned', 1);
    }

    /**
     * Scope the query to emails that have not been assigned to a task.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithNoTask(Builder $query): Builder
    {
        return $query->notAssignedToTask()->orWhereDoesntHave('category');
    }

    /**
     * Scope the query to emails that have not been assigned to a task.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeNotAssignedToTask(Builder $query): Builder
    {
        return $query->where('assigned', 0);
    }

    /**
     * Scope the query to emails that have been processed for task generation.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeProcessed(Builder $query): Builder
    {
        return $query->where('processed', 1);
    }

    /**
     * Scope the query to emails that have not been processed for task generation.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeNotProcessed(Builder $query): Builder
    {
        return $query->where('processed', 0);
    }

    /**
     * Scope the query to emails received after the given date.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  \Carbon\CarbonImmutable  $date
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeReceivedAfter(Builder $query, CarbonImmutable $date): Builder
    {
        return $query->where('received_at', '>', $date);
    }

    /**
     * Scope the query to emails for the given user.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  \App\Models\User  $user
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForUser(Builder $query, User $user): Builder
    {
        return $query->where('user_id', $user->id);
    }

    /**
     * Create an Email from an outlook email.
     *
     * @param  array  $email
     * @param  \App\Models\User  $user
     */
    public function storeEmailFromOutlookForUser(array $email, User $user): Email
    {
        return $this->create([
            'subject' => $email['subject'],
            'from_address' => $email['from']['emailAddress']['address'],
            'from_name' => $email['from']['emailAddress']['name'],
            'body' => str_replace(config('outlook.ignore'), '', $email['body']['content']),
            'received_at' => CarbonImmutable::createFromFormat('Y-m-d\TH:i:s\Z', $email['receivedDateTime']),
            'outlook_id' => $email['id'],
            'user_id' => $user->id,
        ]);
    }

    /**
     * Mark an Email as assigned.
     */
    public function setAssigned(): bool
    {
        $this->assigned = 1;

        return $this->save();
    }

    /**
     * Mark an Email as not assigned.
     */
    public function setNotAssigned(): bool
    {
        $this->assigned = 0;

        return $this->save();
    }

    /**
     * Mark an Email as processed.
     */
    public function setProcessed(): bool
    {
        $this->processed = 1;

        return $this->save();
    }

    /**
     * Delete an email.
     */
    public function deleteEmail(): Email
    {
        CacheForgetService::call('emails', $user->id);
        CacheForgetService::call('emailQuantities', $user->id);

        return tap($this, function ($instance) {
            return $instance->delete();
        });
    }

    /**
     * Restore a deleted email.
     */
    public function restoreEmail(): Email
    {
        CacheForgetService::call('emails', $user->id);
        CacheForgetService::call('emailQuantities', $user->id);

        return tap($this, function ($instance) {
            return $instance->restore();
        });
    }

    /**
     * Delete all emails without tasks, for the given user.
     *
     * @param  \App\Models\User  $user
     */
    public function deleteAllWithNoTask(User $user): bool
    {
        CacheForgetService::call('emails', $user->id);
        CacheForgetService::call('emailQuantities', $user->id);

        return $user->emails()->withNoTask()->delete();
    }
}
