<?php

namespace App\Http\Controllers;

use App\Http\Requests\BookRequest;
use App\Models\Book;
use App\Models\ChapterVersion;
use App\Services\EpubBuilder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BookController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        // ログインユーザー自身の本だけを表示
        $books = $user->books()
            ->withCount('chapters')
            ->latest()
            ->get();

        return view('books.index', [
            'books' => $books,
            'activity' => $this->writingActivity($user->id),
        ]);
    }

    /**
     * 執筆の記録（#15）。直近14日間の「保存した日」と連続執筆日数を返す。
     *
     * @return array{days: array<int, array{date: \Illuminate\Support\Carbon, active: bool}>, streak: int}
     */
    private function writingActivity(int $userId): array
    {
        // 直近30日に保存された版の「日付」の集合を作る
        $activeDates = ChapterVersion::query()
            ->whereHas('chapter.book', fn ($q) => $q->where('user_id', $userId))
            ->where('created_at', '>=', now()->subDays(30)->startOfDay())
            ->pluck('created_at')
            ->map(fn ($d) => $d->toDateString())
            ->unique()
            ->flip();

        // 直近14日分の活動有無
        $days = [];
        for ($i = 13; $i >= 0; $i--) {
            $date = now()->subDays($i)->startOfDay();
            $days[] = ['date' => $date, 'active' => $activeDates->has($date->toDateString())];
        }

        // 連続執筆日数（今日になければ昨日を起点に数える）
        $streak = 0;
        $cursor = $activeDates->has(now()->toDateString())
            ? now()->startOfDay()
            : now()->subDay()->startOfDay();
        while ($activeDates->has($cursor->toDateString())) {
            $streak++;
            $cursor = $cursor->subDay();
        }

        return ['days' => $days, 'streak' => $streak];
    }

    public function create()
    {
        return view('books.create');
    }

    public function store(BookRequest $request)
    {
        $data = $request->safe()->except(['cover', 'remove_cover']);
        $data['user_id'] = $request->user()->id;
        $book = Book::create($data);
        $this->createDefaultTasks($book);
        $this->applyCover($book, $request);

        return redirect()->route('books.show', $book)
            ->with('status', '本を作成しました。さっそく章を追加してみましょう。');
    }

    public function show(Book $book)
    {
        $this->authorize('view', $book);
        $book->load(['chapters', 'publishingTasks', 'kdpMetadata']);
        return view('books.show', compact('book'));
    }

    public function edit(Book $book)
    {
        $this->authorize('update', $book);
        return view('books.edit', compact('book'));
    }

    public function update(BookRequest $request, Book $book)
    {
        $this->authorize('update', $book);
        $book->update($request->safe()->except(['cover', 'remove_cover']));
        $this->applyCover($book, $request);

        return redirect()->route('books.show', $book)
            ->with('status', '本の情報を更新しました。');
    }

    /** 表紙画像のアップロード／削除を反映する */
    private function applyCover(Book $book, BookRequest $request): void
    {
        // 「表紙を削除」または新しい画像のアップロード時は、既存ファイルを片付ける
        if (($request->boolean('remove_cover') || $request->hasFile('cover')) && $book->cover_path) {
            Storage::disk('public')->delete($book->cover_path);
            $book->cover_path = null;
        }

        if ($request->hasFile('cover')) {
            $book->cover_path = $request->file('cover')->store("covers/{$book->id}", 'public');
        }

        $book->save();
    }

    public function destroy(Book $book)
    {
        $this->authorize('delete', $book);
        $book->delete();

        return redirect()->route('books.index')
            ->with('status', '本をゴミ箱に移動しました。30日以内ならゴミ箱から戻せます。');
    }

    /** ゴミ箱：論理削除した本の一覧 */
    public function trash(Request $request)
    {
        $books = $request->user()->books()
            ->onlyTrashed()
            ->withCount('chapters')
            ->latest('deleted_at')
            ->get();

        return view('books.trash', ['books' => $books]);
    }

    /** ゴミ箱から本を復元する */
    public function restore(Request $request, int $id)
    {
        $book = $request->user()->books()->onlyTrashed()->findOrFail($id);
        $this->authorize('restore', $book);
        $book->restore();

        return redirect()->route('books.show', $book)
            ->with('status', '本を復元しました。');
    }

    /** ゴミ箱から本を完全に削除する（取り消し不可） */
    public function forceDelete(Request $request, int $id)
    {
        $book = $request->user()->books()->onlyTrashed()->findOrFail($id);
        $this->authorize('forceDelete', $book);
        // 表紙画像も片付ける
        Storage::disk('public')->deleteDirectory("covers/{$book->id}");
        $book->forceDelete();

        return redirect()->route('books.trash')
            ->with('status', '本を完全に削除しました。');
    }

    public function exportMarkdown(Book $book)
    {
        $this->authorize('view', $book);
        $book->load('chapters');

        $content = '# ' . $book->title . "\n\n";
        if ($book->subtitle) {
            $content .= '## ' . $book->subtitle . "\n\n";
        }

        foreach ($book->chapters as $chapter) {
            // 本のタイトルが見出し1なので、章は見出し2にして階層を保つ
            $content .= "\n## " . $chapter->title . "\n\n";
            $content .= ($chapter->body ?? '') . "\n";
        }

        // 日本語タイトルだと Str::slug が空になることがあるためフォールバックを用意
        $slug = Str::slug($book->title);
        $filename = ($slug !== '' ? $slug : 'book-' . $book->id) . '.md';

        return response($content)
            ->header('Content-Type', 'text/markdown; charset=UTF-8')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }

    /** EPUB を生成してダウンロードさせる（#16） */
    public function exportEpub(Book $book, EpubBuilder $epub)
    {
        $this->authorize('view', $book);

        $path = $epub->build($book);

        return response()->download($path, $epub->filename($book), [
            'Content-Type' => 'application/epub+zip',
        ])->deleteFileAfterSend(true);
    }

    /** KDP登録シート（コピペ用・文字数カウンタ付き）（#18） */
    public function kdpSheet(Book $book)
    {
        $this->authorize('view', $book);
        $book->load('kdpMetadata');

        return view('books.kdp-sheet', compact('book'));
    }

    /** 出版前チェックリストの初期項目を作成する */
    private function createDefaultTasks(Book $book): void
    {
        $titles = [
            'KDPアカウントを作成した',
            '著者名を決めた',
            '書籍タイトルを決めた',
            '表紙を準備した',
            '原稿を通読した',
            '誤字脱字を確認した',
            '説明文を作成した',
            'キーワードを7つ以内で決めた',
            'カテゴリ候補を決めた',
            '価格を決めた',
            'Kindle Previewerで表示確認した',
        ];

        foreach ($titles as $i => $title) {
            $book->publishingTasks()->create([
                'title' => $title,
                'is_done' => false,
                'sort_order' => $i + 1,
            ]);
        }
    }
}
