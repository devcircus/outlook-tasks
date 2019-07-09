<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model as Eloquent;

abstract class Model extends Eloquent
{
    /** @var array */
    protected $guarded = [];

    /** @var int */
    protected $perPage = 5;

    /*
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
}
