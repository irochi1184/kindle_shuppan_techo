@extends('layouts.app')

@section('title', 'KDP登録シート — ' . $book->title)

@php
    $meta = $book->kdpMetadata;
    $description = $meta->description ?? $book->description ?? '';
    $keywords = $meta?->keywords_json ?? [];
    $categories = $meta?->categories_json ?? [];
    // Amazon KDP の目安文字数
    $descLimit = 4000;
    $keywordLimit = 50;
@endphp

@section('content')
    <div class="mb-5">
        <a href="{{ route('books.show', $book) }}" class="inline-flex items-center gap-1 text-sm text-stone-500 hover:text-brand-700 transition">
            <x-icon name="arrow-left" class="w-4 h-4" />本の詳細に戻る
        </a>
    </div>

    <div class="mb-7">
        <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-stone-900">KDP登録シート</h1>
        <p class="text-sm text-stone-500 mt-1.5">KDPの入稿画面にそのままコピーして貼り付けられます。文字数の目安も表示しています。</p>
    </div>

    @php
        $rowClass = 'bg-white rounded-2xl border border-stone-200/80 shadow-card p-5 sm:p-6';
        $copyBtn = 'inline-flex items-center gap-1.5 rounded-lg border border-stone-300 bg-white px-3 py-1.5 text-xs font-medium text-stone-700 hover:bg-stone-50 transition data-[copied=true]:border-emerald-300 data-[copied=true]:text-emerald-700';
    @endphp

    <div class="space-y-4">
        {{-- 基本情報 --}}
        <div class="{{ $rowClass }}">
            <h2 class="text-base font-semibold text-stone-900 mb-4">基本情報</h2>
            <div class="space-y-4">
                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <span class="text-sm font-medium text-stone-700">本のタイトル</span>
                        <button type="button" class="{{ $copyBtn }}" data-copy="#kdp-title"><x-icon name="document" class="w-3.5 h-3.5" /><span data-copy-label>コピー</span></button>
                    </div>
                    <p id="kdp-title" class="rounded-lg bg-stone-50 px-3 py-2 text-sm text-stone-800">{{ $book->title }}</p>
                </div>
                @if ($book->subtitle)
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <span class="text-sm font-medium text-stone-700">サブタイトル</span>
                            <button type="button" class="{{ $copyBtn }}" data-copy="#kdp-subtitle"><x-icon name="document" class="w-3.5 h-3.5" /><span data-copy-label>コピー</span></button>
                        </div>
                        <p id="kdp-subtitle" class="rounded-lg bg-stone-50 px-3 py-2 text-sm text-stone-800">{{ $book->subtitle }}</p>
                    </div>
                @endif
                @if ($book->author_name)
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <span class="text-sm font-medium text-stone-700">著者名</span>
                            <button type="button" class="{{ $copyBtn }}" data-copy="#kdp-author"><x-icon name="document" class="w-3.5 h-3.5" /><span data-copy-label>コピー</span></button>
                        </div>
                        <p id="kdp-author" class="rounded-lg bg-stone-50 px-3 py-2 text-sm text-stone-800">{{ $book->author_name }}</p>
                    </div>
                @endif
            </div>
        </div>

        {{-- 説明文 --}}
        <div class="{{ $rowClass }}">
            <div class="flex items-center justify-between mb-1.5">
                <h2 class="text-base font-semibold text-stone-900">紹介（説明）文</h2>
                <div class="flex items-center gap-3">
                    <span class="text-xs tabular-nums {{ mb_strlen($description) > $descLimit ? 'text-red-600 font-medium' : 'text-stone-400' }}">{{ number_format(mb_strlen($description)) }} / {{ number_format($descLimit) }}</span>
                    <button type="button" class="{{ $copyBtn }}" data-copy="#kdp-description"><x-icon name="document" class="w-3.5 h-3.5" /><span data-copy-label>コピー</span></button>
                </div>
            </div>
            @if ($description !== '')
                <pre id="kdp-description" class="rounded-lg bg-stone-50 px-3 py-2 text-sm text-stone-800 whitespace-pre-wrap font-sans">{{ $description }}</pre>
            @else
                <p class="text-sm text-stone-400">まだ説明文がありません。<a href="{{ route('books.show', $book) }}#" class="text-brand-700 hover:underline">出版情報</a>から入力できます。</p>
            @endif
        </div>

        {{-- キーワード --}}
        <div class="{{ $rowClass }}">
            <div class="flex items-center justify-between mb-3">
                <h2 class="text-base font-semibold text-stone-900">キーワード</h2>
                <span class="text-xs tabular-nums {{ count($keywords) > 7 ? 'text-red-600 font-medium' : 'text-stone-400' }}">{{ count($keywords) }} / 7 個</span>
            </div>
            @forelse ($keywords as $keyword)
                <div class="flex items-center justify-between gap-3 rounded-lg bg-stone-50 px-3 py-2 mb-2">
                    <span id="kdp-kw-{{ $loop->index }}" class="text-sm text-stone-800 min-w-0 truncate">{{ $keyword }}</span>
                    <div class="flex items-center gap-2 shrink-0">
                        <span class="text-xs tabular-nums {{ mb_strlen($keyword) > $keywordLimit ? 'text-red-600' : 'text-stone-400' }}">{{ mb_strlen($keyword) }}字</span>
                        <button type="button" class="{{ $copyBtn }}" data-copy="#kdp-kw-{{ $loop->index }}"><span data-copy-label>コピー</span></button>
                    </div>
                </div>
            @empty
                <p class="text-sm text-stone-400">まだキーワードがありません。</p>
            @endforelse
        </div>

        {{-- カテゴリ・価格 --}}
        <div class="grid sm:grid-cols-2 gap-4">
            <div class="{{ $rowClass }}">
                <h2 class="text-base font-semibold text-stone-900 mb-3">カテゴリ候補</h2>
                @forelse ($categories as $category)
                    <div class="flex items-center justify-between gap-3 rounded-lg bg-stone-50 px-3 py-2 mb-2">
                        <span id="kdp-cat-{{ $loop->index }}" class="text-sm text-stone-800 truncate">{{ $category }}</span>
                        <button type="button" class="{{ $copyBtn }} shrink-0" data-copy="#kdp-cat-{{ $loop->index }}"><span data-copy-label>コピー</span></button>
                    </div>
                @empty
                    <p class="text-sm text-stone-400">まだカテゴリ候補がありません。</p>
                @endforelse
            </div>

            <div class="{{ $rowClass }}">
                <h2 class="text-base font-semibold text-stone-900 mb-3">価格・ロイヤリティ</h2>
                <dl class="space-y-2 text-sm">
                    <div class="flex justify-between rounded-lg bg-stone-50 px-3 py-2">
                        <dt class="text-stone-500">価格</dt>
                        <dd class="font-medium text-stone-800">{{ $meta?->price !== null ? '¥' . number_format($meta->price) : '未設定' }}</dd>
                    </div>
                    <div class="flex justify-between rounded-lg bg-stone-50 px-3 py-2">
                        <dt class="text-stone-500">ロイヤリティ</dt>
                        <dd class="font-medium text-stone-800">{{ $meta?->royalty_plan ?: '未設定' }}</dd>
                    </div>
                </dl>
            </div>
        </div>
    </div>
@endsection
