<?php

namespace App\Http\Controllers;

use Auth;
use Gate;
use Illuminate\Http\Request;

use App\Helper;
use App\Libs\FileHelper;
use App\Libs\StatusGeneralDefine;
use App\Http\Requests;
use App\Http\Requests\Answer\AnswerRequest;
use App\Http\Requests\Answer\AnswerDocumentRequest;
use App\Http\Requests\Answer\AnswerCallRequest;
use App\Http\Requests\Answer\AnswersFilterRequest;
use App\Http\Requests\Answer\AnswerIsQuestionRequest;
use App\Events\Answer\AnswerCreate;
use App\Events\Answer\AnswerIs;
use App\Events\Answer\AnswerUpdate;
use App\Repositories\UserRepository;
use App\Models\Question;
use App\Models\Document;
use App\Models\Answer;
use App\Models\Call;
use App\Models\User;
use App\Models\Lawyer;
use App\Models\Like;
use App\Models\File;
use App\Models\Subscribe;
use App\Models\Clarify;

/**
 * Ответы.
 */
class AnswerController extends Controller
{
    use FileHelper;

    /**
     * Репозиторий пользователя.
     * @var UserRepository
     */
    public $users;

    /**
     * Конструктор.
     * @param UserRepository $users
     */
    function __construct(UserRepository $users)
    {
        $this->users = $users;
    }

    /**
     * Создание ответа из запроса
     * @param  AnswerRequest $request
     * @param  \Illuminate\Database\Eloquent\Relations\MorphMany $answers
     * @return Answer
     */
    protected function createFor(&$for, $request, $parent = null)
    {
        \Debugbar::disable();
        $user = Auth::user();
        if ($for instanceof Answer) {
            $fromObject = $user;
        }
        else {
            $fromObject = $user->lawyer;
        }

        $answer = new Answer($request->all());
        $answer->to()->associate($for);
        $fromObject->answers()->save($answer);

        if ($request->hasFile('file') && $file = $this->saveRequestFile($request->file('file'), config('site.answer.file.directory', 'private/answers'))) {
            $fileModel = new File(['file' => $file, 'field' => 'file']);
            $fileModel->user()->associate($user);
            $fileModel->parent()->associate($parent ?: $for);
            $answer->file()->save($fileModel);
        }
        // Статус для услуги
        if (!($for instanceof Answer)) {
            if (isset($for->status) && $for->status == StatusGeneralDefine::STATUS_UNPUBLISHED) {
                $for->status = StatusGeneralDefine::STATUS_IN_WORK;
                $for->save();
            }
            // Подписка на услугу
            $subscribe = $this->users->getSubscibe($user, $for);
            if (!$subscribe) {
                $subscribe = new Subscribe();
                $subscribe->user()->associate($user)->owner()->associate($for)->save();
            }
        }

        return $answer;
    }

    /**
     * Ответ на вопрос.
     * @param  AnswerRequest $request
     * @param  integer        $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function createForQuestion(AnswerRequest $request, $id)
    {
        $question = Question::findOrFail($id);

        $this->authorize('answer', [Answer::class, $question]);

        $answer = $this->createFor($question, $request);

        event(new AnswerCreate($answer, Auth::user(), []));

        return view('answer.iframe',[
            'type' => 'question',
            'route' => ['name' => 'question.answer', 'params' => ['question' => $question]],
            'model' => $question->load('answers'),
            'show' => ['cost' => false, 'likes' => true, 'clarifies' => true],
            'messages' => [
                'success' => trans('question.message.answer_add'),
            ],
        ]);
    }

    /**
     * Предложения документов от юристов.
     * @param  AnswerDocumentRequest $request
     * @param  integer       $id
     * @return \Illuminate\Http\Response
     */
    public function createForDocument(AnswerDocumentRequest $request, $id)
    {
        $document = Document::findOrFail($id);

        $this->authorize('answer', [Answer::class, $document]);

        $answer = $this->createFor($document, $request);

        event(new AnswerCreate($answer, Auth::user(), []));

        return view('answer.iframe',[
            'type' => 'document',
            'route' => ['name' => 'document.answer', 'params' => ['document' => $document]],
            'model' => $document->load('answers'),
            'show' => ['cost' => true, 'likes' => false, 'clarifies' => true],
            'messages' => [
                'success' => trans('document.message.answer_add'),
            ],
        ]);
        // return redirect()->route('document.answer', ['document' => $document, 'answer' => $answer])->with('message.answer', ['type' => 'success', 'text' => trans('answer.message.created')])->with(['answer_id' => $answer->id]);
    }

