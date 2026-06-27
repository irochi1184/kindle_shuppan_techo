@php($inputClass = 'w-full rounded-lg border border-stone-300 bg-white px-3 py-2 text-sm transition focus:border-brand-500 focus:ring-2 focus:ring-brand-500/25 outline-none placeholder:text-stone-400')

<section>
    <header>
        <h2 class="text-base font-semibold text-stone-900">プロフィール</h2>
        <p class="mt-1 text-sm text-stone-500">お名前とメールアドレスを変更できます。</p>
    </header>

    <form method="post" action="{{ route('profile.update') }}" class="mt-5 space-y-4">
        @csrf
        @method('patch')

        <div>
            <label for="name" class="block text-sm font-medium text-stone-700 mb-1.5">お名前</label>
            <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name" class="{{ $inputClass }}">
            @error('name')<p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>@enderror
        </div>

        <div>
            <label for="email" class="block text-sm font-medium text-stone-700 mb-1.5">メールアドレス</label>
            <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" required autocomplete="username" class="{{ $inputClass }}">
            @error('email')<p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>@enderror
        </div>

        <button type="submit" class="inline-flex items-center justify-center rounded-lg bg-brand-600 px-4 py-2.5 text-white text-sm font-medium shadow-sm hover:bg-brand-700 active:scale-[0.98] transition">保存する</button>
    </form>
</section>
