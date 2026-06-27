@extends('layouts.app')

@section('title', '本の一覧 — Kindle出版手帳')

@section('content')
    <div class="flex items-end justify-between gap-4 mb-7">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-stone-900">本の一覧</h1>
            <p class="text-sm text-stone-500 mt-1.5">執筆中・出版準備中の本を管理します。</p>
        </div>
        <a href="{{ route('books.create') }}"
           class="hidden sm:inline-flex shrink-0 items-center gap-1.5 rounded-lg bg-brand-600 px-4 py-2.5 text-sm text-white font-medium shadow-sm hover:bg-brand-700 active:scale-[0.98] transition">
            <x-icon name="plus" class="w-4 h-4" />
            新しい本をつくる
        </a>
    </div>

    @forelse ($books as $book)
        @if ($loop->first)
            <div class="grid gap-3 sm:grid-cols-2">
        @endif
        <a href="{{ route('books.show', $book) }}"
           class="group flex flex-col bg-white rounded-2xl border border-stone-200/80 p-5 shadow-card hover:shadow-lift hover:border-brand-200 hover:-translate-y-0.5 transition duration-200">
            <div class="flex items-start justify-between gap-3">
                <h2 class="text-base font-semibold text-stone-900 leading-snug group-hover:text-brand-700 transition">{{ $book->title }}</h2>
                <x-badge :color="$book->statusColor()" class="shrink-0">{{ $book->statusLabel() }}</x-badge>
            </div>
            @if ($book->subtitle)
                <p class="text-sm text-stone-500 mt-1 line-clamp-1">{{ $book->subtitle }}</p>
            @endif
            <div class="flex items-center gap-3 mt-4 pt-4 border-t border-stone-100 text-xs text-stone-500">
                @if ($book->author_name)
                    <span class="truncate">著者：{{ $book->author_name }}</span>
                    <span class="text-stone-300">·</span>
                @endif
                <span class="inline-flex items-center gap-1 shrink-0">
                    <x-icon name="document" class="w-3.5 h-3.5 text-stone-400" />章 {{ $book->chapters->count() }} 本
                </span>
                <x-icon name="arrow-left" class="w-4 h-4 ml-auto rotate-180 text-stone-300 group-hover:text-brand-500 group-hover:translate-x-0.5 transition" />
            </div>
        </a>
        @if ($loop->last)
            </div>
        @endif
    @empty
        <div class="bg-white rounded-2xl border border-dashed border-stone-300 p-14 text-center shadow-card">
            <span class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-brand-50 text-brand-500 mb-5">
                <x-icon name="books" class="w-8 h-8" />
            </span>
            <h2 class="text-lg font-semibold text-stone-800">最初の1冊をつくりましょう</h2>
            <p class="text-stone-500 mt-1.5 mb-6 text-sm">タイトルだけでも始められます。あとからいつでも編集できます。</p>
            <a href="{{ route('books.create') }}"
               class="inline-flex items-center gap-1.5 rounded-lg bg-brand-600 px-5 py-2.5 text-sm text-white font-medium shadow-sm hover:bg-brand-700 active:scale-[0.98] transition">
                <x-icon name="plus" class="w-4 h-4" />
                新しい本をつくる
            </a>
        </div>
    @endforelse
@endsection
