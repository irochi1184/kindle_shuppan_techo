<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BookController extends Controller
{
    public function index()
    {
        return view('books.index', ['books' => Book::latest()->get()]);
    }

    public function create()
    {
        return view('books.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'subtitle' => ['nullable', 'string', 'max:255'],
            'author_name' => ['nullable', 'string', 'max:255'],
            'target_reader' => ['nullable', 'string'],
            'book_goal' => ['nullable', 'string'],
            'reader_benefit' => ['nullable', 'string'],
            'description' => ['nullable', 'string'],
            'status' => ['nullable', 'string', 'max:50'],
        ]);

        $book = Book::create($data);
        $this->createDefaultTasks($book);

        return redirect()->route('books.show', $book)
            ->with('status', '本を作成しました。さっそく章を追加してみましょう。');
    }

    public function show(Book $book)
    {
        $book->load(['chapters', 'publishingTasks', 'kdpMetadata']);
        return view('books.show', compact('book'));
    }

    public function edit(Book $book)
    {
        return view('books.edit', compact('book'));
    }

    public function update(Request $request, Book $book)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'subtitle' => ['nullable', 'string', 'max:255'],
            'author_name' => ['nullable', 'string', 'max:255'],
            'target_reader' => ['nullable', 'string'],
            'book_goal' => ['nullable', 'string'],
            'reader_benefit' => ['nullable', 'string'],
            'description' => ['nullable', 'string'],
            'status' => ['nullable', 'string', 'max:50'],
        ]);

        $book->update($data);

        return redirect()->route('books.show', $book)
            ->with('status', '本の情報を更新しました。');
    }

    public function destroy(Book $book)
    {
        $book->delete();

        return redirect()->route('books.index')
            ->with('status', '本を削除しました。');
    }

    public function exportMarkdown(Book $book)
    {
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
