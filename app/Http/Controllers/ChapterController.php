<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChapterRequest;
use App\Models\Book;
use App\Models\Chapter;
use App\Models\ChapterVersion;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

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
     * 自動保存（#11）。本文と文字数だけを更新し、保存履歴（版）は作らない。
     * 明示的な「保存」操作のときだけ版を残すことで、履歴が下書きで埋まらないようにする。
     */
    public function autosave(Request $request, Chapter $chapter): JsonResponse
    {
        $this->authorize('update', $chapter->book);
        $body = (string) $request->validate(['body' => ['nullable', 'string']])['body'] ?? '';

        $chapter->update([
            'body' => $body,
            'word_count' => mb_strlen(strip_tags($body)),
        ]);

        return response()->json([
            'word_count' => $chapter->word_count,
            'saved_at' => now()->toIso8601String(),
        ]);
    }

    /** 章の並べ替え（#14）。送られた id の順に sort_order を振り直す。 */
    public function reorder(Request $request, Book $book): JsonResponse
    {
        $this->authorize('update', $book);
        $data = $request->validate([
            'chapters' => ['required', 'array'],
            'chapters.*' => ['integer'],
        ]);

        foreach ($data['chapters'] as $index => $chapterId) {
            $book->chapters()->whereKey($chapterId)->update(['sort_order' => $index + 1]);
        }

        return response()->json(['ok' => true]);
    }

    /** 保存履歴からの復元（#13）。選んだ版の本文に戻し、その操作自体も新しい版として残す。 */
    public function restoreVersion(Chapter $chapter, ChapterVersion $version)
    {
        $this->authorize('update', $chapter->book);
        abort_unless($version->chapter_id === $chapter->id, 404);

        $chapter->update([
            'body' => $version->body,
            'word_count' => $version->word_count,
        ]);

        $label = $version->created_at->format('Y/m/d H:i');
        $this->saveVersion($chapter, "「{$label}」の版から復元");

        return redirect()->route('chapters.show', $chapter)
            ->with('status', "「{$label}」の保存内容に復元しました。");
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
