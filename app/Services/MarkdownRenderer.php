<?php

namespace App\Services;

use League\CommonMark\CommonMarkConverter;

/**
 * 原稿（Markdown）を HTML に変換する共通処理。
 * 編集画面のプレビュー・章詳細の表示・EPUB 出力で同じ変換結果になるようにする。
 */
class MarkdownRenderer
{
    private CommonMarkConverter $converter;

    public function __construct()
    {
        // 安全のため生 HTML は除去する
        $this->converter = new CommonMarkConverter([
            'html_input' => 'strip',
            'allow_unsafe_links' => false,
        ]);
    }

    public function toHtml(?string $markdown): string
    {
        $markdown = (string) $markdown;

        return $markdown !== '' ? $this->converter->convert($markdown)->getContent() : '';
    }
}
