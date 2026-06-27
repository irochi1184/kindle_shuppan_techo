@php($inputClass = 'w-full rounded-lg border border-stone-300 bg-white px-3 py-2 text-sm transition focus:border-brand-500 focus:ring-2 focus:ring-brand-500/25 outline-none placeholder:text-stone-400')

<section>
    <header>
        <h2 class="text-base font-semibold text-stone-900">パスワードの変更</h2>
        <p class="mt-1 text-sm text-stone-500">安全のため、長くて推測されにくいパスワードを設定してください。</p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-5 space-y-4">
        @csrf
        @method('put')

        <div>
            <label for="update_password_current_password" class="block text-sm font-medium text-stone-700 mb-1.5">現在のパスワード</label>
            <input id="update_password_current_password" name="current_password" type="password" autocomplete="current-password" class="{{ $inputClass }}">
            @error('current_password', 'updatePassword')<p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>@enderror
        </div>

        <div>
            <label for="update_password_password" class="block text-sm font-medium text-stone-700 mb-1.5">新しいパスワード</label>
            <input id="update_password_password" name="password" type="password" autocomplete="new-password" class="{{ $inputClass }}">
            @error('password', 'updatePassword')<p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>@enderror
        </div>

        <div>
            <label for="update_password_password_confirmation" class="block text-sm font-medium text-stone-700 mb-1.5">新しいパスワード（確認）</label>
            <input id="update_password_password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" class="{{ $inputClass }}">
            @error('password_confirmation', 'updatePassword')<p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>@enderror
        </div>

        <button type="submit" class="inline-flex items-center justify-center rounded-lg bg-brand-600 px-4 py-2.5 text-white text-sm font-medium shadow-sm hover:bg-brand-700 active:scale-[0.98] transition">保存する</button>
    </form>
</section>
