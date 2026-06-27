<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Book extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id', 'title', 'subtitle', 'author_name', 'target_reader',
        'book_goal', 'reader_benefit', 'description', 'status', 'cover_path',
    ];

    /** 出版状態の選択肢（値 => 表示ラベル） */
    public const STATUSES = [
        'planning' => '企画中',
        'writing' => '執筆中',
        'editing' => '推敲中',
        'published' => '出版済み',
    ];

    /** 状態ごとのバッジ配色（Tailwindクラス） */
    public const STATUS_COLORS = [
        'planning' => 'bg-sky-50 text-sky-700 ring-sky-600/15',
        'writing' => 'bg-amber-50 text-amber-700 ring-amber-600/15',
        'editing' => 'bg-violet-50 text-violet-700 ring-violet-600/15',
        'published' => 'bg-emerald-50 text-emerald-700 ring-emerald-600/15',
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

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /** 表紙画像の公開URL（未設定なら null）。配信ホストに依存しないルート相対パスで返す */
    public function coverUrl(): ?string
    {
        if (! $this->cover_path) {
            return null;
        }

        return parse_url(Storage::disk('public')->url($this->cover_path), PHP_URL_PATH);
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
