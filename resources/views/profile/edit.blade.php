@extends('layouts.app')

@section('title', 'アカウント設定 — Kindle出版手帳')

@section('content')
    <div class="mb-5">
        <a href="{{ route('books.index') }}" class="inline-flex items-center gap-1 text-sm text-stone-500 hover:text-brand-700 transition">
            <x-icon name="arrow-left" class="w-4 h-4" />本の一覧に戻る
        </a>
    </div>

    <div class="mb-7">
        <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-stone-900">アカウント設定</h1>
        <p class="text-sm text-stone-500 mt-1.5">プロフィールやパスワードの変更ができます。</p>
    </div>

    <div class="space-y-6 max-w-2xl">
        <div class="bg-white rounded-2xl border border-stone-200/80 shadow-card p-6 sm:p-7">
            @include('profile.partials.update-profile-information-form')
        </div>

        <div class="bg-white rounded-2xl border border-stone-200/80 shadow-card p-6 sm:p-7">
            @include('profile.partials.update-password-form')
        </div>

        <div class="bg-white rounded-2xl border border-stone-200/80 shadow-card p-6 sm:p-7">
            @include('profile.partials.delete-user-form')
        </div>
    </div>
@endsection
