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
        return view('chapters.create', compact('book'));
    }

    public function store(Request $request, Book $book)
    {
        $data = $this->validated($request);
        $data['book_id'] = $book->id;
        $data['word_count'] = mb_strlen(strip_tags($data['body'] ?? ''));

        $chapter = Chapter::create($data);
        $this->saveVersion($chapter, '初回作成');

        return redirect()->route('chapters.show', $chapter);
    }

    public function show(Chapter $chapter)
    {
        $chapter->load(['book', 'versions']);
        return view('chapters.show', compact('chapter'));
    }

    public function edit(Chapter $chapter)
    {
        $chapter->load('book');
        return view('chapters.edit', compact('chapter'));
    }

    public function update(Request $request, Chapter $chapter)
    {
        $data = $this->validated($request);
        $data['word_count'] = mb_strlen(strip_tags($data['body'] ?? ''));

        $chapter->update($data);
        $this->saveVersion($chapter, $request->input('version_note'));

        return redirect()->route('chapters.show', $chapter);
    }

    public function destroy(Chapter $chapter)
    {
        $book = $chapter->book;
        $chapter->delete();
        return redirect()->route('books.show', $book);
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
