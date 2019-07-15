<?php

namespace App\Listeners\Models\Task;

use App\Events\Models\Task\Deleting;
use App\Http\DTO\TaskData;

class ClearDueDate
{
    /**
     * Handle the event.
     *
     * @param  \App\Listeners\Models\Task\Deleting  $event
     */
    public function handle(Deleting $event): void
    {
        $data = TaskData::fromArray(['due_date' => null]);

        $event->task->updateTaskData($data->only(['due_date']));
    }
}
