<!DOCTYPE html>
<html lang="ja" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Kindle出版手帳')</title>

    {{-- 開発初期はビルド不要の Tailwind CDN を利用（後で Vite ビルドへ移行可能） --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', '"Noto Sans JP"', 'system-ui', 'sans-serif'],
                    },
                    colors: {
                        // ブランドアクセント（温かみのあるアンバー）
                        brand: {
                            50: '#fdf8f3', 100: '#faecdd', 200: '#f3d4b3',
                            300: '#e9b483', 400: '#dd8f4f', 500: '#cf7430',
                            600: '#b85d24', 700: '#984820', 800: '#7b3b20',
                            900: '#65321d', 950: '#37180d',
                        },
                    },
                    boxShadow: {
                        card: '0 1px 2px 0 rgb(28 25 23 / 0.04), 0 1px 3px 0 rgb(28 25 23 / 0.06)',
                        lift: '0 8px 24px -8px rgb(28 25 23 / 0.12), 0 2px 6px -2px rgb(28 25 23 / 0.08)',
                    },
                },
            },
        }
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Noto+Sans+JP:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { -webkit-font-smoothing: antialiased; }
        /* スクロール時に下に隠れないようヘッダー分のオフセット */
        [id] { scroll-margin-top: 5rem; }
    </style>
</head>
<body class="h-full bg-stone-100 text-stone-800 font-sans antialiased">
    {{-- 背景のやわらかいグラデーション --}}
    <div class="fixed inset-0 -z-10 bg-gradient-to-b from-brand-50 via-stone-50 to-stone-100"></div>

    <header class="sticky top-0 z-30 border-b border-stone-200/70 bg-white/80 backdrop-blur-md">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 h-16 flex items-center justify-between">
            <a href="{{ route('books.index') }}" class="flex items-center gap-2.5 group">
                <span class="flex items-center justify-center w-9 h-9 rounded-xl bg-stone-900 text-brand-100 shadow-sm transition group-hover:scale-105">
                    <x-icon name="book-open" class="w-5 h-5" />
                </span>
                <span class="text-[15px] font-bold tracking-tight text-stone-900">Kindle出版手帳</span>
            </a>
            <nav class="flex items-center gap-1 sm:gap-2 text-sm">
                @auth
                    <a href="{{ route('books.index') }}"
                       class="hidden sm:inline-flex items-center rounded-lg px-3 py-2 font-medium text-stone-600 hover:bg-stone-100 hover:text-stone-900 transition">
                        本の一覧
                    </a>
                    <a href="{{ route('books.create') }}"
                       class="inline-flex items-center gap-1.5 rounded-lg bg-brand-600 px-3.5 py-2 font-medium text-white shadow-sm hover:bg-brand-700 active:scale-[0.98] transition">
                        <x-icon name="plus" class="w-4 h-4" />
                        新しい本
                    </a>

                    {{-- アカウントメニュー（JS不要の details ドロップダウン） --}}
                    <details class="relative group ml-1">
                        <summary class="list-none flex items-center gap-1.5 cursor-pointer rounded-lg px-2 py-1.5 hover:bg-stone-100 transition">
                            <span class="flex items-center justify-center w-8 h-8 rounded-full bg-stone-900 text-white text-xs font-semibold">
                                {{ mb_substr(auth()->user()->name, 0, 1) }}
                            </span>
                        </summary>
                        <div class="absolute right-0 mt-2 w-52 rounded-xl border border-stone-200 bg-white p-1.5 shadow-lift z-40">
                            <div class="px-3 py-2 border-b border-stone-100 mb-1">
                                <p class="text-sm font-semibold text-stone-900 truncate">{{ auth()->user()->name }}</p>
                                <p class="text-xs text-stone-400 truncate">{{ auth()->user()->email }}</p>
                            </div>
                            <a href="{{ route('profile.edit') }}" class="flex items-center gap-2.5 rounded-lg px-3 py-2 text-sm text-stone-700 hover:bg-stone-50 transition">
                                <x-icon name="user" class="w-4 h-4 text-stone-400" />アカウント設定
                            </a>
                            <a href="{{ route('books.trash') }}" class="flex items-center gap-2.5 rounded-lg px-3 py-2 text-sm text-stone-700 hover:bg-stone-50 transition">
                                <x-icon name="trash" class="w-4 h-4 text-stone-400" />ゴミ箱
                            </a>
                            <form action="{{ route('logout') }}" method="POST" class="border-t border-stone-100 mt-1 pt-1">
                                @csrf
                                <button type="submit" class="w-full flex items-center gap-2.5 rounded-lg px-3 py-2 text-sm text-stone-700 hover:bg-stone-50 transition">
                                    <x-icon name="logout" class="w-4 h-4 text-stone-400" />ログアウト
                                </button>
                            </form>
                        </div>
                    </details>
                @else
                    <a href="{{ route('login') }}"
                       class="inline-flex items-center rounded-lg px-3 py-2 font-medium text-stone-600 hover:bg-stone-100 hover:text-stone-900 transition">
                        ログイン
                    </a>
                    <a href="{{ route('register') }}"
                       class="inline-flex items-center gap-1.5 rounded-lg bg-brand-600 px-3.5 py-2 font-medium text-white shadow-sm hover:bg-brand-700 active:scale-[0.98] transition">
                        新規登録
                    </a>
                @endauth
            </nav>
        </div>
    </header>

    <main class="max-w-5xl mx-auto px-4 sm:px-6 py-8 sm:py-10">
        @if (session('status'))
            <div class="mb-6 flex items-start gap-3 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800 shadow-card">
                <x-icon name="check" class="w-5 h-5 shrink-0 mt-0.5 text-emerald-600" />
                <span class="font-medium">{{ session('status') }}</span>
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-6 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700 shadow-card">
                <p class="font-semibold mb-1">入力内容を確認してください。</p>
                <ul class="list-disc list-inside space-y-0.5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </main>

    <footer class="max-w-5xl mx-auto px-4 sm:px-6 py-10 text-center text-xs text-stone-400">
        Kindle出版手帳 — 企画から出版準備まで迷わず進める作業台
    </footer>
</body>
</html>
