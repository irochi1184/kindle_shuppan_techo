<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_see_books(): void
    {
        $this->get(route('books.index'))->assertRedirect(route('login'));
    }

    public function test_user_sees_only_their_own_books(): void
    {
        $me = User::factory()->create();
        $other = User::factory()->create();
        Book::create(['user_id' => $me->id, 'title' => '自分の本']);
        Book::create(['user_id' => $other->id, 'title' => '他人の本']);

        $this->actingAs($me)
            ->get(route('books.index'))
            ->assertOk()
            ->assertSee('自分の本')
            ->assertDontSee('他人の本');
    }

    public function test_creating_a_book_assigns_owner_and_default_checklist(): void
    {
        $me = User::factory()->create();

        $this->actingAs($me)->post(route('books.store'), ['title' => 'テスト本']);

        $book = Book::firstWhere('title', 'テスト本');
        $this->assertSame($me->id, $book->user_id);
        $this->assertSame(11, $book->publishingTasks()->count());
    }

    public function test_user_cannot_view_others_book(): void
    {
        $me = User::factory()->create();
        $book = Book::create(['user_id' => User::factory()->create()->id, 'title' => '他人の本']);

        $this->actingAs($me)->get(route('books.show', $book))->assertForbidden();
    }

    public function test_book_can_be_soft_deleted_and_restored(): void
    {
        $me = User::factory()->create();
        $book = Book::create(['user_id' => $me->id, 'title' => '消す本']);

        $this->actingAs($me)->delete(route('books.destroy', $book));
        $this->assertSoftDeleted($book);

        $this->actingAs($me)->patch(route('books.restore', $book->id));
        $this->assertNotSoftDeleted($book->fresh());
    }
}
