<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CategoryDefinition extends Model
{
    /**
     * A CategoryDefinition belongs to a Category.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
