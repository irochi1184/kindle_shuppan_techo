<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\Chapter;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ChapterEditorTest extends TestCase
{
    use RefreshDatabase;

    private function chapterFor(User $user): Chapter
    {
        $book = Book::create(['user_id' => $user->id, 'title' => '本']);

        return Chapter::create(['book_id' => $book->id, 'title' => '章', 'sort_order' => 1]);
    }

    public function test_autosave_updates_body_without_creating_a_version(): void
    {
        $user = User::factory()->create();
        $chapter = $this->chapterFor($user);

        $this->actingAs($user)
            ->patchJson(route('chapters.autosave', $chapter), ['body' => 'あいうえお'])
            ->assertOk()
            ->assertJson(['word_count' => 5]);

        $this->assertSame('あいうえお', $chapter->fresh()->body);
        $this->assertSame(0, $chapter->versions()->count(), '自動保存では版を作らない');
    }

    public function test_autosave_is_blocked_for_non_owner(): void
    {
        $chapter = $this->chapterFor(User::factory()->create());

        $this->actingAs(User::factory()->create())
            ->patchJson(route('chapters.autosave', $chapter), ['body' => 'x'])
            ->assertForbidden();
    }

    public function test_chapters_can_be_reordered(): void
    {
        $user = User::factory()->create();
        $book = Book::create(['user_id' => $user->id, 'title' => '本']);
        $a = Chapter::create(['book_id' => $book->id, 'title' => 'A', 'sort_order' => 1]);
        $b = Chapter::create(['book_id' => $book->id, 'title' => 'B', 'sort_order' => 2]);

        $this->actingAs($user)
            ->patchJson(route('books.chapters.reorder', $book), ['chapters' => [$b->id, $a->id]])
            ->assertOk();

        $this->assertSame(1, $b->fresh()->sort_order);
        $this->assertSame(2, $a->fresh()->sort_order);
    }

    public function test_restoring_a_version_replaces_body_and_records_a_new_version(): void
    {
        $user = User::factory()->create();
        $chapter = $this->chapterFor($user);
        // 旧版を作り、その後本文を書き換える
        $old = $chapter->versions()->create(['body' => '最初の本文', 'note' => '初回作成', 'word_count' => 5]);
        $chapter->update(['body' => '書き直した本文', 'word_count' => 7]);
        $chapter->versions()->create(['body' => '書き直した本文', 'note' => '保存', 'word_count' => 7]);

        $this->actingAs($user)
            ->post(route('chapters.versions.restore', [$chapter, $old]))
            ->assertRedirect(route('chapters.show', $chapter));

        $this->assertSame('最初の本文', $chapter->fresh()->body);
        // 復元操作自体も版として残る（巻き戻しの巻き戻しが可能）
        $this->assertSame(3, $chapter->versions()->count());
    }
}
