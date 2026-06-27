<!DOCTYPE html>
<html lang="ja" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Kindle出版手帳')</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', '"Noto Sans JP"', 'system-ui', 'sans-serif'] },
                    colors: {
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
    <style>body { -webkit-font-smoothing: antialiased; }</style>
</head>
<body class="h-full bg-stone-100 text-stone-800 font-sans antialiased">
    <div class="fixed inset-0 -z-10 bg-gradient-to-b from-brand-50 via-stone-50 to-stone-100"></div>

    <div class="min-h-full flex flex-col items-center justify-center px-4 py-12">
        <a href="{{ url('/') }}" class="flex items-center gap-2.5 mb-8">
            <span class="flex items-center justify-center w-10 h-10 rounded-xl bg-stone-900 text-brand-100 shadow-sm">
                <x-icon name="book-open" class="w-5 h-5" />
            </span>
            <span class="text-lg font-bold tracking-tight text-stone-900">Kindle出版手帳</span>
        </a>

        <div class="w-full max-w-md bg-white rounded-2xl border border-stone-200/80 shadow-card p-7 sm:p-8">
            {{ $slot }}
        </div>

        <p class="mt-8 text-center text-xs text-stone-400">企画から出版準備まで迷わず進める作業台</p>
    </div>
</body>
</html>
