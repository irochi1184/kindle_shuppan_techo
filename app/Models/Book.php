<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Book extends Model
{
    protected $fillable = [
        'title', 'subtitle', 'author_name', 'target_reader',
        'book_goal', 'reader_benefit', 'description', 'status',
    ];

    public function chapters(): HasMany
    {
        return $this->hasMany(Chapter::class)->orderBy('sort_order');
    }

    public function publishingTasks(): HasMany
    {
        return $this->hasMany(PublishingTask::class)->orderBy('sort_order');
    }

    public function kdpMetadata(): HasOne
    {
        return $this->hasOne(KdpMetadata::class);
    }
}
