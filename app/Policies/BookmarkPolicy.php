<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

use App\Models\User;
use App\Models\Bookmark;
use App\Models\BookmarkCategory;
use App\Models\Question;


class BookmarkPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Может иметь закладки.
     * @param  User   $user
     * @return boolean
     */
    public function bookmarks(User $user)
    {
        return $user->can('provide', User::class) && $user->status;
    }

    /**
     * Может добавить в закладки.
     * @param  User     $user
     * @param  Question $question
     * @return boolean
     */
    public function bookmark(User $user, Question $question)
    {
        return $user->can('provide', User::class) && $user->status && $question->status != Question::STATUS_BLOCKED;
    }

    /**
     * Может удалить закладку.
     * @param  User     $user
     * @param  Bookmark $bookmark
     * @return boolean
     */
    public function delete(User $user, Bookmark $bookmark)
    {
        return true;
        return $user->status && $user->lawyer && $bookmark->lawyer_id && $user->lawyer->id == $bookmark->lawyer_id;
    }

    /**
     * Список категории.
     * @param  User   $user
     * @return boolean
     */
    public function categories(User $user)
    {
        return $user->can('provide', User::class) && $user->status;
    }

    /**
     * Может создать категорию.
     * @param  User   $user
     * @return boolean
     */
    public function categoryCreate(User $user)
    {
        return $user->can('provide', User::class) && $user->status && $user->lawyer->bookmarks->count() <= config('site.user.bookmart.max_category', 20);
    }

    /**
     * Может удалить категорию закладок.
     * @param  User             $user
     * @param  BookmarkCategory $category
     * @return boolean
     */
    public function categoryModify(User $user, BookmarkCategory $category)
    {
        return $user->status && $user->lawyer && $category->lawyer_id && $category->lawyer_id == $user->lawyer->id;
    }

}
