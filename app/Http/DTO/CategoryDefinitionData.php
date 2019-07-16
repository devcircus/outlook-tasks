<?php

namespace App\Http\DTO;

use Illuminate\Http\Request;

class CategoryDefinitionData extends Data
{
    /** @var string|null */
    public $words;

    /** @var string|null */
    public $exact;

    /** @var string|null */
    public $regex;

    /** @var string */
    public $type;

    /**
     * Construct a new CategoryDefinitionData object.
     *
     * @param  array  $parameters
     */
    public function __construct(array $parameters)
    {
        parent::__construct($this->validate($parameters));
    }

    /**
     * Create a new CategoryDefinitionData object from request.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public static function fromRequest(Request $request): CategoryDefinitionData
    {
        return static::fromArray($request->only(['words', 'exact', 'regex', 'type']));
    }

    /**
     * Create a new CategoryDefinitionData object from an array.
     *
     * @param  array  $data
     */
    public static function fromArray(array $data): CategoryDefinitionData
    {
        return new self([
            'words' => $data['words'] ?? null,
            'exact' => $data['exact'] ?? null,
            'regex' => $data['regex'] ?? null,
            'type' => $data['type'],
        ]);
    }

    /**
     * Validate the given parameters.
     *
     * @param  array  $parameters
     */
    public function validate(array $parameters): array
    {
        $parameters['words'] = isset($parameters['words']) ? (string) $parameters['words'] : null;
        $parameters['exact'] = isset($parameters['exact']) ? (string) $parameters['exact'] : null;
        $parameters['regex'] = isset($parameters['regex']) ? (string) $parameters['regex'] : null;
        $parameters['type'] = isset($parameters['type']) ? (string) $parameters['type'] : null;

        return $parameters;
    }
}