    /**
     * Заявка контактов от юристов.
     * @param  AnswerRequest $request
     * @param  integer       $id
     * @return \Illuminate\Http\Response
     */
    public function createForCall(AnswerCallRequest $request, $id)
    {
        $call = Call::findOrFail($id);

        $this->authorize('answer', [Answer::class, $call]);

        $answer = $this->createFor($call, $request);

        event(new AnswerCreate($answer, Auth::user(), []));

        return view('answer.iframe',[
            'type' => 'call',
            'route' => ['name' => 'call.answer', 'params' => ['call' => $call]],
            'model' => $call->load('answers'),
            'show' => ['cost' => false, 'likes' => false, 'clarifies' => false],
            'messages' => [
                'success' => trans('call.message.answer_add'),
            ],
        ]);
    }

    /**
     * Ответ юристу(на другой ответ).
     * @param  AnswerRequest $request
     * @param  integer       $id
     * @return \Illuminate\Http\Response
     */
    public function createForAnswer(AnswerRequest $request, $id)
    {
        $answer = Answer::findOrFail($id);

        $this->authorize('reply', $answer);

        $parent = $answer->to_type != Answer::MORPH_NAME ? $answer->to : null;
        
        $newAnswer = $this->createFor($answer, $request, $parent);

        event(new AnswerCreate($answer, Auth::user()));

        return view('answer.iframe',[
            'type' => 'answer',
            'route' => ['name' => "{$answer->to_type}.answer", 'params' => [$answer->to_type => $answer->to]],
            'model' => $answer->load('answers'),
            'messages' => [
                'success' => trans('answer.message.created'),
            ],
        ]);
    }

    /**
     * Форма обновления.
     * @param  integer $id
     * @return \Illuminate\Http\JsonResponse
     */
/*    public function updateForm($id)
    {
        $answer = Answer::findOrFail($id);

        return response()->json([
            'form' => view('answer.form_update', [
                            'to' => $answer,
                            'type' => $answer->to_type,
                            'panel' => 'info',
                            'show_remove' => true,
                            'method' => 'PUT',
                            'action' => route('answer.update', ['id' => $answer->id]),
                            'icon' => 'refresh',
                            'button_text' => trans('answer.update_answer'),
                        ])->render(),
        ]);
    }
*/
    /**
     * Обновление ответа.
     * @param  AnswerRequest $request
     * @param  integer       $id
     * @return 
     */
    /*public function update(AnswerRequest $request, $id)
    {
        $answer = Answer::findOrFail($id);

        $this->authorize('update', $answer);

        $answer->text = $request->input('text');
        $answer->save();

        if ($request->hasFile('file') && $file = $this->saveRequestFile($request->file('file'), config('site.answer.file.directory', 'private/answers'))) {
            if ( $answer->file && $answer->file->count() != 0) {
                $answer->file->delete();
            }
            $fileModel = new File(['file' => $file, 'field' => 'file']);
            $fileModel->user()->associate(Auth::user());
            $parent = $answer->to_type != Answer::MORPH_NAME ? $answer->to : null;
            if ($parent) {
                $fileModel->parent()->associate($parent);
            }
            $answer->file()->save($fileModel);
        }

        event(new AnswerUpdate($answer, Auth::user()));

        return view('answer.iframe',[
            'type' => $answer->to_type,
            'route' => ['name' => $answer->to_type . '.answer', 'params' => [$answer->to_type => $answer->to]],
            'model' => $answer->to->load('answers'),
            'messages' => [
                'success' => trans($answer->to_type . '.message.answer_updated'),
            ],
        ]);
    }*/

    /**
     * Переадресация на ответ страницы вопроса.
     * @param  integer $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function redirectToAnswer($id)
    {
        $answer = Answer::findOrFail($id);
        return redirect()->route($answer->to_type . '.answer', [$answer->to_type => $answer->to, 'answer' => $answer]);
    }

    /**
     * Установка оценки при выборе исполнителя.
     * @param Answer $answer
     * @param integer $is
     */
    protected function setLikeForAnswer(Answer $answer, $text)
    {
        $user = Auth::user();
        $likes = $answer->likes()->where(['user_id' => $user->id])->get();
        if ($answer->is && $likes->count() === 0) {
            // $this->authorize('like-answer', [Like::class, $answer]);
            $like = new Like(['rating' => 1, 'text' => $text]);
            $like->entity()->associate($answer);
            $user->likes()->save($like);
            return $like;
        }
        return null;
    }

    /**
     * Модальное окно.
     * @param  Like   $like
     * @return array
     */
    protected function getModalJson(Like $like)
    {
        return [
            'title' => trans('like.comments_to_review'),
            'body' => view('like.form', [
                'like' => $like,
            ])->render(),
        ];
    }

