<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Definition extends Model
{
    /**
     * A Definition belongs to a Category.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
