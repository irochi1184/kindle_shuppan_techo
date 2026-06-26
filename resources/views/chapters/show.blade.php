@extends('layouts.app')

@section('title', $chapter->title . ' — ' . $chapter->book->title)

@section('content')
    <div class="mb-6">
        <a href="{{ route('books.show', $chapter->book) }}" class="inline-flex items-center gap-1 text-sm text-stone-500 hover:text-amber-700">
            <x-icon name="arrow-left" class="w-4 h-4" />{{ $chapter->book->title }} に戻る
        </a>
    </div>

    {{-- 章ヘッダー --}}
    <div class="bg-white rounded-xl border border-stone-200 p-6 mb-6">
        <div class="flex items-start justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 text-xs">
                    <span class="rounded-full bg-stone-100 text-stone-600 px-2.5 py-1">{{ $chapter->statusLabel() }}</span>
                    <span class="text-stone-400">{{ number_format($chapter->word_count) }} 字</span>
                </div>
                <h1 class="text-2xl font-bold mt-2">{{ $chapter->title }}</h1>
            </div>
            <a href="{{ route('chapters.edit', $chapter) }}"
               class="inline-flex items-center gap-1.5 shrink-0 rounded-lg bg-amber-600 px-4 py-2 text-sm text-white font-medium hover:bg-amber-700">
                <x-icon name="pencil" class="w-4 h-4" />原稿を書く・編集する</a>
        </div>

        @if ($chapter->summary)
            <div class="mt-5 pt-5 border-t border-stone-100">
                <p class="text-xs text-stone-400 mb-1">概要</p>
                <p class="text-sm whitespace-pre-line">{{ $chapter->summary }}</p>
            </div>
        @endif
    </div>

    {{-- 原稿本文 --}}
    <section class="bg-white rounded-xl border border-stone-200 p-6 mb-6">
        <h2 class="text-lg font-bold mb-4">原稿</h2>
        @if (filled($chapter->body))
            <div class="text-sm leading-relaxed whitespace-pre-wrap font-mono text-stone-700">{{ $chapter->body }}</div>
        @else
            <p class="text-sm text-stone-500 text-center py-6">まだ本文がありません。「原稿を書く・編集する」から書き始めましょう。</p>
        @endif
    </section>

    {{-- 保存履歴（版） --}}
    <section class="bg-white rounded-xl border border-stone-200 p-6">
        <h2 class="text-lg font-bold mb-4">保存履歴</h2>
        <p class="text-xs text-stone-400 mb-4">保存するたびに、そのときの原稿が履歴として残ります。</p>

        @forelse ($chapter->versions as $version)
            <details class="rounded-lg border border-stone-100 mb-2">
                <summary class="flex items-center justify-between gap-4 px-4 py-3 cursor-pointer hover:bg-stone-50">
                    <div class="min-w-0">
                        <span class="text-sm font-medium text-stone-700">{{ $version->note }}</span>
                        <span class="text-xs text-stone-400 ml-2">{{ number_format($version->word_count) }} 字</span>
                    </div>
                    <span class="shrink-0 text-xs text-stone-400">{{ $version->created_at->format('Y/m/d H:i') }}</span>
                </summary>
                <div class="px-4 pb-4 pt-1 border-t border-stone-100">
                    <div class="text-sm leading-relaxed whitespace-pre-wrap font-mono text-stone-600">{{ $version->body ?: '（本文なし）' }}</div>
                </div>
            </details>
        @empty
            <p class="text-sm text-stone-500">保存履歴はまだありません。</p>
        @endforelse
    </section>
@endsection
