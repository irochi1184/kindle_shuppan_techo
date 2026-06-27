<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Chapter;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ChapterController extends Controller
{
    public function create(Book $book)
    {
        $this->authorize('update', $book);
        return view('chapters.create', compact('book'));
    }

    public function store(Request $request, Book $book)
    {
        $this->authorize('update', $book);
        $data = $this->validated($request);
        $data['book_id'] = $book->id;
        $data['word_count'] = mb_strlen(strip_tags($data['body'] ?? ''));
        // 並び順の指定がなければ末尾に追加する
        if (empty($data['sort_order'])) {
            $data['sort_order'] = ($book->chapters()->max('sort_order') ?? 0) + 1;
        }

        $chapter = Chapter::create($data);
        $this->saveVersion($chapter, '初回作成');

        return redirect()->route('chapters.show', $chapter)
            ->with('status', '章を作成しました。');
    }

    public function show(Chapter $chapter)
    {
        $this->authorize('view', $chapter->book);
        $chapter->load(['book', 'versions']);
        return view('chapters.show', compact('chapter'));
    }

    public function edit(Chapter $chapter)
    {
        $this->authorize('update', $chapter->book);
        $chapter->load('book');
        return view('chapters.edit', compact('chapter'));
    }

    public function update(Request $request, Chapter $chapter)
    {
        $this->authorize('update', $chapter->book);
        $data = $this->validated($request);
        $data['word_count'] = mb_strlen(strip_tags($data['body'] ?? ''));

        $chapter->update($data);
        $this->saveVersion($chapter, $request->input('version_note'));

        return redirect()->route('chapters.show', $chapter)
            ->with('status', '章を保存しました。保存履歴に追加されています。');
    }

    public function destroy(Chapter $chapter)
    {
        $this->authorize('update', $chapter->book);
        $book = $chapter->book;
        $chapter->delete();

        return redirect()->route('books.show', $book)
            ->with('status', '章を削除しました。');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'summary' => ['nullable', 'string'],
            'body' => ['nullable', 'string'],
            'sort_order' => ['nullable', 'integer'],
            'status' => ['nullable', 'string', 'max:50'],
        ]);
    }

    private function saveVersion(Chapter $chapter, ?string $note): void
    {
        $chapter->versions()->create([
            'body' => $chapter->body,
            'note' => $note ?: '保存',
            'word_count' => $chapter->word_count,
        ]);
    }
}
