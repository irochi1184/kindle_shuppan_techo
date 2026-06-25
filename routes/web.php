<?php

use App\Http\Controllers\BookController;
use App\Http\Controllers\ChapterController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect()->route('books.index'));

Route::resource('books', BookController::class);
Route::resource('books.chapters', ChapterController::class)->shallow();
Route::get('/books/{book}/export/markdown', [BookController::class, 'exportMarkdown'])->name('books.export.markdown');
