<?php

use App\Http\Controllers\BookController;
use App\Http\Controllers\ChapterController;
use App\Http\Controllers\KdpMetadataController;
use App\Http\Controllers\PublishingTaskController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect()->route('books.index'));

Route::resource('books', BookController::class);
Route::resource('books.chapters', ChapterController::class)->shallow();

// Markdown（原稿）の書き出し
Route::get('/books/{book}/export/markdown', [BookController::class, 'exportMarkdown'])->name('books.export.markdown');

// 出版情報（KDP）の保存
Route::put('/books/{book}/metadata', [KdpMetadataController::class, 'update'])->name('books.metadata.update');

// 出版前チェックリストのチェック切り替え
Route::patch('/tasks/{task}/toggle', [PublishingTaskController::class, 'toggle'])->name('tasks.toggle');
