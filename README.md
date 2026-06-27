# Kindle出版手帳

Kindle出版を目指す初心者が、企画・執筆・版管理・出版準備まで迷わず進めるためのWebサービス案です。

最初の目的は、あなた自身が執筆中の「令和のインストラクショナルデザイン」をこのツールで管理し、実際の出版まで使い切ることです。

## 最小機能

1. 書籍企画の登録
2. 章ごとの原稿管理
3. 章ごとの版管理
4. 出版前チェックリスト
5. KDP用の説明文・キーワード・カテゴリ候補の管理
6. Markdown原稿の出力

## 技術構成

- Laravel
- MySQL
- Blade
- 認証：Laravel Breeze
- フロント：Tailwind CSS（Vite ビルド）
- Markdown保存
- 将来：AI API連携、EPUB/DOCX/PDF出力、Stripe決済

## セットアップ

```bash
composer install
npm install
cp .env.example .env        # 既に .env がある場合は不要
php artisan key:generate
php artisan migrate

# フロントの CSS/JS をビルド（Tailwind は Vite ビルドに移行済み）
npm run build      # 本番用に1回ビルド
# もしくは開発中は
npm run dev        # 変更を監視してホットリロード

php artisan serve  # http://127.0.0.1:8000
```

> 注意: 画面のスタイルは Vite でビルドした `public/build/` の資産を読み込みます。
> 初回や CSS 変更後は `npm run build`（または `npm run dev` を起動）してください。
> ビルドしていないと「Vite manifest not found」エラーになります。

