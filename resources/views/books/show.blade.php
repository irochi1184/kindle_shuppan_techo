@extends('layouts.app')

@section('title', $book->title . ' — Kindle出版手帳')

@php
    $meta = $book->kdpMetadata;
    $doneCount = $book->publishingTasks->where('is_done', true)->count();
    $taskTotal = $book->publishingTasks->count();
    $progress = $taskTotal > 0 ? round($doneCount / $taskTotal * 100) : 0;
    $totalWords = $book->chapters->sum('word_count');
    $inputClass = 'w-full rounded-lg border border-stone-300 bg-white px-3 py-2 text-sm transition focus:border-brand-500 focus:ring-2 focus:ring-brand-500/25 outline-none placeholder:text-stone-400';
@endphp

@section('content')
    <div class="mb-5">
        <a href="{{ route('books.index') }}" class="inline-flex items-center gap-1 text-sm text-stone-500 hover:text-brand-700 transition">
            <x-icon name="arrow-left" class="w-4 h-4" />本の一覧に戻る
        </a>
    </div>

    {{-- 本のヘッダー --}}
    <div class="bg-white rounded-2xl border border-stone-200/80 shadow-card p-6 sm:p-7 mb-6">
        <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-5">
            <div class="flex items-start gap-5 min-w-0">
                @if ($book->coverUrl())
                    <img src="{{ $book->coverUrl() }}" alt="表紙" class="w-24 h-36 shrink-0 rounded-lg object-cover border border-stone-200 shadow-sm">
                @endif
                <div class="min-w-0">
                    <x-badge :color="$book->statusColor()">{{ $book->statusLabel() }}</x-badge>
                    <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-stone-900 mt-3">{{ $book->title }}</h1>
                    @if ($book->subtitle)
                        <p class="text-stone-500 mt-1.5">{{ $book->subtitle }}</p>
                    @endif
                    @if ($book->author_name)
                        <p class="text-sm text-stone-400 mt-2.5">著者：{{ $book->author_name }}</p>
                    @endif
                </div>
            </div>
            <div class="grid grid-cols-2 sm:grid-cols-1 gap-2 shrink-0">
                <a href="{{ route('books.edit', $book) }}"
                   class="inline-flex items-center justify-center gap-1.5 rounded-lg border border-stone-300 bg-white px-4 py-2 text-sm font-medium text-stone-700 hover:bg-stone-50 transition">
                    <x-icon name="pencil" class="w-4 h-4" />編集
                </a>
                <a href="{{ route('books.kdp-sheet', $book) }}"
                   class="inline-flex items-center justify-center gap-1.5 rounded-lg border border-stone-300 bg-white px-4 py-2 text-sm font-medium text-stone-700 hover:bg-stone-50 transition">
                    <x-icon name="document" class="w-4 h-4" />KDP登録シート
                </a>
                <a href="{{ route('books.export.epub', $book) }}"
                   class="inline-flex items-center justify-center gap-1.5 rounded-lg bg-brand-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-brand-700 active:scale-[0.98] transition">
                    <x-icon name="download" class="w-4 h-4" />EPUBで書き出す
                </a>
                <a href="{{ route('books.export.markdown', $book) }}"
                   class="inline-flex items-center justify-center gap-1.5 rounded-lg border border-stone-300 bg-white px-4 py-2 text-sm font-medium text-stone-700 hover:bg-stone-50 transition">
                    <x-icon name="download" class="w-4 h-4" />Markdown
                </a>
            </div>
        </div>

        {{-- ミニ統計 --}}
        <dl class="grid grid-cols-3 gap-3 mt-6">
            <div class="rounded-xl bg-stone-50 px-4 py-3">
                <dt class="text-xs text-stone-500">章の数</dt>
                <dd class="text-lg font-bold text-stone-900 mt-0.5">{{ $book->chapters->count() }}<span class="text-xs font-medium text-stone-400 ml-1">本</span></dd>
            </div>
            <div class="rounded-xl bg-stone-50 px-4 py-3">
                <dt class="text-xs text-stone-500">合計文字数</dt>
                <dd class="text-lg font-bold text-stone-900 mt-0.5">{{ number_format($totalWords) }}<span class="text-xs font-medium text-stone-400 ml-1">字</span></dd>
            </div>
            <div class="rounded-xl bg-stone-50 px-4 py-3">
                <dt class="text-xs text-stone-500">出版準備</dt>
                <dd class="text-lg font-bold text-stone-900 mt-0.5">{{ $progress }}<span class="text-xs font-medium text-stone-400 ml-1">%</span></dd>
            </div>
        </dl>

        @if ($book->target_reader || $book->book_goal || $book->reader_benefit)
            <dl class="grid sm:grid-cols-3 gap-5 mt-6 pt-6 border-t border-stone-100 text-sm">
                @if ($book->target_reader)
                    <div><dt class="text-xs font-medium text-stone-400 mb-1">想定読者</dt><dd class="whitespace-pre-line text-stone-700">{{ $book->target_reader }}</dd></div>
                @endif
                @if ($book->book_goal)
                    <div><dt class="text-xs font-medium text-stone-400 mb-1">本の目的</dt><dd class="whitespace-pre-line text-stone-700">{{ $book->book_goal }}</dd></div>
                @endif
                @if ($book->reader_benefit)
                    <div><dt class="text-xs font-medium text-stone-400 mb-1">読者が得るもの</dt><dd class="whitespace-pre-line text-stone-700">{{ $book->reader_benefit }}</dd></div>
                @endif
            </dl>
        @endif
    </div>

    {{-- 章（原稿） --}}
    <section class="bg-white rounded-2xl border border-stone-200/80 shadow-card p-6 sm:p-7 mb-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-base font-semibold text-stone-900">章（原稿）</h2>
            <a href="{{ route('books.chapters.create', $book) }}"
               class="inline-flex items-center gap-1.5 rounded-lg bg-brand-600 px-3.5 py-2 text-white text-sm font-medium shadow-sm hover:bg-brand-700 active:scale-[0.98] transition">
                <x-icon name="plus" class="w-4 h-4" />章を追加
            </a>
        </div>

        @if ($book->chapters->isNotEmpty())
            <div class="flex items-center justify-between mb-2 px-1">
                <p class="text-xs text-stone-400">ハンドルをドラッグして並べ替えられます。</p>
                <span data-reorder-status class="text-xs text-stone-400"></span>
            </div>
        @endif

        <div class="space-y-2" data-sortable-chapters data-reorder-url="{{ route('books.chapters.reorder', $book) }}">
            @forelse ($book->chapters as $chapter)
                <div data-chapter-id="{{ $chapter->id }}"
                     class="group flex items-center justify-between gap-3 rounded-xl border border-stone-100 px-3 py-3 hover:border-brand-200 hover:bg-brand-50/40 transition">
                    <div class="flex items-center gap-2 min-w-0">
                        <button type="button" data-drag-handle aria-label="ドラッグして並べ替え"
                                class="shrink-0 cursor-grab active:cursor-grabbing text-stone-300 hover:text-stone-500 transition touch-none">
                            <x-icon name="grip" class="w-5 h-5" />
                        </button>
                        <span data-chapter-order class="shrink-0 w-7 h-7 rounded-lg bg-stone-100 text-stone-500 text-xs font-semibold flex items-center justify-center group-hover:bg-brand-100 group-hover:text-brand-700 transition">{{ $loop->iteration }}</span>
                        <a href="{{ route('chapters.show', $chapter) }}" class="font-medium text-stone-800 truncate hover:text-brand-800 transition">{{ $chapter->title }}</a>
                    </div>
                    <div class="flex items-center gap-3 shrink-0">
                        <span class="text-xs text-stone-400 tabular-nums">{{ number_format($chapter->word_count) }} 字</span>
                        <x-badge :color="$chapter->statusColor()">{{ $chapter->statusLabel() }}</x-badge>
                    </div>
                </div>
            @empty
                <div class="rounded-xl border border-dashed border-stone-200 py-8 text-center">
                    <p class="text-sm text-stone-500">まだ章がありません。「章を追加」から書き始めましょう。</p>
                </div>
            @endforelse
        </div>
    </section>

    <div class="grid lg:grid-cols-2 gap-6">
        {{-- 出版前チェックリスト --}}
        <section class="bg-white rounded-2xl border border-stone-200/80 shadow-card p-6 sm:p-7">
            <div class="flex items-center justify-between mb-3">
                <h2 class="text-base font-semibold text-stone-900">出版前チェックリスト</h2>
                <span class="text-sm font-medium text-stone-500 tabular-nums">{{ $doneCount }} / {{ $taskTotal }}</span>
            </div>

            {{-- 進捗バー --}}
            <div class="h-1.5 w-full rounded-full bg-stone-100 overflow-hidden mb-4">
                <div class="h-full rounded-full bg-emerald-500 transition-all duration-500" style="width: {{ $progress }}%"></div>
            </div>

            <div class="space-y-0.5">
                @forelse ($book->publishingTasks as $task)
                    <form action="{{ route('tasks.toggle', $task) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="w-full flex items-center gap-3 rounded-lg px-2 py-2 text-left hover:bg-stone-50 transition">
                            <span class="shrink-0 w-5 h-5 rounded-md border flex items-center justify-center transition
                                {{ $task->is_done ? 'bg-emerald-500 border-emerald-500 text-white' : 'border-stone-300 text-transparent' }}">
                                <x-icon name="check" class="w-3.5 h-3.5" />
                            </span>
                            <span class="text-sm transition {{ $task->is_done ? 'text-stone-400 line-through' : 'text-stone-700' }}">{{ $task->title }}</span>
                        </button>
                    </form>
                @empty
                    <p class="text-sm text-stone-500">チェック項目がありません。</p>
                @endforelse
            </div>
        </section>

        {{-- 出版情報（KDP） --}}
        <section class="bg-white rounded-2xl border border-stone-200/80 shadow-card p-6 sm:p-7">
            <h2 class="text-base font-semibold text-stone-900 mb-4">出版情報</h2>
            <form action="{{ route('books.metadata.update', $book) }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-sm font-medium text-stone-700 mb-1.5">説明文</label>
                    <textarea name="description" rows="3" class="{{ $inputClass }}"
                              placeholder="ストアに載せる紹介文">{{ old('description', $meta->description ?? '') }}</textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-stone-700 mb-1.5">キーワード<span class="text-stone-400 font-normal ml-1">（1行に1つ・最大7つ）</span></label>
                    <textarea name="keywords" rows="3" class="{{ $inputClass }}"
                              placeholder="例：&#10;インストラクショナルデザイン&#10;研修設計">{{ old('keywords', isset($meta) ? implode("\n", $meta->keywords_json ?? []) : '') }}</textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-stone-700 mb-1.5">カテゴリ候補<span class="text-stone-400 font-normal ml-1">（1行に1つ）</span></label>
                    <textarea name="categories" rows="2" class="{{ $inputClass }}"
                              placeholder="例：&#10;教育&#10;ビジネス">{{ old('categories', isset($meta) ? implode("\n", $meta->categories_json ?? []) : '') }}</textarea>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-stone-700 mb-1.5">価格（円）</label>
                        <input type="number" name="price" min="0" value="{{ old('price', $meta->price ?? '') }}" class="{{ $inputClass }}">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-stone-700 mb-1.5">ロイヤリティ設定メモ</label>
                        <input type="text" name="royalty_plan" value="{{ old('royalty_plan', $meta->royalty_plan ?? '') }}" class="{{ $inputClass }}" placeholder="例：70%">
                    </div>
                </div>

                <button type="submit" class="inline-flex items-center justify-center rounded-lg bg-brand-600 px-4 py-2.5 text-white text-sm font-medium shadow-sm hover:bg-brand-700 active:scale-[0.98] transition">出版情報を保存</button>
            </form>
        </section>
    </div>
@endsection
