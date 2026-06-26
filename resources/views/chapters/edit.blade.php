@extends('layouts.app')

@section('title', '章を編集 — ' . $chapter->title)

@section('content')
    <div class="mb-6">
        <a href="{{ route('chapters.show', $chapter) }}" class="inline-flex items-center gap-1 text-sm text-stone-500 hover:text-amber-700">
            <x-icon name="arrow-left" class="w-4 h-4" />章の詳細に戻る
        </a>
        <h1 class="text-2xl font-bold mt-2">章を編集</h1>
        <p class="text-sm text-stone-500 mt-1">{{ $chapter->book->title }}</p>
    </div>

    <form action="{{ route('chapters.update', $chapter) }}" method="POST"
          class="bg-white rounded-xl border border-stone-200 p-6">
        @csrf
        @method('PUT')
        @include('chapters._form')

        <div class="mt-5">
            <label class="block text-sm font-medium text-stone-700 mb-1">保存メモ（任意）</label>
            <input type="text" name="version_note" value="{{ old('version_note') }}"
                   class="w-full rounded-lg border border-stone-300 px-3 py-2 text-sm focus:border-amber-500 focus:ring-1 focus:ring-amber-500 outline-none"
                   placeholder="例：導入を書き直した／誤字を修正">
            <p class="text-xs text-stone-400 mt-1">このメモは保存履歴に残ります。あとで見返すときの目印になります。</p>
        </div>

        <div class="flex items-center gap-3 mt-8 pt-5 border-t border-stone-100">
            <button type="submit"
                    class="rounded-lg bg-amber-600 px-5 py-2 text-white font-medium hover:bg-amber-700">
                保存する
            </button>
            <a href="{{ route('chapters.show', $chapter) }}" class="text-sm text-stone-500 hover:text-stone-700">キャンセル</a>
        </div>
    </form>

    <form action="{{ route('chapters.destroy', $chapter) }}" method="POST" class="mt-6"
          onsubmit="return confirm('この章と保存履歴を削除します。よろしいですか？');">
        @csrf
        @method('DELETE')
        <button type="submit" class="inline-flex items-center gap-1.5 text-sm text-red-600 hover:text-red-700">
            <x-icon name="trash" class="w-4 h-4" />この章を削除する
        </button>
    </form>
@endsection
