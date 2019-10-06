<?php

namespace App\Http\Actions\Task;

use PerfectOblivion\Actions\Action;
use App\Http\Responders\Task\CreateTaskFromCategoryResponder;
use Inertia\Response;

class CreateTaskFromCategory extends Action
{
    /** @var \App\Http\Responders\Task\CreateTaskFromCategoryResponder */
    private $responder;

    /**
     * Construct a new CreateTask action.
     *
     * @param  \App\Http\Responders\Task\CreateTaskFromCategoryResponder  $responder
     */
    public function __construct(CreateTaskFromCategoryResponder $responder)
    {
        $this->responder = $responder;
    }

    /**
     * Execute the action.
     *
     * @param  string  $category_name
     */
    public function __invoke(?string $category_name = 'all'): Response
    {
        return $this->responder->withPayload([
            'category' => $category_name,
        ])->respond();
    }
}
