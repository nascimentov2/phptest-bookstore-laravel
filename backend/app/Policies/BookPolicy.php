<?php

namespace App\Policies;

use App\Models\Book;
use App\Models\User;

class BookPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function index(User $user, Book $book): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function store(User $user, Book $book): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function show(User $user, Book $book): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Book $book): bool
    {
        return true;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function destroy(User $user, Book $book): bool
    {
        return true;
    }
}
