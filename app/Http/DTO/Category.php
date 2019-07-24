<?php

namespace App\Http\DTO;

use Illuminate\Http\Request;

class Category extends Data
{
    /** @var string */
    public $name;

    /**
     * Construct a new Category object.
     *
     * @param  array  $parameters
     */
    public function __construct(array $parameters)
    {
        parent::__construct($this->validate($parameters));
    }

    /**
     * Create a new Category object from request.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public static function fromRequest(Request $request): Category
    {
        return static::fromArray($request->only(['name']));
    }

    /**
     * Create a new Category object from an array.
     *
     * @param  array  $data
     */
    public static function fromArray(array $data): Category
    {
        return new self([
            'name' => $data['name'] ?? null,
        ]);
    }

    /**
     * Create a new Category object from a name.
     *
     * @param  string  $name
     */
    public static function fromName(string $name): Category
    {
        return new self([
            'name' => $name,
        ]);
    }

    /**
     * Validate the given parameters.
     *
     * @param  array  $parameters
     */
    public function validate(array $parameters): array
    {
        $parameters['name'] = isset($parameters['name']) ? (string) $parameters['name'] : null;

        return $parameters;
    }
}
