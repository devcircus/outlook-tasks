<?php

namespace App;

use Illuminate\Support\Arr;
use Illuminate\Support\Fluent;

class Thing extends Fluent
{

    /**
     * Construct a new Thing.
     *
     * @param  array  $attributes
     */
    public function __construct(array $attributes)
    {
        foreach ($attributes as $key => $value) {
            $this->{$key} = $value;
        }

        parent::__construct($attributes);
    }

    /**
     * Add the given properties to the instance.
     *
     * @param  array  $element
     */
    public function with(array $element): Thing
    {
        foreach ($element as $key => $value) {
            $this->{$key} = $value;
            $this->attributes[$key] = $value;
        }

        return $this;
    }

    /**
     * Retrieve all but the given properties.
     *
     * @param  string|array $keys
     */
    public function except($keys): array
    {
        return Arr::except($this->toArray(), $keys);
    }

    /**
     * Retrieve only the given properties.
     *
     * @param  string|array $keys
     */
    public function only($keys): array
    {
        return Arr::only($this->toArray(), $keys);
    }
}
