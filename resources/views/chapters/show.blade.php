@extends('layouts.app')

@section('title', $chapter->title . ' — ' . $chapter->book->title)

@section('content')
    <div class="mb-5">
        <a href="{{ route('books.show', $chapter->book) }}" class="inline-flex items-center gap-1 text-sm text-stone-500 hover:text-brand-700 transition">
            <x-icon name="arrow-left" class="w-4 h-4" />{{ $chapter->book->title }} に戻る
        </a>
    </div>

    {{-- 章ヘッダー --}}
    <div class="bg-white rounded-2xl border border-stone-200/80 shadow-card p-6 sm:p-7 mb-6">
        <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-4">
            <div class="min-w-0">
                <div class="flex items-center gap-2.5">
                    <x-badge :color="$chapter->statusColor()">{{ $chapter->statusLabel() }}</x-badge>
                    <span class="text-xs text-stone-400 tabular-nums">{{ number_format($chapter->word_count) }} 字</span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-stone-900 mt-3">{{ $chapter->title }}</h1>
            </div>
            <a href="{{ route('chapters.edit', $chapter) }}"
               class="inline-flex items-center justify-center gap-1.5 shrink-0 rounded-lg bg-brand-600 px-4 py-2.5 text-sm text-white font-medium shadow-sm hover:bg-brand-700 active:scale-[0.98] transition">
                <x-icon name="pencil" class="w-4 h-4" />原稿を書く・編集する</a>
        </div>

        @if ($chapter->summary)
            <div class="mt-5 pt-5 border-t border-stone-100">
                <p class="text-xs font-medium text-stone-400 mb-1">概要</p>
                <p class="text-sm text-stone-700 whitespace-pre-line">{{ $chapter->summary }}</p>
            </div>
        @endif
    </div>

    {{-- 原稿本文 --}}
    <section class="bg-white rounded-2xl border border-stone-200/80 shadow-card p-6 sm:p-7 mb-6">
        <h2 class="text-base font-semibold text-stone-900 mb-4">原稿</h2>
        @if (filled($chapter->body))
            <div class="rounded-xl bg-stone-50/70 border border-stone-100 px-5 py-4 text-[15px] leading-loose whitespace-pre-wrap text-stone-700">{{ $chapter->body }}</div>
        @else
            <div class="rounded-xl border border-dashed border-stone-200 py-10 text-center">
                <p class="text-sm text-stone-500">まだ本文がありません。「原稿を書く・編集する」から書き始めましょう。</p>
            </div>
        @endif
    </section>

    {{-- 保存履歴（版） --}}
    <section class="bg-white rounded-2xl border border-stone-200/80 shadow-card p-6 sm:p-7">
        <div class="flex items-center gap-2 mb-1">
            <x-icon name="clock" class="w-4 h-4 text-stone-400" />
            <h2 class="text-base font-semibold text-stone-900">保存履歴</h2>
        </div>
        <p class="text-xs text-stone-400 mb-4">保存するたびに、そのときの原稿が履歴として残ります。</p>

        <div class="space-y-2">
            @forelse ($chapter->versions as $version)
                <details class="group rounded-xl border border-stone-100 overflow-hidden">
                    <summary class="flex items-center justify-between gap-4 px-4 py-3 cursor-pointer hover:bg-stone-50 transition list-none">
                        <div class="flex items-center gap-2 min-w-0">
                            <x-icon name="arrow-left" class="w-3.5 h-3.5 -rotate-90 text-stone-400 group-open:rotate-0 transition" />
                            <span class="text-sm font-medium text-stone-700 truncate">{{ $version->note ?: '（メモなし）' }}</span>
                            <span class="text-xs text-stone-400 shrink-0 tabular-nums">{{ number_format($version->word_count) }} 字</span>
                        </div>
                        <span class="shrink-0 text-xs text-stone-400 tabular-nums">{{ $version->created_at->format('Y/m/d H:i') }}</span>
                    </summary>
                    <div class="px-4 pb-4 pt-1 border-t border-stone-100 bg-stone-50/40">
                        <div class="text-sm leading-relaxed whitespace-pre-wrap font-mono text-stone-600">{{ $version->body ?: '（本文なし）' }}</div>
                    </div>
                </details>
            @empty
                <p class="text-sm text-stone-500">保存履歴はまだありません。</p>
            @endforelse
        </div>
    </section>
@endsection
