<?php

namespace App\Http\Controllers;

use Mail;
use Auth;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Requests\Site\FeedbackRequest;
use App\Models\Pay;
use App\Models\User;
use App\Models\Lawyer;
use App\Mail\Registered;
use App\Mail\Feedback;

/**
 * Контроллер сайта.
 */
class SiteController extends Controller
{

    /**
     * Конструктор.
     */
    function __construct()
    {
        $this->middleware('auth')->only(['feedbackForm', 'feedback']);
    }
    /**
     * Форма обратной связи.
     * @return \Illuminate\Http\Response
     */
    public function feedbackForm(Request $request)
    {
        return view('site.feedback.form', [
            'user' => Auth::user(),
            'theme' => $request->input('theme')
        ]);
    }

    /**
     * Отправка сообщения.
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     */
    public function feedbackSend(FeedbackRequest $request)
    {
        $user = Auth::user();
        $fields = $request->only(['theme', 'contact', 'text']);

        Mail::to(config('site.mail.feedback', 'feedback@konsultant.ru'))->send(new Feedback($user, $fields));

        $message = trans('feedback.message.message_send');
        if ($request->ajax()) {
            return response()->json([
                'messages' => [
                    'success' => $message,
                ],
            ]);
        }
        else {
            return redirect()->route('feedback')->with('success', $message);
        }
    }

    public function testStream(Request $request)
    {
        \Debugbar::disable();
        $some = $request->input('some') ?: 'no-data';
        event(new \App\Events\SomeEvent($some));
        return $some;
    }

    public function testStreamPage(Request $request)
    {
        \Debugbar::disable();
        return view('test_stream');
        // return response()->view('test_stream')->header('Access-Control-Allow-Origin', '*');
    }

}
