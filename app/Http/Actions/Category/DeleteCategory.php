<?php

namespace App\Http\Actions\Category;

use App\Models\Category;
use PerfectOblivion\Actions\Action;
use App\Services\Category\DeleteCategoryService;
use App\Http\Responders\Category\DeleteCategoryResponder;

class DeleteCategory extends Action
{
    /** @var \App\Http\Responders\Category\DeleteCategoryResponder */
    private $responder;

    /**
    * Construct a new DeleteCategory action.
    *
    * @param  \App\Http\Responders\Category\DeleteCategoryResponder  $responder
    */
    public function __construct(DeleteCategoryResponder $responder)
    {
        $this->responder = $responder;
    }

    /**
     * Execute the action.
     *
     * @param  \App\Models\Category  $category
     *
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Category $category)
    {
        DeleteCategoryService::call($category);

        return $this->responder->respond();
    }
}
