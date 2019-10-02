<?php

namespace App\Http\Actions\Category;

use App\Models\Category;
use PerfectOblivion\Actions\Action;
use App\Services\Category\RestoreCategoryService;
use App\Http\Responders\Category\RestoreCategoryResponder;
use Illuminate\Http\Request;

class RestoreCategory extends Action
{
    /** @var \App\Http\Responders\Category\RestoreCategoryResponder */
    private $responder;

    /**
    * Construct a new RestoreCategory action.
    *
    * @param  \App\Http\Responders\Category\RestoreCategoryResponder  $responder
    */
    public function __construct(RestoreCategoryResponder $responder)
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
        $restored = RestoreCategoryService::call($category, $request->user());

        return $this->responder->withPayload($restored)->respond();
    }
}
