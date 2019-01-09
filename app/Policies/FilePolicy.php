<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

use App\Models\User;
use App\Models\Lawyer;
use App\Models\File;
use App\Models\Question;
use App\Models\Document;
use App\Models\Call;
use App\Models\Answer;
use App\Models\Chat;
use App\Models\Clarify;

/**
 * Доступ к файлам.
 */
class FilePolicy
{
    use HandlesAuthorization;

    protected $privateServices = [Call::MORPH_NAME, Document::MORPH_NAME];

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function access(User $user, File $file)
    {
        // публичный файл
        if ($file->access === File::ACCESS_PUBLIC) {
            return true;
        }

        // dd($file->owner->from_type);

        return $user->isUser && $file->parent && $file->parent->user_id === $user->id // владелец услуги
            || $file->user_id == $user->id // владелец файла
            || $user->can('provide', User::class) && $file->owner && $file->owner_type === Clarify::MORPH_NAME && $file->owner->to_type === Question::MORPH_NAME // уточнение для услуги - доступно юристам
            || $file->owner_type == Chat::MORPH_NAME && in_array($user->id, [$file->owner->to_user->id, $file->owner->from_user->id]) // участники чата
/*            ||  $file->owner && $file->owner_type === Answer::MORPH_NAME && $file->owner->from && ( // файл ответа
                $file->owner->from_type === Lawyer::MORPH_NAME && $file->owner->from->user->id === $user->id // владелец ответа
                || $file->owner->to && (
                    $file->owner->to_type !== Answer::MORPH_NAME && $file->owner->to->user_id === $user->id // владелец услуги
                    || $file->owner->to_type === Answer::MORPH_NAME && $file->owner->to->from->user->id === $user->id
                )
            )
*/            || $user->can('admin') // админ
        ;
    }
}
