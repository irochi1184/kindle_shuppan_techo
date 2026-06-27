@extends('layouts.app')

@section('title', '新しい本をつくる — Kindle出版手帳')

@section('content')
    <div class="mb-6">
        <a href="{{ route('books.index') }}" class="inline-flex items-center gap-1 text-sm text-stone-500 hover:text-amber-700">
            <x-icon name="arrow-left" class="w-4 h-4" />本の一覧に戻る
        </a>
        <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-stone-900 mt-3">新しい本をつくる</h1>
        <p class="text-sm text-stone-500 mt-1.5">タイトルだけでも始められます。あとからいつでも編集できます。</p>
    </div>

    <form action="{{ route('books.store') }}" method="POST" enctype="multipart/form-data"
          class="bg-white rounded-2xl border border-stone-200/80 shadow-card p-6 sm:p-7">
        @csrf
        @include('books._form')

        <div class="flex items-center gap-4 mt-8 pt-5 border-t border-stone-100">
            <button type="submit"
                    class="inline-flex items-center justify-center rounded-lg bg-brand-600 px-5 py-2.5 text-white font-medium shadow-sm hover:bg-brand-700 active:scale-[0.98] transition">
                この内容で本をつくる
            </button>
            <a href="{{ route('books.index') }}" class="text-sm text-stone-500 hover:text-stone-700 transition">キャンセル</a>
        </div>
    </form>
@endsection
