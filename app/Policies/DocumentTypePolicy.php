<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

use App\Models\User;
use App\Models\DocumentType;


class DocumentTypePolicy
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
     * Просмотр документа.
     * @param  User         $user
     * @param  DocumentType $type
     * @return boolean
     */
    public function view(User $user, DocumentType $type)
    {
        return $user->can('admin', User::class)
            || $type->status == Document::PUBLISHED;
    }
}
