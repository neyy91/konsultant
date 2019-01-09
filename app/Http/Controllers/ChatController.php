<?php

namespace App\Http\Controllers;

use Auth;
use Date;
use Illuminate\Http\Request;

use App\Libs\FileHelper;
use App\Repositories\ChatRepository;
use App\Events\Chat\ChatMessage;
use App\Models\Chat;
use App\Models\User;


/**
 * Чаты.
 */
class ChatController extends Controller
{
    use FileHelper;

    /**
     * Репозиторий чатов.
     * @var ChatRepository
     */
    protected $chats;

    /**
     * Конструктор чатов.
     * @param ChatRepository $chats
     */
    function __construct(ChatRepository $chats)
    {
        // \Debugbar::disable();
        $this->middleware('auth')->except(['start', 'chats']);

        $this->chats = $chats;
    }

    /**
     * Начало чата. Создание диалогов.
     * @param  User   $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function start(User $user)
    {
        if (Auth::guest()) {
            session()->flash('info', trans('user.need_to_login'));
            return response()->json([
                'redirect' => route('login'),
            ]);
        }
        $this->authorize('access', [Chat::class, $user]);

        $route = ['user.chat.view', ['user' => $user]];
        $me = Auth::user();

        $dialog = $this->chats->dialogWith($user, $me);

        return response()->json([
            'dialog' => view('user.chat.layout.dialog', [
                            'dialog' => $dialog,
                            'counts' => $this->chats->getNewMessagesCountByUser($me),
                        ])->render(),
            'id' => $user->id,
        ]);
    }

    /**
     * Чаты.
     * @return \Illuminate\Http\RedirectResponse
     */
    public function chats()
    {
        if (Auth::guest()) {
            return redirect()->route('login')->with('info', trans('user.need_to_login'));
        }
        $this->authorize('chat', Chat::class);

        $me = Auth::user();

        return view('user.chat.chats', [
            'user' => $me,
            'chats' => $this->chats->getChatsPaginate($me),
        ]);

    }

    /**
     * Валидация получение данных при получении сообщения.
     * @param  Request $request
     */
    public function validateIncoming($request)
    {
        $this->validate($request, [
            'from' => 'required|integer',
            'last' => 'nullable|integer',
        ]);
    }

    /**
     * Отправка данных при получении сообщения.
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function incomingForLayout(Request $request)
    {
        $this->authorize('chat', Chat::class);
        $this->validateIncoming($request);

        $me = Auth::user();
        $user = User::findOrFail($request->input('from'));
        $dialog = view('user.chat.layout.dialog', [
            'dialog' => $this->chats->getDialog($user),
            'counts' => $this->chats->getNewMessagesCountByUser($me),
        ])->render();

        $last = $request->input('last');
        $messages = null;
        if ($last) {
            $lastChat = Chat::find($last);
            if ($lastChat) {
                $lastMessages = $this->chats->getMessagesFromLast($lastChat, $user, $me);
                $messages = view('user.chat.messages', [
                    'chatList' => $lastMessages,
                    'day' => $lastChat->created_at->day,
                    'prevType' => $lastChat->getMessageType($me),
                    'me' => $me,
                ])->render();
            }
        }

        return response()->json([
            'id' => $user->id,
            'dialog' => $dialog,
            'messages' => $messages,
        ]);
    }

    /**
     * Просмотр чата.
     * @param  User   $user
     * @return \Illuminate\Http\Response
     */
    public function view(User $user)
    {
        $this->authorize('access', [Chat::class, $user]);

        $me = Auth::user();
        $dialog = $this->chats->getDialog($user, $me);
        if (!$dialog) {
            return redirect()->route('user.dashboard')->with('info', trans('chat.dialog_not_found_start_now'));
        }

        return view('user.chat.view', [
            'me' => $me,
            'user' => $user,
            'dialog' => $dialog,
            'chatList' => $this->chats->getMessages($user, $me),
        ]);
    }

    /**
     * Получение данных чата.
     * @param  Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function viewForLayout(Request $request)
    {
        $user = User::findOrFail($request->input('id'));
        $me = Auth::user();

        return response()->json([
            'id' => $user->id,
            'title' => view('user.chat.layout.title', [
                            'user' => $user,
                        ])->render(),
            'messages' => view('user.chat.messages', [
                            'chatList' => $this->chats->getMessages($user, $me),
                            'me' => $me,
                        ])->render(),
            'url' => [
                'send' => route('user.chat.message.send', ['user' => $user]),
                'chat' => route('user.chat.view', ['user' => $user]),
                'delete' => route('user.chat.delete', ['user' => $user]),
            ],
        ]);
    }

    /**
     * Валидация id непросмотренных сообщений.
     * @param  Request $request
     * @return void
     */
    public function validateViewed(Request $request)
    {
        $this->validate($request, [
            'from' => 'nullable|integer',
            'id' => 'nullable|array',
            'id.*' => 'integer',
        ]);
    }

    /**
     * Отметка времени просмотра сообщения.
     * @param  Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function setViewedMessages(Request $request)
    {
        $this->validateViewed($request);
        $chatList = Chat::findMany($request->input('id'));

        $this->authorize('chat', Chat::class);

        $me = Auth::user();
        $from = User::findOrFail($request->input('from'));

        foreach ($chatList as $chat) {
            if ($chat->from_id == $from->id && $chat->to_id == $me->id) {
                $chat->viewed_at = Date::now();
                $chat->save();
            }
        }
        
        return response()->json([
            'id' => $from->id,
            'vieweds' => $chatList->pluck('id')->toArray(),
            'count' => $this->chats->getNewMessagesCount($me),
        ]);
    }

    /**
     * Удаление чата(диалога).
     * @param  Request $request
     * @param  User    $user
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function delete(Request $request, User $user)
    {
        $me = Auth::user();
        $dialog = $this->chats->getDialog($user, $me);

        $this->authorize('delete', $dialog);

        // прочитано
        $this->chats->setViewedAll($me, $dialog->to);

        $dialog->delete();

        if ($request->ajax()) {
            return response()->json([
                'delete' => true,
                'redirect' => route('user.dashboard'),
            ]);
        }
        return redirect()->route('user.dashboard');
    }

    /**
     * Валидация сообщения.
     * @param  Request $request
     * @return void
     */
    protected function validateMessage(Request $request)
    {
        $this->validate($request, [
            'last' => 'nullable|integer',
            'message' => 'required|max:' . config('site.chat.message.max_text', 1000),
        ], trans('chat.validation.message'));
    }

    /**
     * Отправка сообщения.
     * @param  Request $request
     * @param  User    $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function messageSend(Request $request, User $user)
    {
        $this->authorize('message', [Chat::class, $user]);

        $me = Auth::user();
        $this->validateMessage($request);
        $message = Chat::sendMessage($request->input('message'), $user, $me);

        if ($message) {
            $this->chats->dialogWith($me, $user);
            event(new ChatMessage($message));
        }

        $lastMessages = $lastChat = null;
        if ($last = $request->input('last')) {
            $lastChat = Chat::find($last);
            if ($lastChat) {
                $lastMessages = $this->chats->getMessagesFromLast($lastChat, $user, $me);
            }
        }

        return response()->json([
            'messages' => view('user.chat.messages', [
                'chatList' => $lastMessages ? $lastMessages : [$message],
                'day' => $lastChat ? $lastChat->created_at->day : $this->chats->getPrevMessageDay($message, $user, $me),
                'prevType' => $lastChat ? $lastChat->getMessageType($me) : null,
                'me' => $me,
            ])->render(),
        ]);
    }
}
