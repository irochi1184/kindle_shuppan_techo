@php($inputClass = 'w-full rounded-lg border border-stone-300 bg-white px-3 py-2 text-sm transition focus:border-brand-500 focus:ring-2 focus:ring-brand-500/25 outline-none placeholder:text-stone-400')

<x-guest-layout>
    <h1 class="text-xl font-bold tracking-tight text-stone-900">おかえりなさい</h1>
    <p class="text-sm text-stone-500 mt-1 mb-6">ログインして執筆を続けましょう。</p>

    @if (session('status'))
        <div class="mb-4 rounded-lg border border-emerald-200 bg-emerald-50 px-3 py-2 text-sm text-emerald-700">
            {{ session('status') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-4 rounded-lg border border-red-200 bg-red-50 px-3 py-2 text-sm text-red-700">
            <ul class="list-disc list-inside space-y-0.5">
                @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf

        <div>
            <label for="email" class="block text-sm font-medium text-stone-700 mb-1.5">メールアドレス</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" class="{{ $inputClass }}">
        </div>

        <div>
            <label for="password" class="block text-sm font-medium text-stone-700 mb-1.5">パスワード</label>
            <input id="password" type="password" name="password" required autocomplete="current-password" class="{{ $inputClass }}">
        </div>

        <div class="flex items-center justify-between">
            <label for="remember_me" class="inline-flex items-center gap-2 text-sm text-stone-600">
                <input id="remember_me" type="checkbox" name="remember" class="rounded border-stone-300 text-brand-600 focus:ring-brand-500/30">
                ログイン状態を保つ
            </label>
            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="text-sm text-stone-500 hover:text-brand-700 transition">パスワードをお忘れですか？</a>
            @endif
        </div>

        <button type="submit" class="w-full inline-flex items-center justify-center rounded-lg bg-brand-600 px-4 py-2.5 text-white text-sm font-medium shadow-sm hover:bg-brand-700 active:scale-[0.98] transition">
            ログイン
        </button>
    </form>

    <p class="mt-6 text-center text-sm text-stone-500">
        アカウントをお持ちでないですか？
        <a href="{{ route('register') }}" class="font-medium text-brand-700 hover:text-brand-800 transition">新規登録</a>
    </p>
</x-guest-layout>