    protected function setIsForAll(Answer $answer, $params = [])
    {
        // Выбран
        $answer->is = true;
        if (isset($params['rate'])) {
            $answer->rate = $params['rate'];
        }
        $answer->save();

        // Установка статуса
        $service = $answer->to;
        $count = $service->answers()->where('is', 1)->count();
        $save = false;

        if ($count > 0 && $service->status === $service::STATUS_IN_WORK) {
            $service->status = $service::STATUS_LAWYER_SELECTED;
            $save = true;
        } elseif($count === 0 && $service->status === $service::STATUS_LAWYER_SELECTED) {
            $service->status = $service::STATUS_IN_WORK;
            $save = true;
        }
        
        if ($save) {
            $service->save();
        }
    }
        

    /**
     * Установка подходящего ответа для вопроса.
     * @param AnswerIsQuestionRequest $request
     * @param integer  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function setIsForQuestion(AnswerIsQuestionRequest $request, $id)
    {
        $answer = Answer::findOrFail($id);

        $this->authorize('is-for-question', $answer);

        $this->setIsForAll($answer, ['rate' => $request->input('rate')]);

        $title = trans('answer.is_help.question');
        // Установка удаления отзыва(Like)
        $like = $this->setLikeForAnswer($answer, $request->input('comment'));

        event(new AnswerIs($answer, Auth::user(), ['like' => $like]));

        $question = $answer->load('to')->to;

        return response()->json([
            'id' => $answer->id,
            'html' => view('question.article', ['question' => $question])->render(),
            'messages' => [
                'success' => trans($answer->is ? 'answer.message.select_answer' : 'answer.message.unselect_answer')
            ]
        ]);
    }

    /**
     * Установка подходящего ответа.
     * @param Request $request
     * @param Answer $answer
     * @param string  $type
     */
    protected function setIsForOneChoice(Request $request, $answer, $type)
    {
        // только одного ответа
        $answer->to->answers()->where('is', 1)->update(['is' => 0]);
        // установка подходящего ответа
        if ($request->has('is')) {
            $this->setIsForAll($answer, $request->input('is'));
        }
    }

    /**
     * Установка подходящего ответа для ответа. Только один ответ(юрист).
     * @param Request $request
     * @param integer  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function setIsForDocument(Request $request, $id)
    {
        $answer = Answer::findOrFail($id);

        $this->authorize('is-for-document', $answer);

        $this->setIsForOneChoice($request, $answer, 'document');
        $messageType = $answer->is ? 'success' : 'info';

        event(new AnswerIs($answer, Auth::user()));

        $document = $answer->load('to')->to;

        return response()->json([
            'is' => $answer->is,
            'html' => view('document.article', ['document' => $document])->render(),
            'messages' => [
                $messageType => trans($answer->is ? 'answer.message.lawyer_select' : 'answer.message.lawyer_unselect')
            ]
        ]);
    }

    /**
     * Установка подходящего ответа для ответа. Только один ответ(юрист).
     * @param Request $request
     * @param integer  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function setIsForCall(Request $request, $id)
    {
        $answer = Answer::findOrFail($id);

        $this->authorize('is-for-call', $answer);

        $this->setIsForOneChoice($request, $answer, 'call');
        $messageType = $answer->is ? 'success' : 'info';
        
        event(new AnswerIs($answer, Auth::user()));

        $call = $answer->to;

        return response()->json([
            'is' => $answer->is,
            'html' => view('call.article', ['call' => $call])->render(),
            'messages' => [
                $messageType => trans($answer->is ? 'answer.message.select_lawyer_call' : 'answer.message.unselect_lawyer_call')
            ]
        ]);
    }

    /**
     * Ответы на разные типы данных(вопросы, документы...). Для админов.
     * @param  AnswersFilterRequest $request
     * @param  string $type
     * @return \Illuminate\Http\Response
     */
    public function answersAdmin(AnswersFilterRequest $request, $type)
    {
        $answers = Helper::getRequestModel(
            Answer::where('to_type', $type),
            $request->all(),
            ['is']
        );

        return view('answer.admin.index', [
            'answers' => $answers->paginate(config('admin.page', 20)),
            'request' => $request,
            'type' => $type,
        ]);
    }

    /**
     * Отображение формы подтвержение удаления.
     * @param  integer $id
     * @return \Illuminate\Http\Response
     */
    public function deleteFormAdmin($id)
    {
        $answer = Answer::findOrFail($id);
        return view('answer.admin.delete', ['answer' => $answer]);
    }

    /**
     * Удаление ответа.
     * @param  integer $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteAdmin($id)
    {
        $answer = Answer::findOrFail($id);
        $answer->delete();
        return redirect()->route('answers.admin', ['type' => $answer->to_type])->with('warning', trans('question.message.deleted'))->with('updating', true);
    }

}
