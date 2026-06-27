<?php

namespace Tests\Feature;

use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * 未ログインのトップアクセスはログイン画面へ誘導される。
     */
    public function test_guest_is_redirected_to_login(): void
    {
        // / は本の一覧へ転送され、本の一覧は auth ミドルウェアでログインへ転送される
        $this->get('/')->assertRedirect(route('books.index'));
        $this->get(route('books.index'))->assertRedirect(route('login'));
    }
}
