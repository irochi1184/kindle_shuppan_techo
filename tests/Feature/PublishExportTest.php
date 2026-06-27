<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\Chapter;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use ZipArchive;

class PublishExportTest extends TestCase
{
    use RefreshDatabase;

    public function test_epub_export_returns_a_valid_epub_zip(): void
    {
        $user = User::factory()->create();
        $book = Book::create(['user_id' => $user->id, 'title' => 'EPUBの本', 'author_name' => '著者']);
        Chapter::create(['book_id' => $book->id, 'title' => '第1章', 'body' => "## 見出し\n\n本文です。", 'sort_order' => 1]);

        $response = $this->actingAs($user)->get(route('books.export.epub', $book));
        $response->assertOk();
        $response->assertHeader('content-type', 'application/epub+zip');

        // 中身が EPUB の必須ファイルを含む zip であることを確認
        $tmp = tempnam(sys_get_temp_dir(), 'epubtest');
        file_put_contents($tmp, $response->streamedContent());
        $zip = new ZipArchive();
        $this->assertTrue($zip->open($tmp) === true);
        $this->assertSame('application/epub+zip', $zip->getFromName('mimetype'));
        $this->assertNotFalse($zip->getFromName('META-INF/container.xml'));
        $this->assertNotFalse($zip->getFromName('OEBPS/package.opf'));
        $this->assertNotFalse($zip->getFromName('OEBPS/nav.xhtml'));
        $this->assertStringContainsString('第1章', $zip->getFromName('OEBPS/nav.xhtml'));
        $this->assertStringContainsString('<h2>見出し</h2>', $zip->getFromName('OEBPS/text/chap-1.xhtml'));
        $zip->close();
        @unlink($tmp);
    }

    public function test_epub_export_is_blocked_for_non_owner(): void
    {
        $book = Book::create(['user_id' => User::factory()->create()->id, 'title' => '他人の本']);

        $this->actingAs(User::factory()->create())
            ->get(route('books.export.epub', $book))
            ->assertForbidden();
    }

    public function test_cover_image_can_be_uploaded_and_removed(): void
    {
        Storage::fake('public');
        $user = User::factory()->create();
        $book = Book::create(['user_id' => $user->id, 'title' => '本']);

        $this->actingAs($user)->put(route('books.update', $book), [
            'title' => '本',
            'cover' => UploadedFile::fake()->image('cover.jpg', 800, 1280),
        ]);

        $book->refresh();
        $this->assertNotNull($book->cover_path);
        Storage::disk('public')->assertExists($book->cover_path);

        // 削除
        $this->actingAs($user)->put(route('books.update', $book), [
            'title' => '本',
            'remove_cover' => '1',
        ]);
        $this->assertNull($book->refresh()->cover_path);
    }

    public function test_non_image_cover_is_rejected(): void
    {
        Storage::fake('public');
        $user = User::factory()->create();
        $book = Book::create(['user_id' => $user->id, 'title' => '本']);

        $this->actingAs($user)
            ->from(route('books.edit', $book))
            ->put(route('books.update', $book), [
                'title' => '本',
                'cover' => UploadedFile::fake()->create('doc.pdf', 100, 'application/pdf'),
            ])
            ->assertSessionHasErrors('cover');
    }

    public function test_kdp_sheet_renders_with_metadata(): void
    {
        $user = User::factory()->create();
        $book = Book::create(['user_id' => $user->id, 'title' => 'KDP本']);
        $book->kdpMetadata()->create([
            'description' => '紹介文です',
            'keywords_json' => ['学習', 'AI'],
            'categories_json' => ['教育'],
            'price' => 500,
        ]);

        $this->actingAs($user)->get(route('books.kdp-sheet', $book))
            ->assertOk()
            ->assertSee('KDP登録シート')
            ->assertSee('学習')
            ->assertSee('紹介文です');
    }
}
