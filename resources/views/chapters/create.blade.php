@extends('layouts.app')

@section('title', '章を追加 — ' . $book->title)

@section('content')
    <div class="mb-6">
        <a href="{{ route('books.show', $book) }}" class="inline-flex items-center gap-1 text-sm text-stone-500 hover:text-brand-700 transition">
            <x-icon name="arrow-left" class="w-4 h-4" />{{ $book->title }} に戻る
        </a>
        <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-stone-900 mt-3">章を追加</h1>
    </div>

    <form action="{{ route('books.chapters.store', $book) }}" method="POST"
          class="bg-white rounded-2xl border border-stone-200/80 shadow-card p-6 sm:p-7">
        @csrf
        @include('chapters._form')

        <div class="flex items-center gap-4 mt-8 pt-5 border-t border-stone-100">
            <button type="submit"
                    class="inline-flex items-center justify-center rounded-lg bg-brand-600 px-5 py-2.5 text-white font-medium shadow-sm hover:bg-brand-700 active:scale-[0.98] transition">
                章を保存する
            </button>
            <a href="{{ route('books.show', $book) }}" class="text-sm text-stone-500 hover:text-stone-700 transition">キャンセル</a>
        </div>
    </form>
@endsection
