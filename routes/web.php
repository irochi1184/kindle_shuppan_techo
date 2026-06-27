<?php

use App\Http\Controllers\BookController;
use App\Http\Controllers\ChapterController;
use App\Http\Controllers\KdpMetadataController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublishingTaskController;
use Illuminate\Support\Facades\Route;

// トップは本の一覧へ（未ログインは auth ミドルウェアでログインへ誘導）
Route::get('/', fn () => redirect()->route('books.index'));

// Breeze のログイン後リダイレクト先（dashboard）は本の一覧へ寄せる
Route::get('/dashboard', fn () => redirect()->route('books.index'))
    ->middleware('auth')->name('dashboard');

// ===== ログインが必要なアプリ本体 =====
Route::middleware('auth')->group(function () {
    // ゴミ箱（論理削除した本の復元・完全削除）。モデル束縛より前に定義する
    Route::get('books/trash', [BookController::class, 'trash'])->name('books.trash');
    Route::patch('books/{id}/restore', [BookController::class, 'restore'])->name('books.restore');
    Route::delete('books/{id}/force', [BookController::class, 'forceDelete'])->name('books.force-delete');

    Route::resource('books', BookController::class);

    // 章の並べ替え（#14）。shallow リソースより前に定義する
    Route::patch('books/{book}/chapters/reorder', [ChapterController::class, 'reorder'])->name('books.chapters.reorder');
    Route::resource('books.chapters', ChapterController::class)->shallow();

    // 章の自動保存（#11）と保存履歴からの復元（#13）
    Route::patch('chapters/{chapter}/autosave', [ChapterController::class, 'autosave'])->name('chapters.autosave');
    Route::post('chapters/{chapter}/versions/{version}/restore', [ChapterController::class, 'restoreVersion'])->name('chapters.versions.restore');

    // Markdown（原稿）の書き出し
    Route::get('books/{book}/export/markdown', [BookController::class, 'exportMarkdown'])->name('books.export.markdown');

    // 出版情報（KDP）の保存
    Route::put('books/{book}/metadata', [KdpMetadataController::class, 'update'])->name('books.metadata.update');

    // 出版前チェックリストのチェック切り替え
    Route::patch('tasks/{task}/toggle', [PublishingTaskController::class, 'toggle'])->name('tasks.toggle');

    // アカウント設定（Breeze）
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
