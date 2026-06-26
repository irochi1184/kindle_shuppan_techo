<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Kindle出版手帳')</title>

    {{-- 開発初期はビルド不要の Tailwind CDN を利用（後で Vite ビルドへ移行可能） --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: "Hiragino Sans", "Noto Sans JP", system-ui, sans-serif; }
    </style>
</head>
<body class="min-h-screen bg-stone-50 text-stone-800">
    <header class="bg-white border-b border-stone-200">
        <div class="max-w-5xl mx-auto px-4 py-4 flex items-center justify-between">
            <a href="{{ route('books.index') }}" class="flex items-center gap-2.5 text-lg font-bold tracking-tight text-stone-800">
                <span class="flex items-center justify-center w-9 h-9 rounded-lg bg-stone-900 text-amber-50">
                    <x-icon name="book-open" class="w-5 h-5" />
                </span>
                <span>Kindle出版手帳</span>
            </a>
            <nav class="flex items-center gap-4 text-sm">
                <a href="{{ route('books.index') }}" class="text-stone-600 hover:text-amber-700">本の一覧</a>
                <a href="{{ route('books.create') }}"
                   class="inline-flex items-center gap-1.5 rounded-lg bg-amber-600 px-3.5 py-1.5 text-white font-medium hover:bg-amber-700">
                    <x-icon name="plus" class="w-4 h-4" />
                    新しい本
                </a>
            </nav>
        </div>
    </header>

    <main class="max-w-5xl mx-auto px-4 py-8">
        @if (session('status'))
            <div class="mb-6 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
                {{ session('status') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-6 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                <p class="font-medium mb-1">入力内容を確認してください。</p>
                <ul class="list-disc list-inside space-y-0.5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </main>

    <footer class="max-w-5xl mx-auto px-4 py-8 text-center text-xs text-stone-400">
        Kindle出版手帳 — 企画から出版準備まで迷わず進める作業台
    </footer>
</body>
</html>
