<?php

namespace App\Http\DTO;

use App\Thing;
use Illuminate\Http\Request;

class UserData extends Thing
{
    /** @var string|null */
    public $name;

    /** @var string|null */
    public $email;

    /** @var string|null */
    public $password;

    /** @var bool */
    public $is_admin;

    /**
     * Construct a new UserData object.
     *
     * @param  array  $parameters
     */
    public function __construct(array $parameters)
    {
        parent::__construct($this->validate($parameters));
    }

    /**
     * Create a new UserData object from request.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public static function fromRequest(Request $request): UserData
    {
        return static::fromArray($request->only(['name', 'email', 'password']));
    }

    /**
     * Create a new UserData object from an array.
     *
     * @param  array  $data
     */
    public static function fromArray(array $data): UserData
    {
        return new self([
            'name' => $data['name'] ?? null,
            'email' => $data['email'] ?? null,
            'is_admin' => $data['is_admin'] ?? null,
            'password' => $data['password'] ?? null,
        ]);
    }

    /**
     * Validate the given parameters.
     *
     * @param  array  $parameters
     */
    public function validate(array $parameters): array
    {
        $name = isset($parameters['name']) ? (string) $parameters['name'] : null;
        $email = isset($parameters['email']) ? (string) $parameters['email'] : null;
        $password = isset($parameters['password']) ? (string) $parameters['password'] : null;
        $is_admin = isset($parameters['is_admin']) ? (bool) $parameters['is_admin'] : false;

        $parameters['name'] = $name;
        $parameters['email'] = $email;
        $parameters['password'] = $password;
        $parameters['is_admin'] = $is_admin;

        return $parameters;
    }
}
