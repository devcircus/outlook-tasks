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

    /**
     * Update a definition.
     *
     * @param  array  $data
     */
    public function updateDefinition(array $data): Definition
    {
        return tap($this, function ($instance) use ($data) {
            return $instance->update($data);
        });
    }

    /**
     * Delete a definition.
     */
    public function deleteDefinition(): bool
    {
        return $this->delete();
    }
}
