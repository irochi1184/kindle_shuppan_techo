<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChapterRequest;
use App\Models\Book;
use App\Models\Chapter;

class ChapterController extends Controller
{
    public function create(Book $book)
    {
        $this->authorize('update', $book);
        return view('chapters.create', compact('book'));
    }

    public function store(ChapterRequest $request, Book $book)
    {
        $this->authorize('update', $book);
        $data = $request->safe()->except('version_note');
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

    public function update(ChapterRequest $request, Chapter $chapter)
    {
        $this->authorize('update', $chapter->book);
        $data = $request->safe()->except('version_note');
        $data['word_count'] = mb_strlen(strip_tags($data['body'] ?? ''));

        $chapter->update($data);
        $saved = $this->saveVersion($chapter, $request->input('version_note'));

        return redirect()->route('chapters.show', $chapter)
            ->with('status', $saved
                ? '章を保存しました。保存履歴に追加されています。'
                : '章を保存しました。本文に変更がないため、保存履歴は追加していません。');
    }

    public function destroy(Chapter $chapter)
    {
        $this->authorize('update', $chapter->book);
        $book = $chapter->book;
        $chapter->delete();

        return redirect()->route('books.show', $book)
            ->with('status', '章を削除しました。');
    }

    /**
     * 保存履歴（版）を追加する。
     * 直前の版と本文が同一なら追加しない（#9: 無変更の版で履歴が肥大化するのを防ぐ）。
     *
     * @return bool 版を追加したら true
     */
    private function saveVersion(Chapter $chapter, ?string $note): bool
    {
        $latest = $chapter->versions()->first(); // versions() は最新順
        if ($latest && (string) $latest->body === (string) $chapter->body) {
            return false;
        }

        $chapter->versions()->create([
            'body' => $chapter->body,
            'note' => $note ?: '保存',
            'word_count' => $chapter->word_count,
        ]);

        return true;
    }
}
