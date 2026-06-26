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

    /** 出版状態の選択肢（値 => 表示ラベル） */
    public const STATUSES = [
        'planning' => '企画中',
        'writing' => '執筆中',
        'editing' => '推敲中',
        'published' => '出版済み',
    ];

    /** 状態の日本語ラベルを返す */
    public function statusLabel(): string
    {
        return self::STATUSES[$this->status] ?? $this->status ?? '未設定';
    }

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
