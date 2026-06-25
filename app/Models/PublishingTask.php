<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PublishingTask extends Model
{
    protected $fillable = [
        'book_id', 'title', 'description', 'is_done', 'sort_order',
    ];

    protected $casts = [
        'is_done' => 'boolean',
    ];

    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }
}
