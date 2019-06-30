<?php

namespace App\Models;

use Carbon\CarbonImmutable;
use Laravel\Scout\Searchable;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Concerns\Uuid\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Email extends Model
{
    use HasUuids, Searchable;

    /** @var string */
    protected $table = 'emails';

    /** @var array */
    protected $dates = ['received_at'];

    /**
     * An Email belongs to a User.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope the query to emails that have not been assigned to a task.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeUnassigned(Builder $query): Builder
    {
        return $query->where('assigned', false);
    }

    /**
     * Scope the query to order by the given column and direction.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $column
     * @param  string  $direction
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOrderByColumn(Builder $query, string $column = 'created_at', string $direction = 'asc'): Builder
    {
        return $query->orderBy($column, $direction);
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
            'body' => $email['body']['content'],
            'received_at' => CarbonImmutable::createFromFormat('Y-m-d\TH:i:s\Z', $email['receivedDateTime']),
            'outlook_id' => $email['id'],
            'user_id' => $user->id,
        ]);
    }
}
