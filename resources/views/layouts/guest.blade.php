<!DOCTYPE html>
<html lang="ja" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Kindle出版手帳')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Noto+Sans+JP:wght@400;500;600;700&display=swap" rel="stylesheet">

    {{-- Vite でビルドした Tailwind CSS（テーマは tailwind.config.js を参照） --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
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
