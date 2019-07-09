<?php

namespace App\Http\DTO;

use Illuminate\Http\Request;
use Spatie\DataTransferObject\DataTransferObject;

class CategoryData extends DataTransferObject
{
    /** @var string */
    public $name;

    /**
     * Construct a new CategoryData object.
     *
     * @param  array  $parameters
     */
    public function __construct(array $parameters)
    {
        $parameters['name'] = isset($parameters['name']) ? (string) $parameters['name'] : null;

        parent::__construct($parameters);
    }

    /**
     * Create a new CategoryData object from request.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public static function fromRequest(Request $request): CategoryData
    {
        return static::fromArray($request->only(['name']));
    }

    /**
     * Create a new CategoryData object from an array.
     *
     * @param  array  $data
     */
    public static function fromArray(array $data): CategoryData
    {
        return new self([
            'name' => $data['name'] ?? null,
        ]);
    }

    /**
     * Create a new CategoryData object from a name.
     *
     * @param  string  $name
     */
    public static function fromName(string $name): CategoryData
    {
        return new self([
            'name' => $name,
        ]);
    }
}
