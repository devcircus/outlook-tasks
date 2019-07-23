<?php

namespace App\Outlook\Query;

class QueryParameter
{
    /** @var array */
    private $default = [
        'list' => [
            '$select' => 'receivedDateTime,from,subject,body',
            '$orderBy' => 'receivedDateTime DESC',
            '$count' => 'true',
        ],
        'send' => [],
    ];

    /** @var array */
    private $queryParameters = [
        'list' => [],
        'send' => [],
    ];

    /**
     * Construct a new QueryParameter.
     *
     * @param  string|null  $key
     * @param  string|null  $value
     * @param  string|null  $type
     */
    public function __construct(?string $key = null, ?string $value = null, ?string $type = 'list')
    {
        if ($key && $value) {
            $this->queryParameters[$type]["\${$key}"] = $value;
        }
    }

    /**
     * Create a new QueryParameter.
     *
     * @param  string  $key
     * @param  string  $value
     * @param  string  $type
     */
    public static function add(string $key, string $value, string $type): QueryParameter
    {
        return new self($key, $value, $type);
    }

    /**
     * Set a query parameter.
     *
     * @param  string  $key
     * @param  string  $value
     * @param  string  $type
     */
    public function set(string $key, string $value, string $type): QueryParameter
    {
        $this->queryParameters[$type]["\${$key}"] = $value;

        return $this;
    }

    /**
     * Remove a query parameter, so default is used.
     *
     * @param  string  $key
     * @param  string  $type
     */
    public function reset(string $key, string $type): void
    {
        unset($this->queryParameters[$type]["\${$key}"]);
    }

    /**
     * Get the full queryParameters array.
     *
     * @param  string  $type
     */
    public function get(string $type): array
    {
        return array_merge($this->default[$type], $this->queryParameters[$type]);
    }
}
