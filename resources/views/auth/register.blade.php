@php($inputClass = 'w-full rounded-lg border border-stone-300 bg-white px-3 py-2 text-sm transition focus:border-brand-500 focus:ring-2 focus:ring-brand-500/25 outline-none placeholder:text-stone-400')

<x-guest-layout>
    <h1 class="text-xl font-bold tracking-tight text-stone-900">アカウントを作成</h1>
    <p class="text-sm text-stone-500 mt-1 mb-6">無料で始められます。最初の1冊をつくりましょう。</p>

    @if ($errors->any())
        <div class="mb-4 rounded-lg border border-red-200 bg-red-50 px-3 py-2 text-sm text-red-700">
            <ul class="list-disc list-inside space-y-0.5">
                @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf

        <div>
            <label for="name" class="block text-sm font-medium text-stone-700 mb-1.5">お名前（ペンネーム可）</label>
            <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" class="{{ $inputClass }}">
        </div>

        <div>
            <label for="email" class="block text-sm font-medium text-stone-700 mb-1.5">メールアドレス</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username" class="{{ $inputClass }}">
        </div>

        <div>
            <label for="password" class="block text-sm font-medium text-stone-700 mb-1.5">パスワード</label>
            <input id="password" type="password" name="password" required autocomplete="new-password" class="{{ $inputClass }}">
        </div>

        <div>
            <label for="password_confirmation" class="block text-sm font-medium text-stone-700 mb-1.5">パスワード（確認）</label>
            <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" class="{{ $inputClass }}">
        </div>

        <button type="submit" class="w-full inline-flex items-center justify-center rounded-lg bg-brand-600 px-4 py-2.5 text-white text-sm font-medium shadow-sm hover:bg-brand-700 active:scale-[0.98] transition">
            アカウントを作成
        </button>
    </form>

    <p class="mt-6 text-center text-sm text-stone-500">
        すでにアカウントをお持ちですか？
        <a href="{{ route('login') }}" class="font-medium text-brand-700 hover:text-brand-800 transition">ログイン</a>
    </p>
</x-guest-layout>
