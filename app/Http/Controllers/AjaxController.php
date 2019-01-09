<?php

namespace App\Http\Controllers;

use Auth;
use Gate;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Repositories\ChatRepository;
use App\Models\BookmarkCategory;
use App\Models\Bookmark;
use App\Models\Chat;


/**
 * AJAX.
 */
class AjaxController extends Controller
{

    /**
     * Репозиторий чата.
     * @var ChatRepository
     */
    protected $chats;

    /**
     * Конструктор.
     * @param ChatRepository $chats
     */
    function __construct(ChatRepository $chats)
    {
        $this->chats = $chats;
    }

    /**
     * Получение AJAX диалога чата.
     * @return string
     */
    protected function getChatHtml($request)
    {
        if (Auth::guest() || Gate::denies('chat', Chat::class)) {
            return null;
        }
        
        Gate::allows('chat', Chat::class);
        $me = Auth::user();
        $dialogs = $this->chats->getDialogs($me);
        return view('user.chat.layout.dialogs', [
            'dialogs' => $dialogs,
            'user' => $me,
            'counts' => $this->chats->getNewMessagesCountByUser($me),
        ])->render();
    }

    protected function getLoginFormHtml($request)
    {
        if (Auth::check() || $request->input('route') == 'login') {
            return null;
        }

        return view('user.login_modal')->render();
    }

    /**
     * Данные отправляемые по умолчанию.
     * @param Request $reqeust
     * @return \Illuminate\Http\JsonResponse
     */
    public function getLayout(Request $request)
    {
        return response()->json([
            'chat' => $this->getChatHtml($request),
            'login' => $this->getLoginFormHtml($request),
        ]);
    }


}
