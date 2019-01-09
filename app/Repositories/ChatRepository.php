<?php 

namespace App\Repositories;

use App\Models\Chat;
use App\Models\User;

/**
* Репозиторий для чатов.
*/
class ChatRepository
{

    /**
     * @var integer
     */
    const DEFAULT_MESSAGES = 20;
    const DEFAULT_CHATS = 20;

    /**
     * With по умолчанию.
     * @var array
     */
    static $with = [
        'default' => ['from', 'to'],
        'message' => ['to'],
        'more' => ['from', 'from.photo', 'from.city', 'to', 'to.photo', 'to.city']
    ];

    /**
     * Количество сообщений в чате.
     * @param  integer|null $count
     * @return integer
     */
    protected function getMessagesCount($count = null)
    {
        if (!$count) {
            $count = config('site.chat.count.messages', self::DEFAULT_MESSAGES);
        }
        return $count;
    }

    /**
     * Количество чатов(диалогов) на странице.
     * @param  integer|null $count
     * @return integer
     */
    protected function getChatsCount($count = null)
    {
        if (!$count) {
            $count = config('site.chat.count.chats', self::DEFAULT_CHATS);
        }
        return $count;
    }

    /**
     * Получение сообщений.
     * @param  User         $user
     * @param  User|null    $me
     * @param  integer|null $count
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function getMessages(User $user, $me = null, $count = null)
    {
        return Chat::setUsers($user, $me)->onlyMessages()->with(self::$with['message'])->take($this->getMessagesCount($count))->latest()->get()->sortBy('created_at');
    }

    /**
     * Получение новых сообщений.
     * @param  User         $user
     * @param  User|null    $me
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function getNewMessages(User $user, $me = null)
    {
        return Chat::setUsers($user, $me)->onlyMessages()->unviewed()->orderBy('created_at', 'asc')->get();
    }

    /**
     * Количество непрочитанных сообщений.
     * @param  User   $me
     * @return integer
     */
    public function getNewMessagesCount(User $me)
    {
        return Chat::onlyMessages()->incoming($me)->unviewed()->count();
    }

    /**
     * Получение количество сообщений.
     * @param  User         $me
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function getNewMessagesCountByUser(User $me, $count = null)
    {
        $from = Chat::onlyUserDialogs($me)->select('to_id')->take($this->getChatsCount($count))->get()->pluck('to_id')->all();
        $messages = Chat::onlyMessages()->incoming($me, $from)->unviewed()->select('id', 'from_id')->get();
        $counts = [
            'all' => $messages->count(),
        ];
        foreach ($messages as $message) {
            if (isset($counts[$message->from_id])) {
                $counts[$message->from_id]++;
            }
            else {
                $counts[$message->from_id] = 1;
            }
        }
        return $counts;
    }

    public function setViewedAll(User $me, User $from)
    {
        return Chat::onlyMessages()->unviewed()->incoming($me, $from)->update(['viewed_at' => Chat::getDateFormatDBValue()]);
    }

    /**
     * Получение новых сообщений начиная с последнего чата $lastChat.
     * @param  Chat         $lastChat
     * @param  User         $user
     * @param  User||null   $me
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function getMessagesFromLast(Chat $lastChat, User $user, $me = null)
    {
        return Chat::setUsers($user, $me)->onlyMessages()->where('created_at', '>', $lastChat->created_at)->with(self::$with['message'])->latest()->get()->sortBy('created_at');
    }

    /**
     * День предыдуего сообщения.
     * @param  Chat         $chat
     * @param  User         $user
     * @param  User|null    $me
     * @return Chat
     */
    public function getPrevMessageDay(Chat $chat, User $user, $me = null)
    {
        $prev = Chat::setUsers($user, $me)->onlyMessages()->where('created_at', '<', $chat->created_at)->first();
        return $prev ? $prev->created_at->day : null;
    }

    /**
     * Получение диалогов пользователя $me.
     * @param  User     $me
     * @param  integer  $count
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function getDialogs(User $me, $count = null)
    {
        return Chat::onlyUserDialogs($me)->with(self::$with['more'])->take($this->getChatsCount($count))->orderBy('created_at', 'desc')->get();
    }

    /**
     * Чаты с пользователям имеющий 1 сообщение постранично.
     * @param  User         $me
     * @param  integer|null $page
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getChatsPaginate(User $me, $page = null)
    {
        return Chat::onlyMessages()->setUsers(null, $me)->with(self::$with['more'])->groupBy('to_id')->orderBy('created_at', 'desc')->paginate($this->getChatsCount($page));
    }

    /**
     * Получение диалогов по id.
     * @param  array  $ids
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function getUsersDialog(array $users)
    {
        return Chat::onlyDialogs()->with(self::$with['more'])->whereIn('user_id', $users);
    }

    /**
     * Диалог пользователей.
     * @param  User         $user
     * @param  User|null    $me
     * @return Chat
     */
    public function getDialog(User $user, $me = null)
    {
        return Chat::outgoing($me, $user)->onlyDialogs()->first();
    }

    /**
     * Диалог с пользователем. Создание, если нет.
     * @param  User         $user
     * @param  User|null    $me
     * @return Chat
     */
    public function dialogWith(User $user, $me = null)
    {
        // нету? создаем диалог
        $dialog = $this->getDialog($user, $me);
        if (!$dialog) {
            $dialog = new Chat(['message' => null]);
            $dialog->is = Chat::IS_DIALOG;
            $dialog->from()->associate($me);
            $dialog->to()->associate($user);
            return $dialog->save() ? $dialog : null;
        }
        // есть? обновляем
        $dialog->touch();
        $dialog->save();
        return $dialog;
    }
}