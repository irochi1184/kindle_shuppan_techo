<?php

namespace App\Policies;

use App\Models\Book;
use App\Models\User;

class BookPolicy
{
    /** 所有者だけが自分の本を閲覧・操作できる */
    public function view(User $user, Book $book): bool
    {
        return $this->owns($user, $book);
    }

    public function update(User $user, Book $book): bool
    {
        return $this->owns($user, $book);
    }

    public function delete(User $user, Book $book): bool
    {
        return $this->owns($user, $book);
    }

    public function restore(User $user, Book $book): bool
    {
        return $this->owns($user, $book);
    }

    public function forceDelete(User $user, Book $book): bool
    {
        return $this->owns($user, $book);
    }

    /** 本の所有者かどうか */
    protected function owns(User $user, Book $book): bool
    {
        return $book->user_id === $user->id;
    }
}
