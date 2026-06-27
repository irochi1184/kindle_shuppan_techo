@php($inputClass = 'w-full rounded-lg border border-stone-300 bg-white px-3 py-2 text-sm transition focus:border-red-500 focus:ring-2 focus:ring-red-500/25 outline-none placeholder:text-stone-400')

<section>
    <header>
        <h2 class="text-base font-semibold text-red-700">アカウントの削除</h2>
        <p class="mt-1 text-sm text-stone-500">削除すると、すべての本・原稿・保存履歴が完全に消え、元に戻せません。必要なデータは事前に書き出しておいてください。</p>
    </header>

    <form method="post" action="{{ route('profile.destroy') }}" class="mt-5 space-y-4"
          onsubmit="return confirm('本当にアカウントを削除しますか？すべてのデータが完全に削除され、元に戻せません。');">
        @csrf
        @method('delete')

        <div>
            <label for="delete_password" class="block text-sm font-medium text-stone-700 mb-1.5">確認のためパスワードを入力</label>
            <input id="delete_password" name="password" type="password" placeholder="現在のパスワード" class="{{ $inputClass }} max-w-sm">
            @error('password', 'userDeletion')<p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>@enderror
        </div>

        <button type="submit" class="inline-flex items-center gap-1.5 rounded-lg bg-red-600 px-4 py-2.5 text-white text-sm font-medium shadow-sm hover:bg-red-700 active:scale-[0.98] transition">
            <x-icon name="trash" class="w-4 h-4" />アカウントを削除する
        </button>
    </form>
</section>
