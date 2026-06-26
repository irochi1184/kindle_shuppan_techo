@extends('layouts.app')

@section('title', '本の一覧 — Kindle出版手帳')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold">本の一覧</h1>
            <p class="text-sm text-stone-500 mt-1">執筆中・出版準備中の本を管理します。</p>
        </div>
        <a href="{{ route('books.create') }}"
           class="inline-flex items-center gap-1.5 rounded-lg bg-amber-600 px-4 py-2 text-white font-medium hover:bg-amber-700">
            <x-icon name="plus" class="w-4 h-4" />
            新しい本をつくる
        </a>
    </div>

    @forelse ($books as $book)
        <a href="{{ route('books.show', $book) }}"
           class="block bg-white rounded-xl border border-stone-200 p-5 mb-3 hover:border-amber-300 hover:shadow-sm transition">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <h2 class="text-lg font-semibold text-stone-800">{{ $book->title }}</h2>
                    @if ($book->subtitle)
                        <p class="text-sm text-stone-500 mt-0.5">{{ $book->subtitle }}</p>
                    @endif
                    <p class="text-xs text-stone-400 mt-2">
                        @if ($book->author_name)著者：{{ $book->author_name }} ・ @endif
                        章 {{ $book->chapters->count() }} 本
                    </p>
                </div>
                <span class="shrink-0 rounded-full bg-amber-50 text-amber-700 text-xs font-medium px-3 py-1">
                    {{ $book->statusLabel() }}
                </span>
            </div>
        </a>
    @empty
        <div class="bg-white rounded-xl border border-dashed border-stone-300 p-12 text-center">
            <span class="inline-flex items-center justify-center w-14 h-14 rounded-full bg-stone-100 text-stone-400 mb-4">
                <x-icon name="books" class="w-7 h-7" />
            </span>
            <p class="text-stone-600 mb-5">まだ本がありません。最初の1冊をつくってみましょう。</p>
            <a href="{{ route('books.create') }}"
               class="inline-flex items-center gap-1.5 rounded-lg bg-amber-600 px-4 py-2 text-white font-medium hover:bg-amber-700">
                <x-icon name="plus" class="w-4 h-4" />
                新しい本をつくる
            </a>
        </div>
    @endforelse
@endsection
