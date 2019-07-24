<?php

namespace App\Models;

use Carbon\CarbonImmutable;
use App\Http\DTO\User as UserData;
use App\Models\Concerns\Uuid\HasUuids;
use Illuminate\Notifications\Notifiable;
use App\Models\Concerns\Oauth\HasOauthTokens;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

class User extends Authenticatable implements AuthorizableContract, MustVerifyEmail
{
    use HasUuids;
    use Notifiable;
    use SoftDeletes;
    use Authorizable;
    use HasOauthTokens;

    /** @var array */
    protected $guarded = [];

    /** @var int */
    protected $perPage = 10;

    /** @var array */
    protected $casts = [
        'is_admin' => 'boolean',
    ];

    /** @var array */
    protected $appends = [
        'has_oauth_tokens',
    ];

    /**
     * A user has many tasks.
     */
    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

    /**
     * A user has many emails.
     */
    public function emails(): HasMany
    {
        return $this->hasMany(Email::class);
    }

    /**
     * Order query by user name.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $builder
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOrderByName($builder)
    {
        $builder->orderBy('name');
    }

    /**
     * Filter the query.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $builder
     * @param  array  $filters
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFilter($builder, array $filters)
    {
        $builder->when($filters['search'] ?? null, function ($builder, $search) {
            $builder->where(function ($builder) use ($search) {
                $builder->where('name', 'like', '%'.$search.'%')
                    ->orWhere('email', 'like', '%'.$search.'%');
            });
        })->when($filters['trashed'] ?? null, function ($builder, $trashed) {
            if ('with' === $trashed) {
                $builder->withTrashed();
            } elseif ('only' === $trashed) {
                $builder->onlyTrashed();
            }
        });
    }

    /**
     * Get the outlook_token_expires attribute as a Carbon date.
     *
     * @param  int  $value
     *
     * @return \Carbon\CarbonImmutable
     */
    public function getOutlookTokenExpiresAttribute($value)
    {
        return CarbonImmutable::createFromTimestamp($value);
    }

    /**
     * Does the user have oauth tokens stored?
     *
     * @return bool
     */
    public function getHasOauthTokensAttribute()
    {
        return $this->hasOauthTokens();
    }

    /**
     * Retrieve the model for a bound value.
     *
     * @param  mixed  $value
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function resolveRouteBinding($value)
    {
        return in_array(SoftDeletes::class, class_uses($this))
            ? $this->where($this->getRouteKeyName(), $value)->withTrashed()->first()
            : parent::resolveRouteBinding($value);
    }

    /**
     * Is the user an administrator?
     */
    public function isAdmin(): bool
    {
        return $this->is_admin;
    }

    /**
     * Create a user with the provided data.
     *
     * @param  \App\Http\DTO\User  $user
     *
     * @return \App\Models\User
     */
    public function createUser(UserData $user)
    {
        return $this->create([
            'name' => $user->name,
            'email' => $user->email,
            'password' => bcrypt($user->password),
            'is_admin' => $user->is_admin,
        ]);
    }

    /**
     * Delete a user.
     *
     * @return \App\Models\User
     */
    public function deleteUser()
    {
        return tap($this, function ($instance) {
            return $instance->delete();
        });
    }

    /**
     * Restore a deleted user.
     *
     * @return \App\Models\User
     */
    public function restoreUser()
    {
        return tap($this, function ($instance) {
            return $instance->restore();
        });
    }

    /**
     * Update user data.
     *
     * @param  \App\Http\DTO\User  $data
     *
     * @return \App\Models\User
     */
    public function updateUserData(UserData $data)
    {
        return tap($this, function ($user) use ($data) {
            return $user->update($data->only(['name', 'email']));
        })->fresh();
    }

    /**
     * Update user password.
     *
     * @param  \App\Http\DTO\User  $data
     *
     * @return \App\Models\User
     */
    public function updateUserPassword(UserData $data)
    {
        return tap($this, function ($user) use ($data) {
            return $user->update([
                'password' => bcrypt($data->password),
            ]);
        })->fresh();
    }
}
