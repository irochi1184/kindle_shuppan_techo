<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class KdpMetadataController extends Controller
{
    /** 出版情報（KDP）を保存する。書籍ごとに1件を作成 or 更新 */
    public function update(Request $request, Book $book)
    {
        $data = $request->validate([
            'description' => ['nullable', 'string'],
            'keywords' => ['nullable', 'string'],   // 改行区切りで入力 → 配列化
            'categories' => ['nullable', 'string'], // 改行区切りで入力 → 配列化
            'price' => ['nullable', 'integer', 'min:0'],
            'royalty_plan' => ['nullable', 'string', 'max:255'],
        ]);

        // 改行区切りのテキストを配列へ変換（空行は除外）。キーワードは最大7件まで。
        $keywords = $this->splitLines($data['keywords'] ?? '');
        $keywords = array_slice($keywords, 0, 7);
        $categories = $this->splitLines($data['categories'] ?? '');

        $book->kdpMetadata()->updateOrCreate(
            ['book_id' => $book->id],
            [
                'description' => $data['description'] ?? null,
                'keywords_json' => $keywords,
                'categories_json' => $categories,
                'price' => $data['price'] ?? null,
                'royalty_plan' => $data['royalty_plan'] ?? null,
            ]
        );

        return redirect()->route('books.show', $book)
            ->with('status', '出版情報を保存しました。');
    }

    /** 改行区切りのテキストを、トリム済み・空行除外の配列にする */
    private function splitLines(string $text): array
    {
        return collect(preg_split('/\r\n|\r|\n/', $text))
            ->map(fn ($line) => trim($line))
            ->filter(fn ($line) => $line !== '')
            ->values()
            ->all();
    }
}
