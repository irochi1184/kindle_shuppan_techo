@extends('layouts.app')

@section('title', '章を編集 — ' . $chapter->title)

@section('content')
    <div class="mb-6">
        <a href="{{ route('chapters.show', $chapter) }}" class="inline-flex items-center gap-1 text-sm text-stone-500 hover:text-brand-700 transition">
            <x-icon name="arrow-left" class="w-4 h-4" />章の詳細に戻る
        </a>
        <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-stone-900 mt-3">章を編集</h1>
        <p class="text-sm text-stone-500 mt-1.5">{{ $chapter->book->title }}</p>
    </div>

    <form action="{{ route('chapters.update', $chapter) }}" method="POST"
          class="bg-white rounded-2xl border border-stone-200/80 shadow-card p-6 sm:p-7">
        @csrf
        @method('PUT')
        @include('chapters._form', ['autosaveUrl' => route('chapters.autosave', $chapter)])

        <div class="mt-5">
            <label class="block text-sm font-medium text-stone-700 mb-1.5">保存メモ（任意）</label>
            <input type="text" name="version_note" value="{{ old('version_note') }}"
                   class="w-full rounded-lg border border-stone-300 bg-white px-3 py-2 text-sm transition focus:border-brand-500 focus:ring-2 focus:ring-brand-500/25 outline-none placeholder:text-stone-400"
                   placeholder="例：導入を書き直した／誤字を修正">
            <p class="text-xs text-stone-400 mt-1.5">このメモは保存履歴に残ります。あとで見返すときの目印になります。</p>
        </div>

        <div class="flex items-center gap-4 mt-8 pt-5 border-t border-stone-100">
            <button type="submit"
                    class="inline-flex items-center justify-center rounded-lg bg-brand-600 px-5 py-2.5 text-white font-medium shadow-sm hover:bg-brand-700 active:scale-[0.98] transition">
                保存する
            </button>
            <a href="{{ route('chapters.show', $chapter) }}" class="text-sm text-stone-500 hover:text-stone-700 transition">キャンセル</a>
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
