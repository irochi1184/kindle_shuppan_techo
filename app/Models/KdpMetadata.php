<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KdpMetadata extends Model
{
    // テーブル名は kdp_metadata（複数形の自動推測 kdp_metadata と一致させる）
    protected $table = 'kdp_metadata';

    protected $fillable = [
        'book_id', 'description', 'keywords_json', 'categories_json',
        'price', 'royalty_plan',
    ];

    protected $casts = [
        'keywords_json' => 'array',
        'categories_json' => 'array',
    ];

    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }
}
