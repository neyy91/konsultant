<?php

namespace App\Http\Controllers;

use Auth;
use Debugbar;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Collection;

use App\Http\Requests;
use App\Models\Like;
use App\Models\Question;
use App\Models\Answer;
use App\Models\Document;


/**
 * Оценки, лайки, дислайки.
 */
class LikeController extends Controller
{

    /**
     * Праверка запроса.
     * @param  Request $request
     */
    protected function validateRequest(Request $request)
    {
        $this->validate($request, [
            'rating' => 'required|in:' . implode(',', Like::ratingKeys()),
        ]);
    }

    /**
     * Оценка ответа.
     * @param  Request $request
     * @param  Answer $answer
     * @return \Illuminate\Http\JsonResponse
     */
    public function createLikeForAnswer(Request $request, Answer $answer)
    {
        $this->authorize('like-answer', [Like::class, $answer]);

        $this->validateRequest($request);

        $user = Auth::user();
        $like = new Like($request->all());
        $like->entity()->associate($answer);
        $user->likes()->save($like);

        $answer->load('likes');

        return response()->json([
            'html' => view('answer.likes', ['answer' => $answer])->render(),
            'modal' => [
                'title' => trans('like.comments_to_review'),
                'body' => view('like.form', [
                    'like' => $like,
                ])->render(),
            ],
            'messages' => [
                'success' => trans('like.message.rate'),
            ],
        ]);
    }

    /**
     * Оценки ответа.
     * @param  Request $request
     * @param  Answer  $answer 
     * @return \Illuminate\Http\Response
     */
    public function answerLikes(Request $request, Answer $answer)
    {
        Debugbar::disable();
        $this->validateRequest($request);
        $likes = $answer->likes()->latest()->where('rating', $request->input('rating'))->take(3)->get();

        return view('like.items_easy', [
            'likes' => $likes,
            'lawyer' => $answer->from,
            'answer' => $answer,
        ]);
    }

    /**
     * Обновление оценки.
     * @param  Request $request
     * @param  Like    $like
     * @return \Illuminate\Http\JsonResponse
     */
    public function likedUpdate(Request $request, Like $like)
    {
        Debugbar::disable();
        $this->validate($request, [
            'text' => 'string|max:255',
        ]);
        $like->fill($request->all())->save();

        return response()->json([
            'like' => $like,
            'messages' => [
                'success' => trans('like.message.like_text'),
            ],
        ]);
    }

}
