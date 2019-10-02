<?php

namespace App\Http\Actions\Category;

use App\Models\Category;
use PerfectOblivion\Actions\Action;
use App\Services\Category\DeleteCategoryService;
use App\Http\Responders\Category\DeleteCategoryResponder;
use Illuminate\Http\Request;

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
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Category  $category
     *
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request, Category $category)
    {
        DeleteCategoryService::call($category, $request->user());

        return $this->responder->respond();
    }
}
