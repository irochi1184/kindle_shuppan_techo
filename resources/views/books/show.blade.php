@extends('layouts.app')

@section('title', $book->title . ' — Kindle出版手帳')

@php
    $meta = $book->kdpMetadata;
    $doneCount = $book->publishingTasks->where('is_done', true)->count();
    $taskTotal = $book->publishingTasks->count();
    $inputClass = 'w-full rounded-lg border border-stone-300 px-3 py-2 text-sm focus:border-amber-500 focus:ring-1 focus:ring-amber-500 outline-none';
@endphp

@section('content')
    <div class="mb-6">
        <a href="{{ route('books.index') }}" class="inline-flex items-center gap-1 text-sm text-stone-500 hover:text-amber-700">
            <x-icon name="arrow-left" class="w-4 h-4" />本の一覧に戻る
        </a>
    </div>

    {{-- 本のヘッダー --}}
    <div class="bg-white rounded-xl border border-stone-200 p-6 mb-6">
        <div class="flex items-start justify-between gap-4">
            <div>
                <span class="rounded-full bg-amber-50 text-amber-700 text-xs font-medium px-3 py-1">{{ $book->statusLabel() }}</span>
                <h1 class="text-2xl font-bold mt-2">{{ $book->title }}</h1>
                @if ($book->subtitle)
                    <p class="text-stone-500 mt-1">{{ $book->subtitle }}</p>
                @endif
                @if ($book->author_name)
                    <p class="text-sm text-stone-400 mt-2">著者：{{ $book->author_name }}</p>
                @endif
            </div>
            <div class="flex flex-col gap-2 shrink-0">
                <a href="{{ route('books.edit', $book) }}"
                   class="inline-flex items-center justify-center gap-1.5 rounded-lg border border-stone-300 px-4 py-2 text-sm font-medium hover:bg-stone-50">
                    <x-icon name="pencil" class="w-4 h-4" />本の情報を編集</a>
                <a href="{{ route('books.export.markdown', $book) }}"
                   class="inline-flex items-center justify-center gap-1.5 rounded-lg border border-stone-300 px-4 py-2 text-sm font-medium hover:bg-stone-50">
                    <x-icon name="download" class="w-4 h-4" />原稿をMarkdownで書き出す</a>
            </div>
        </div>

        @if ($book->target_reader || $book->book_goal || $book->reader_benefit)
            <dl class="grid sm:grid-cols-3 gap-4 mt-6 pt-6 border-t border-stone-100 text-sm">
                @if ($book->target_reader)
                    <div><dt class="text-stone-400 mb-1">想定読者</dt><dd class="whitespace-pre-line">{{ $book->target_reader }}</dd></div>
                @endif
                @if ($book->book_goal)
                    <div><dt class="text-stone-400 mb-1">本の目的</dt><dd class="whitespace-pre-line">{{ $book->book_goal }}</dd></div>
                @endif
                @if ($book->reader_benefit)
                    <div><dt class="text-stone-400 mb-1">読者が得るもの</dt><dd class="whitespace-pre-line">{{ $book->reader_benefit }}</dd></div>
                @endif
            </dl>
        @endif
    </div>

    {{-- 章（原稿） --}}
    <section class="bg-white rounded-xl border border-stone-200 p-6 mb-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-bold">章（原稿）</h2>
            <a href="{{ route('books.chapters.create', $book) }}"
               class="inline-flex items-center gap-1.5 rounded-lg bg-amber-600 px-3.5 py-1.5 text-white text-sm font-medium hover:bg-amber-700">
                <x-icon name="plus" class="w-4 h-4" />章を追加</a>
        </div>

        @forelse ($book->chapters as $chapter)
            <a href="{{ route('chapters.show', $chapter) }}"
               class="flex items-center justify-between gap-4 rounded-lg border border-stone-100 px-4 py-3 mb-2 hover:border-amber-300 hover:bg-amber-50/30 transition">
                <div class="flex items-center gap-3 min-w-0">
                    <span class="shrink-0 w-7 h-7 rounded-full bg-stone-100 text-stone-500 text-xs flex items-center justify-center">{{ $chapter->sort_order }}</span>
                    <span class="font-medium text-stone-800 truncate">{{ $chapter->title }}</span>
                </div>
                <div class="flex items-center gap-3 shrink-0 text-xs">
                    <span class="text-stone-400">{{ number_format($chapter->word_count) }} 字</span>
                    <span class="rounded-full bg-stone-100 text-stone-600 px-2.5 py-1">{{ $chapter->statusLabel() }}</span>
                </div>
            </a>
        @empty
            <p class="text-sm text-stone-500 py-4 text-center">まだ章がありません。「章を追加」から書き始めましょう。</p>
        @endforelse
    </section>

    <div class="grid lg:grid-cols-2 gap-6">
        {{-- 出版前チェックリスト --}}
        <section class="bg-white rounded-xl border border-stone-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-bold">出版前チェックリスト</h2>
                <span class="text-sm text-stone-400">{{ $doneCount }} / {{ $taskTotal }}</span>
            </div>

            <div class="space-y-1">
                @forelse ($book->publishingTasks as $task)
                    <form action="{{ route('tasks.toggle', $task) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="w-full flex items-center gap-3 rounded-lg px-2 py-2 text-left hover:bg-stone-50">
                            <span class="shrink-0 w-5 h-5 rounded-md border flex items-center justify-center transition
                                {{ $task->is_done ? 'bg-emerald-500 border-emerald-500 text-white' : 'border-stone-300 text-transparent' }}">
                                <x-icon name="check" class="w-3.5 h-3.5" />
                            </span>
                            <span class="text-sm {{ $task->is_done ? 'text-stone-400 line-through' : 'text-stone-700' }}">{{ $task->title }}</span>
                        </button>
                    </form>
                @empty
                    <p class="text-sm text-stone-500">チェック項目がありません。</p>
                @endforelse
            </div>
        </section>

        {{-- 出版情報（KDP） --}}
        <section class="bg-white rounded-xl border border-stone-200 p-6">
            <h2 class="text-lg font-bold mb-4">出版情報</h2>
            <form action="{{ route('books.metadata.update', $book) }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-sm font-medium text-stone-700 mb-1">説明文</label>
                    <textarea name="description" rows="3" class="{{ $inputClass }}"
                              placeholder="ストアに載せる紹介文">{{ old('description', $meta->description ?? '') }}</textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-stone-700 mb-1">キーワード（1行に1つ・最大7つ）</label>
                    <textarea name="keywords" rows="3" class="{{ $inputClass }}"
                              placeholder="例：&#10;インストラクショナルデザイン&#10;研修設計">{{ old('keywords', isset($meta) ? implode("\n", $meta->keywords_json ?? []) : '') }}</textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-stone-700 mb-1">カテゴリ候補（1行に1つ）</label>
                    <textarea name="categories" rows="2" class="{{ $inputClass }}"
                              placeholder="例：&#10;教育&#10;ビジネス">{{ old('categories', isset($meta) ? implode("\n", $meta->categories_json ?? []) : '') }}</textarea>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-stone-700 mb-1">価格（円）</label>
                        <input type="number" name="price" min="0" value="{{ old('price', $meta->price ?? '') }}" class="{{ $inputClass }}">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-stone-700 mb-1">ロイヤリティ設定メモ</label>
                        <input type="text" name="royalty_plan" value="{{ old('royalty_plan', $meta->royalty_plan ?? '') }}" class="{{ $inputClass }}" placeholder="例：70%">
                    </div>
                </div>

                <button type="submit" class="rounded-lg bg-amber-600 px-4 py-2 text-white text-sm font-medium hover:bg-amber-700">出版情報を保存</button>
            </form>
        </section>
    </div>
@endsection
