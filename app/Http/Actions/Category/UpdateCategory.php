<?php

namespace App\Http\Actions\Category;

use App\Models\Category;
use Illuminate\Http\Request;
use PerfectOblivion\Actions\Action;
use App\Http\DTO\Category as CategoryData;
use Illuminate\Contracts\Auth\Access\Gate;
use App\Services\Category\UpdateCategoryService;
use App\Http\Responders\Category\UpdateCategoryResponder;

class UpdateCategory extends Action
{
    /** @var \Illuminate\Contracts\Auth\Access\Gate */
    private $gate;

    /** @var \App\Http\Responders\Category\UpdateCategoryResponder */
    private $responder;

    /**
     * Construct a new UpdateCategory action.
     *
     * @param  \Illuminate\Contracts\Auth\Access\Gate  $gate
     * @param  \App\Http\Responders\Category\UpdateCategoryResponder  $responder
     */
    public function __construct(Gate $gate, UpdateCategoryResponder $responder)
    {
        $this->gate = $gate;
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
        if (! $this->gate->allows('update-category', $category)) {
            return redirect()->back()->with(['warning' => 'You do not have permission to edit this category.']);
        }

        $updated = UpdateCategoryService::call($category, CategoryData::fromRequest($request), $request->user());

        return $this->responder->withPayload($updated)->respond();
    }
}
