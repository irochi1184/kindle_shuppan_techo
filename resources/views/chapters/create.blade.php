@extends('layouts.app')

@section('title', '章を追加 — ' . $book->title)

@section('content')
    <div class="mb-6">
        <a href="{{ route('books.show', $book) }}" class="inline-flex items-center gap-1 text-sm text-stone-500 hover:text-amber-700">
            <x-icon name="arrow-left" class="w-4 h-4" />{{ $book->title }} に戻る
        </a>
        <h1 class="text-2xl font-bold mt-2">章を追加</h1>
    </div>

    <form action="{{ route('books.chapters.store', $book) }}" method="POST"
          class="bg-white rounded-xl border border-stone-200 p-6">
        @csrf
        @include('chapters._form')

        <div class="flex items-center gap-3 mt-8 pt-5 border-t border-stone-100">
            <button type="submit"
                    class="rounded-lg bg-amber-600 px-5 py-2 text-white font-medium hover:bg-amber-700">
                章を保存する
            </button>
            <a href="{{ route('books.show', $book) }}" class="text-sm text-stone-500 hover:text-stone-700">キャンセル</a>
        </div>
    </form>
@endsection
