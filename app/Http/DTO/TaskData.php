<?php

namespace App\Http\DTO;

use Carbon\CarbonImmutable;
use Illuminate\Http\Request;
use Spatie\DataTransferObject\DataTransferObject;

class TaskData extends DataTransferObject
{
    /** @var int|null */
    public $id;

    /** @var \App\Http\DTO\CategoryData|null */
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
     * Construct a new TaskData object.
     *
     * @param  array  $parameters
     */
    public function __construct(array $parameters)
    {
        $dueDate = $parameters['due_date'] ?? null;
        $parameters['id'] = isset($parameters['id']) ? (int) $parameters['id'] : null;
        $parameters['user_id'] = isset($parameters['user_id']) ? (int) $parameters['user_id'] : null;
        $parameters['title'] = isset($parameters['title']) ? (string) $parameters['title'] : null;
        $parameters['description'] = isset($parameters['description']) ? (string) $parameters['description'] : null;
        $parameters['report_to'] = isset($parameters['report_to']) ? (string) $parameters['report_to'] : null;
        $parameters['complete'] = isset($parameters['complete']) ? (bool) $parameters['complete'] : false;

        if ($dueDate) {
            if (is_string($dueDate)) {
                $parameters['due_date'] = new CarbonImmutable($dueDate);
            } elseif (! $dueDate instanceof CarbonImmutable) {
                $parameters['due_date'] = null;
            }
        }

        $parameters['category'] = isset($parameters['category']) ? CategoryData::fromName($parameters['category']) : null;

        parent::__construct($parameters);
    }

    /**
     * Create a new TaskData object from request.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public static function fromRequest(Request $request): TaskData
    {
        return static::fromArray($request->only(['title', 'description', 'report_to', 'complete', 'due_date', 'category']));
    }

    /**
     * Create a new TaskData object from an array.
     *
     * @param  array  $data
     */
    public static function fromArray(array $data): TaskData
    {
        return new self([
            'id' => $data['id'] ?? null,
            'title' => $data['title'] ?? null,
            'description' => $data['description'] ?? null,
            'due_date' => $data['due_date'] ?? null,
            'complete' => $data['complete'] ?? null,
            'report_to' => $data['report_to'] ?? null,
            'category' => $data['category'] ?? null,
        ]);
    }

    /**
     * Create a new TaskData object from email data.
     *
     * @param  array  $data
     */
    public static function fromEmail(array $data): TaskData
    {
        return new self([
            'id' => $data['id'] ?? null,
            'title' => strtoupper($data['subject']) ?? null,
            'description' => $data['body'] ?? null,
            'complete' => false,
            'report_to' => $data['from_name'] ?? null,
        ]);
    }
}