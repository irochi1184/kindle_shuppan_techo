@extends('layouts.app')

@section('title', 'ゴミ箱 — Kindle出版手帳')

@section('content')
    <div class="mb-5">
        <a href="{{ route('books.index') }}" class="inline-flex items-center gap-1 text-sm text-stone-500 hover:text-brand-700 transition">
            <x-icon name="arrow-left" class="w-4 h-4" />本の一覧に戻る
        </a>
    </div>

    <div class="mb-7">
        <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-stone-900">ゴミ箱</h1>
        <p class="text-sm text-stone-500 mt-1.5">削除した本はここに残ります。復元するか、完全に削除できます。</p>
    </div>

    @forelse ($books as $book)
        <div class="flex items-center justify-between gap-4 bg-white rounded-2xl border border-stone-200/80 shadow-card p-5 mb-3">
            <div class="min-w-0">
                <h2 class="text-base font-semibold text-stone-800 truncate">{{ $book->title }}</h2>
                <p class="text-xs text-stone-400 mt-1">
                    章 {{ $book->chapters_count }} 本 ・ {{ $book->deleted_at?->format('Y/m/d H:i') }} に削除
                </p>
            </div>
            <div class="flex items-center gap-2 shrink-0">
                <form action="{{ route('books.restore', $book->id) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="inline-flex items-center gap-1.5 rounded-lg border border-stone-300 bg-white px-3.5 py-2 text-sm font-medium text-stone-700 hover:bg-stone-50 transition">
                        <x-icon name="restore" class="w-4 h-4" />復元
                    </button>
                </form>
                <form action="{{ route('books.force-delete', $book->id) }}" method="POST"
                      onsubmit="return confirm('「{{ $book->title }}」を完全に削除します。章・保存履歴も消え、元に戻せません。よろしいですか？');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="inline-flex items-center gap-1.5 rounded-lg px-3.5 py-2 text-sm font-medium text-red-600 hover:bg-red-50 transition">
                        <x-icon name="trash" class="w-4 h-4" />完全に削除
                    </button>
                </form>
            </div>
        </div>
    @empty
        <div class="bg-white rounded-2xl border border-dashed border-stone-300 p-14 text-center shadow-card">
            <span class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-stone-100 text-stone-400 mb-4">
                <x-icon name="trash" class="w-7 h-7" />
            </span>
            <p class="text-stone-500 text-sm">ゴミ箱は空です。</p>
        </div>
    @endforelse
@endsection
