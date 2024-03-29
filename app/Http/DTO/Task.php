<?php

namespace App\Http\DTO;

use Carbon\CarbonImmutable;
use Illuminate\Http\Request;

class Task extends Data
{

    /** @var string|null */
    public $category;

    /** @var int|null */
    public $user_id;

    /** @var string|null */
    public $title;

    /** @var string|null */
    public $description;

    /** @var string|null */
    public $report_to;

    /** @var \Carbon\CarbonImmutable|null */
    public $due_date;

    /** @var bool */
    public $complete;

    /**
     * Construct a new Task object.
     *
     * @param  array  $parameters
     */
    public function __construct(array $parameters)
    {
        parent::__construct($this->validate($parameters));
    }

    /**
     * Create a new Task object from request.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public static function fromRequest(Request $request): Task
    {
        return static::fromArray($request->only(['title', 'description', 'report_to', 'complete', 'due_date', 'category']));
    }

    /**
     * Create a new Task object from an array.
     *
     * @param  array  $data
     */
    public static function fromArray(array $data): Task
    {
        return new self([
            'title' => $data['title'] ?? null,
            'description' => $data['description'] ?? null,
            'due_date' => $data['due_date'] ?? null,
            'complete' => $data['complete'] ?? null,
            'report_to' => $data['report_to'] ?? null,
            'category' => $data['category'] ?? null,
        ]);
    }

    /**
     * Create a new Task object from email data.
     *
     * @param  array  $data
     */
    public static function fromEmail(array $data): Task
    {
        return new self([
            'title' => strtoupper($data['subject']) ?? null,
            'description' => $data['body'] ?? null,
            'complete' => false,
            'report_to' => $data['from_name'] ?? null,
        ]);
    }

    /**
     * Validate the given parameters.
     *
     * @param  array  $parameters
     */
    public function validate(array $parameters): array
    {
        $dueDate = $parameters['due_date'] ?? null;
        $parameters['user_id'] = isset($parameters['user_id']) ? (int) $parameters['user_id'] : null;
        $parameters['title'] = isset($parameters['title']) ? (string) $parameters['title'] : null;
        $parameters['description'] = isset($parameters['description']) ? (string) $parameters['description'] : null;
        $parameters['report_to'] = isset($parameters['report_to']) ? (string) $parameters['report_to'] : null;
        $parameters['complete'] = isset($parameters['complete']) ? (bool) $parameters['complete'] : false;
        $parameters['category'] = isset($parameters['category']) ? (string) $parameters['category'] : null;

        if ($dueDate) {
            if (is_string($dueDate)) {
                $parameters['due_date'] = CarbonImmutable::createFromFormat('Y-m-d', $dueDate);
            } elseif (! $dueDate instanceof CarbonImmutable) {
                $parameters['due_date'] = null;
            }
        }

        return $parameters;
    }
}
