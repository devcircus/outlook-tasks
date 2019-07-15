<?php

namespace App\Events\Models\Task;

use App\Models\Task;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;

class Deleting
{
    use Dispatchable, SerializesModels;

    /** @var \App\Models\Task */
    public $task;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Task $task)
    {
        $this->task = $task;
    }
}
