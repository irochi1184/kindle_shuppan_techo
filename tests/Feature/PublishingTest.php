<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\Chapter;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublishingTest extends TestCase
{
    use RefreshDatabase;

    public function test_keywords_over_seven_are_rejected(): void
    {
        $user = User::factory()->create();
        $book = Book::create(['user_id' => $user->id, 'title' => '本']);

        $this->actingAs($user)
            ->from(route('books.show', $book))
            ->put(route('books.metadata.update', $book), [
                'keywords' => collect(range(1, 8))->map(fn ($i) => "キーワード{$i}")->implode("\n"),
            ])
            ->assertSessionHasErrors('keywords');
    }

    public function test_keywords_up_to_seven_are_saved_as_array(): void
    {
        $user = User::factory()->create();
        $book = Book::create(['user_id' => $user->id, 'title' => '本']);

        $this->actingAs($user)->put(route('books.metadata.update', $book), [
            'keywords' => "A\n\nB\nC",   // 空行は除外される
            'price' => 500,
        ]);

        $meta = $book->fresh()->kdpMetadata;
        $this->assertSame(['A', 'B', 'C'], $meta->keywords_json);
        $this->assertSame(500, $meta->price);
    }

    public function test_markdown_export_joins_chapters_in_order(): void
    {
        $user = User::factory()->create();
        $book = Book::create(['user_id' => $user->id, 'title' => '私の本', 'subtitle' => '副題']);
        Chapter::create(['book_id' => $book->id, 'title' => '第2章', 'body' => 'B', 'sort_order' => 2]);
        Chapter::create(['book_id' => $book->id, 'title' => '第1章', 'body' => 'A', 'sort_order' => 1]);

        $response = $this->actingAs($user)->get(route('books.export.markdown', $book));

        $response->assertOk();
        $response->assertHeader('content-type', 'text/markdown; charset=UTF-8');
        $body = $response->getContent();
        // 本=H1、副題と章=H2、章は並び順
        $this->assertStringContainsString('# 私の本', $body);
        $this->assertStringContainsString('## 第1章', $body);
        $this->assertTrue(strpos($body, '第1章') < strpos($body, '第2章'), '章は並び順で結合される');
    }
}
