<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;

use App\Helper;
use App\Http\Requests;
use App\Http\Requests\ExpertisesAdminFilterRequest;
use App\Repositories\ExpertiseRepository;
use App\Models\Expertise;
use App\Models\Question;


/**
 * Экспентиза.
 */
class ExpertiseController extends Controller
{

    /**
     * Репозиторий экспертизы.
     * @var ExpertiseRepository
     */
    protected $expertises;

    /**
     * Контролер
     * @param ExpertiseRepository $expertises
     */
    function __construct(ExpertiseRepository $expertises)
    {
        $this->expertises = $expertises;
    }

    /**
     * Проверка данных при создании.
     * @param  Request $request
     * @return void
     */
    protected function validateCreateMessage(Request $request)
    {
        $this->validate($request, [
            'message' => 'required|string|max:' . config('site.expertise.max_message', 5000),
        ], trans('expertise.validation.create_message'));
    }

    /**
     * Создание сообщение от эксперта.
     * @param  Request $request
     * @param  Question $question
     * @return \Illuminate\Http\JsonResponse
     */
    public function createMessage(Request $request, $id)
    {
        $question = Question::findOrFail($id);

        $this->authorize('message', [Expertise::class, $question]);

        $this->validateCreateMessage($request);

        $user = Auth::user();
        $expertise = new Expertise($request->all());
        $expertise->lawyer()->associate($user->lawyer);
        $expertise->question()->associate($question);
        $expertise->type = Expertise::TYPE_MESSAGE;
        
        if ($expertise->save()) {
            return response()->json([
                'html' => view('expertise.items', [
                    'expertises' => $this->expertises->getMessagesForQuestion($question),
                ])->render(),
                'messages' => [
                    'success' => trans('expertise.message.created'),
                ],
            ]);
        }
        else {
            return response()->json([
                'messages' => [
                    'warning' => trans('expertise.message.not_create'),
                ],
            ]);
        }
    }


    /**
     * Список экспертиз для админа.
     * @param ExpertisesAdminFilterRequest $request
     * @return \Illuminate\Http\Response
     */
    public function expertisesAdmin(ExpertisesAdminFilterRequest $request)
    {
        $fields = ['qid', 'lid', 'type'];
        $expertises = Helper::getRequestModel(
            Expertise::class,
            $request->all(),
            $fields,
            [
                'qid' => [
                    'field' => 'question_id',
                ],
                'lid' => [
                    'field' => 'lawyer_id',
                ],
            ]
        );
        $expertises->with('lawyer', 'lawyer.user', 'question');

        return view('expertise.admin.index', [
            'expertises' => $expertises->paginate(config('admin.page', 20))->appends($request->only($fields)),
            'request' => $request,
            'formVars' => ['types' => Expertise::getTypes()],
        ]);
    }

    /**
     * Форма для удаление экспертизы.
     * @param  Expertise $expertise
     * @return \Illuminate\Http\Response
     */
    public function deleteFormAdmin(Expertise $expertise)
    {
        return view('expertise.admin.delete', [
            'expertise' => $expertise,
        ]);
    }

    /**
     * Удаление экспертизы.
     * @param  Expertise $expertise
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteAdmin(Expertise $expertise)
    {
        if ($expertise->type == Expertise::TYPE_OWNER) {
            Expertise::where('question_id', $expertise->question->id)->delete();
        }
        elseif($expertise->type == Expertise::TYPE_EXPERT) {
            Expertise::where('question_id', $expertise->question->id)->where('lawyer_id', $expertise->lawyer->id)->delete();
        }
        else {
            $expertise->delete();
        }

        return redirect()->route('expertises.admin')->with('success', trans('expertise.message.deleted'))->with('updating', true);
    }
}
