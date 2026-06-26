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

    /** 章の状態の選択肢（値 => 表示ラベル） */
    public const STATUSES = [
        'not_started' => '未着手',
        'writing' => '執筆中',
        'revising' => '推敲中',
        'proofread' => '校正済み',
        'ready' => '出版準備完了',
    ];

    /** 状態の日本語ラベルを返す */
    public function statusLabel(): string
    {
        return self::STATUSES[$this->status] ?? $this->status ?? '未設定';
    }

    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }

    public function versions(): HasMany
    {
        return $this->hasMany(ChapterVersion::class)->latest();
    }
}
