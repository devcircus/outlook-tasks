<?php

namespace App\Http\Actions\Category;

use App\Http\DTO\Category;
use Illuminate\Http\Request;
use PerfectOblivion\Actions\Action;
use App\Services\Category\StoreCategoryService;
use App\Http\Responders\Category\StoreCategoryResponder;

class StoreCategory extends Action
{
    /** @var \App\Http\Responders\Category\StoreCategoryResponder */
    private $responder;

    /**
    * Construct a new StoreCategory action.
    *
    * @param  \App\Http\Responders\Category\StoreCategoryResponder  $responder
    */
    public function __construct(StoreCategoryResponder $responder)
    {
        $this->responder = $responder;
    }

    /**
     * Execute the action.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $category = StoreCategoryService::call(Category::fromRequest($request), $request->user());

        return $this->responder->withPayload($category)->respond();
    }
}
