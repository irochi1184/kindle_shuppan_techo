@props(['name', 'class' => 'w-5 h-5'])

{{--
    細線（stroke-width 1.75）の統一アイコンセット。
    currentColor を使うので、親の text-* 色がそのまま反映されます。
    使い方: <x-icon name="plus" class="w-4 h-4" />
--}}

@php
    // 各アイコンの中身（path 等）。viewBox は 24x24 で統一。
    $paths = [
        // 開いた本（ブランドロゴ用）
        'book-open' => '<path d="M12 6.5C10.5 5.3 8.6 4.7 5.8 4.7c-.6 0-1.1.1-1.6.3v12.4c.5-.2 1-.3 1.6-.3 2.8 0 4.7.6 6.2 1.8 1.5-1.2 3.4-1.8 6.2-1.8.6 0 1.1.1 1.6.3V5c-.5-.2-1-.3-1.6-.3-2.8 0-4.7.6-6.2 1.8Z"/><path d="M12 6.5V19"/>',
        // 本の山（空状態用）
        'books' => '<path d="M5 5.5h3.2v13H5z"/><path d="M9.7 5.5h3.2v13H9.7z"/><path d="m14.6 6.2 3 .8-2.8 11.4-3-.8z"/>',
        // プラス
        'plus' => '<path d="M12 5.5v13M5.5 12h13"/>',
        // 戻る矢印
        'arrow-left' => '<path d="M14.5 6.5 9 12l5.5 5.5"/>',
        // チェック
        'check' => '<path d="m5.5 12.5 4 4 9-9.5"/>',
        // 鉛筆（編集）
        'pencil' => '<path d="M15.5 5.5 18.5 8.5 9 18l-3.5.8L6.3 15z"/>',
        // ダウンロード（書き出し）
        'download' => '<path d="M12 4.5v10M8 11l4 4 4-4"/><path d="M5 18.5h14"/>',
        // ドキュメント（章/原稿）
        'document' => '<path d="M7 4.5h7l4 4v11H7z"/><path d="M14 4.5v4h4"/>',
        // 時計（保存履歴）
        'clock' => '<circle cx="12" cy="12" r="7.5"/><path d="M12 8v4.2l2.8 1.8"/>',
        // ゴミ箱（削除）
        'trash' => '<path d="M5.5 7h13M9.5 7V5.5h5V7M7 7l.8 11.5h8.4L17 7"/>',
        // 人（アカウント）
        'user' => '<circle cx="12" cy="8.5" r="3.5"/><path d="M5.5 19c.7-3.2 3.3-5 6.5-5s5.8 1.8 6.5 5"/>',
        // ログアウト
        'logout' => '<path d="M14 7.5V5.5H5v13h9v-2"/><path d="M10 12h9m0 0-3-3m3 3-3 3"/>',
        // 復元（元に戻す）
        'restore' => '<path d="M5 8.5h6.5A5.5 5.5 0 1 1 6 14"/><path d="M5 5v3.5h3.5"/>',
        // ドラッグハンドル（6点グリップ）
        'grip' => '<circle cx="9" cy="6" r="1"/><circle cx="9" cy="12" r="1"/><circle cx="9" cy="18" r="1"/><circle cx="15" cy="6" r="1"/><circle cx="15" cy="12" r="1"/><circle cx="15" cy="18" r="1"/>',
        // 炎（執筆ストリーク）
        'flame' => '<path d="M12 3c.6 3-1.5 4.2-2.8 5.7C8 10 7 11.4 7 13.5a5 5 0 0 0 10 0c0-1.8-.8-3.4-2-4.6-.3 1-1 1.8-2 2 .8-2.3.3-5.2-1-7.9Z"/>',
    ];

    $svg = $paths[$name] ?? '';
@endphp

<svg {{ $attributes->merge(['class' => $class]) }}
     viewBox="0 0 24 24" fill="none" stroke="currentColor"
     stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"
     aria-hidden="true">{!! $svg !!}</svg>
