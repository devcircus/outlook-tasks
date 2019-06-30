<?php

namespace App\Outlook\Query;

class QueryParameter
{
    /** @var array */
    private $default = [
        "\$select" => 'receivedDateTime,from,subject,body',
        "\$orderBy" => 'receivedDateTime DESC',
        "\$count" => 'true',
    ];

    /** @var array */
    private $queryParameters = [];

    /**
     * Construct a new QueryParameter.
     *
     * @param  string|null  $key
     * @param  string|null  $value
     */
    public function __construct(?string $key = null, ?string $value = null)
    {
        if ($key && $value) {
            $this->queryParameters["\${$key}"] = $value;
        }
    }

    /**
     * Create a new QueryParameter.
     *
     * @param  string  $key
     * @param  string  $value
     */
    public static function add(string $key, string $value): QueryParameter
    {
        return new self($key, $value);
    }

    /**
     * Set a query parameter.
     *
     * @param  string  $key
     * @param  string  $value
     */
    public function set(string $key, string $value): QueryParameter
    {
        $this->queryParameters["\${$key}"] = $value;

        return $this;
    }

    /**
     * Remove a query parameter, so default is used.
     *
     * @param  string  $key
     */
    public function reset(string $key): void
    {
        unset($this->queryParameters["\${$key}"]);
    }

    /**
     * Get the full queryParameters array.
     */
    public function get(): array
    {
        return array_merge($this->default, $this->queryParameters);
    }
}
