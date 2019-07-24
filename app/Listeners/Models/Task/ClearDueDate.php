<?php

namespace App\Listeners\Models\Task;

use App\Http\DTO\Task;
use App\Events\Models\Task\Deleting;

class ClearDueDate
{
    /**
     * Handle the event.
     *
     * @param  \App\Listeners\Models\Task\Deleting  $event
     */
    public function handle(Deleting $event): void
    {
        $data = Task::fromArray(['due_date' => null]);

        $event->task->updateTaskData($data->only(['due_date']));
    }
}
