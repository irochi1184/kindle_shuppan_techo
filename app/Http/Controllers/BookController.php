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

        return redirect()->route('books.show', $book);
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

        return redirect()->route('books.show', $book);
    }

    public function destroy(Book $book)
    {
        $book->delete();
        return redirect()->route('books.index');
    }

    public function exportMarkdown(Book $book)
    {
        $book->load('chapters');

        $content = '# ' . $book->title . "\n\n";
        if ($book->subtitle) {
            $content .= '## ' . $book->subtitle . "\n\n";
        }

        foreach ($book->chapters as $chapter) {
            $content .= "\n# " . $chapter->title . "\n\n";
            $content .= $chapter->body . "\n";
        }

        $filename = Str::slug($book->title) . '.md';

        return response($content)
            ->header('Content-Type', 'text/markdown; charset=UTF-8')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }
}
