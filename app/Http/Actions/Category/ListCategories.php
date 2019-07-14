<?php

namespace App\Http\Actions\Category;

use PerfectOblivion\Actions\Action;
use App\Services\Category\ListCategoriesService;
use App\Http\Responders\Category\ListCategoriesResponder;

class ListCategories extends Action
{
    /** @var \App\Http\Responders\Category\ListCategoriesResponder */
    private $responder;

    /**
    * Construct a new ListCategories action.
    *
    * @param  \App\Http\Responders\Category\ListCategoriesResponder  $responder
    */
    public function __construct(ListCategoriesResponder $responder)
    {
        $this->responder = $responder;
    }

    /**
     * Execute the action.
     *
     * @return \Illuminate\Http\Response
     */
    public function __invoke()
    {
        $categories = ListCategoriesService::call();

        return $this->responder->withPayload($categories)->respond();
    }
}
