<?php

namespace App\Models;

use Carbon\CarbonImmutable;
use App\Models\Concerns\Uuid\HasUuids;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Email extends Model
{
    use HasUuids;

    /** @var string */
    protected $table = 'emails';

    /** @var array */
    protected $dates = ['received_at'];

    /** @var array */
    protected $with = ['category'];

    protected $casts = [
        'received_at' => 'date:Y-m-d',
    ];

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
     * Set the category for the email.
     *
     * @param  string  $category
     */
    public function setCategory(string $category): bool
    {
        $this->category()->associate(Category::where('name', $category)->first());

        return $this->save();
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
     * Scope the query to emails that have a category that is not "none".
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeCategoryNotNone(Builder $query): Builder
    {
        return $query->whereHas('category', function ($query) {
            return $query->where('name', '!=', 'none');
        });
    }

    /**
     * Scope the query to emails that have a category that is "none".
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeCategoryNone(Builder $query): Builder
    {
        return $query->whereHas('category', function ($query) {
            $query->where('name', '=', 'none');
        });
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
     * Scope the query to emails that have not been assigned to a task
     * or in which the email has been assigned a task of none.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithNoTask(Builder $query): Builder
    {
        return $query->notAssignedToTask()->whereHas('category', function ($query) {
            return $query->where('name', '=', 'none');
        })->orWhereDoesntHave('category');
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
     * Set an Email as assigned.
     */
    public function setAssigned(): bool
    {
        $this->assigned = 1;

        return $this->save();
    }

    /**
     * Set an Email as not assigned.
     */
    public function setNotAssigned(): bool
    {
        $this->assigned = 0;

        return $this->save();
    }

    /**
     * Delete an email.
     */
    public function deleteEmail(): Email
    {
        return tap($this, function($instance) {
            return $instance->delete();
        });
    }
}
