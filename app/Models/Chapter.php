<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Chapter extends Model
{
    use SoftDeletes;

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

    /** 状態ごとのバッジ配色（Tailwindクラス） */
    public const STATUS_COLORS = [
        'not_started' => 'bg-stone-100 text-stone-500 ring-stone-500/15',
        'writing' => 'bg-amber-50 text-amber-700 ring-amber-600/15',
        'revising' => 'bg-violet-50 text-violet-700 ring-violet-600/15',
        'proofread' => 'bg-sky-50 text-sky-700 ring-sky-600/15',
        'ready' => 'bg-emerald-50 text-emerald-700 ring-emerald-600/15',
    ];

    /** 状態の日本語ラベルを返す */
    public function statusLabel(): string
    {
        return self::STATUSES[$this->status] ?? $this->status ?? '未設定';
    }

    /** 状態バッジの配色クラスを返す */
    public function statusColor(): string
    {
        return self::STATUS_COLORS[$this->status] ?? 'bg-stone-100 text-stone-600 ring-stone-500/15';
    }

    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }

    public function versions(): HasMany
    {
        // 新しい順。同一秒の保存でも順序が安定するよう id でもタイブレークする
        return $this->hasMany(ChapterVersion::class)->latest()->latest('id');
    }
}
