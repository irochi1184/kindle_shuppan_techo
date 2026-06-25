# Claude Code 作業指示書：Kindle出版手帳

あなたはLaravelに詳しい上級エンジニアです。
以下の仕様で「Kindle出版手帳」のMVPを実装してください。

## 目的
Kindle出版を目指す初心者が、書籍企画、章ごとの執筆、版管理、出版前チェックリスト、KDP用情報整理までできるWebサービスを作る。

## 技術
- Laravel 最新安定版
- MySQL
- Blade
- Tailwind CSSが使える場合は使用
- 認証はLaravel Breezeを使用

## 実装する機能

### 1. 書籍CRUD
- 書籍一覧
- 書籍作成
- 書籍詳細
- 書籍編集
- 書籍削除

項目：
- title
- subtitle
- author_name
- target_reader
- book_goal
- reader_benefit
- description
- status

### 2. 章CRUD
書籍詳細画面から章を管理できるようにする。

項目：
- book_id
- title
- summary
- body
- sort_order
- status
- word_count

本文保存時に文字数を自動計算する。

### 3. 章の版管理
章を保存するたびにchapter_versionsへ履歴を保存する。

項目：
- chapter_id
- body
- note
- word_count

章詳細画面で版の一覧を表示する。

### 4. 出版前チェックリスト
書籍ごとに出版前チェックリストを管理できるようにする。

初期項目：
- KDPアカウントを作成した
- 著者名を決めた
- 書籍タイトルを決めた
- 表紙を準備した
- 原稿を通読した
- 誤字脱字を確認した
- 説明文を作成した
- キーワードを7つ以内で決めた
- カテゴリ候補を決めた
- 価格を決めた
- Kindle Previewerで表示確認した

### 5. KDP情報管理
書籍ごとに以下を保存できるようにする。
- 説明文
- キーワード最大7件
- カテゴリ候補
- 価格
- ロイヤリティ設定メモ

### 6. Markdown出力
書籍ごとに、章の並び順に本文を結合し、Markdownファイルとしてダウンロードできるようにする。

## 画面方針
初心者向けなので、画面上には専門用語をなるべく使わない。
例：
- metadata → 出版情報
- version → 保存履歴
- manuscript → 原稿

## 注意
まずは完璧なAI機能やEPUB出力は作らなくてよい。
最初の目的は、実際に1冊の本を管理し、出版準備まで進められる状態にすること。
