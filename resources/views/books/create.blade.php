@extends('layouts.app')

@section('title', '新しい本をつくる — Kindle出版手帳')

@section('content')
    <div class="mb-6">
        <a href="{{ route('books.index') }}" class="text-sm text-stone-500 hover:text-amber-700">← 本の一覧に戻る</a>
        <h1 class="text-2xl font-bold mt-2">新しい本をつくる</h1>
        <p class="text-sm text-stone-500 mt-1">タイトルだけでも始められます。あとからいつでも編集できます。</p>
    </div>

    <form action="{{ route('books.store') }}" method="POST"
          class="bg-white rounded-xl border border-stone-200 p-6">
        @csrf
        @include('books._form')

        <div class="flex items-center gap-3 mt-8 pt-5 border-t border-stone-100">
            <button type="submit"
                    class="rounded-lg bg-amber-600 px-5 py-2 text-white font-medium hover:bg-amber-700">
                この内容で本をつくる
            </button>
            <a href="{{ route('books.index') }}" class="text-sm text-stone-500 hover:text-stone-700">キャンセル</a>
        </div>
    </form>
@endsection
