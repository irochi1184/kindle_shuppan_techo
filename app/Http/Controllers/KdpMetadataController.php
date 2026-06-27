<?php

namespace App\Http\Controllers;

use App\Http\Requests\KdpMetadataRequest;
use App\Models\Book;

class KdpMetadataController extends Controller
{
    /** 出版情報（KDP）を保存する。書籍ごとに1件を作成 or 更新 */
    public function update(KdpMetadataRequest $request, Book $book)
    {
        $this->authorize('update', $book);
        $data = $request->validated();

        // 改行区切りのテキストを配列へ変換（空行は除外）。件数上限はリクエスト側で検証済み。
        $keywords = $request->splitLines($data['keywords'] ?? '');
        $categories = $request->splitLines($data['categories'] ?? '');

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
}
