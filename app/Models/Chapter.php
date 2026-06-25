<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Chapter extends Model
{
    protected $fillable = [
        'book_id', 'title', 'summary', 'body', 'sort_order', 'status', 'word_count',
    ];

    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }

    public function versions(): HasMany
    {
        return $this->hasMany(ChapterVersion::class)->latest();
    }
}
