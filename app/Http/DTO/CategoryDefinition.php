<?php

namespace App\Http\DTO;

use Illuminate\Http\Request;

class CategoryDefinition extends Data
{
    /** @var string */
    public $definition_type;

    /** @var string */
    public $rule_type;

    /** @var string */
    public $definition;

    /** @var bool */
    public $optional;

    /**
     * Construct a new CategoryDefinition object.
     *
     * @param  array  $parameters
     */
    public function __construct(array $parameters)
    {
        parent::__construct($this->validate($parameters));
    }

    /**
     * Create a new CategoryDefinition object from request.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public static function fromRequest(Request $request): CategoryDefinition
    {
        return static::fromArray($request->only(['definition_type', 'rule_type', 'definition', 'optional']));
    }

    /**
     * Create a new CategoryDefinition object from an array.
     *
     * @param  array  $data
     */
    public static function fromArray(array $data): CategoryDefinition
    {
        return new self([
            'definition_type' => $data['definition_type'] ?? null,
            'rule_type' => $data['rule_type'] ?? null,
            'definition' => $data['definition'] ?? null,
            'optional' => $data['optional'] ?? null,
        ]);
    }

    /**
     * Validate the given parameters.
     *
     * @param  array  $parameters
     */
    public function validate(array $parameters): array
    {
        $parameters['definition_type'] = isset($parameters['definition_type']) ? (string) $parameters['definition_type'] : null;
        $parameters['rule_type'] = isset($parameters['rule_type']) ? (string) $parameters['rule_type'] : null;
        $parameters['definition'] = isset($parameters['definition']) ? (string) $parameters['definition'] : null;
        $parameters['optional'] = isset($parameters['optional']) ? (bool) $parameters['optional'] : false;

        return $parameters;
    }
}
