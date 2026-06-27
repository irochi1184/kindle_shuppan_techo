<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\Chapter;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ChapterTest extends TestCase
{
    use RefreshDatabase;

    private function bookOwnedBy(User $user): Book
    {
        return Book::create(['user_id' => $user->id, 'title' => 'テスト本']);
    }

    public function test_creating_a_chapter_counts_words_and_saves_first_version(): void
    {
        $user = User::factory()->create();
        $book = $this->bookOwnedBy($user);

        $this->actingAs($user)->post(route('books.chapters.store', $book), [
            'title' => '第1章',
            'body' => 'あいうえお',
        ]);

        $chapter = Chapter::firstWhere('title', '第1章');
        $this->assertSame(5, $chapter->word_count);
        $this->assertSame(1, $chapter->versions()->count());
    }

    public function test_saving_without_body_change_does_not_add_a_version(): void
    {
        $user = User::factory()->create();
        $book = $this->bookOwnedBy($user);
        $chapter = Chapter::create([
            'book_id' => $book->id, 'title' => '章', 'body' => '本文', 'word_count' => 2,
        ]);
        // 初回の版を用意
        $chapter->versions()->create(['body' => '本文', 'note' => '初回作成', 'word_count' => 2]);

        // 本文を変えずにタイトルだけ更新
        $this->actingAs($user)->put(route('chapters.update', $chapter), [
            'title' => '章（改題）',
            'body' => '本文',
        ]);

        $this->assertSame(1, $chapter->versions()->count(), '本文無変更なら版は増えない');

        // 本文を変えれば版が増える
        $this->actingAs($user)->put(route('chapters.update', $chapter), [
            'title' => '章（改題）',
            'body' => '本文を書き直した',
        ]);
        $this->assertSame(2, $chapter->fresh()->versions()->count());
    }

    public function test_invalid_book_status_is_rejected(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->from(route('books.create'))
            ->post(route('books.store'), ['title' => '本', 'status' => 'invalid-status'])
            ->assertSessionHasErrors('status');
    }
}
