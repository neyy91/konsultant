<?php

namespace App\Http\Controllers;

use Auth;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

use App\Helper;
use App\Libs\FileHelper;
use App\Libs\GetOrCreateUserForService;
use App\Http\Requests;
use App\Http\Requests\Question\QuestionRequest;
use App\Http\Requests\Question\QuestionUpdateRequest;
use App\Http\Requests\Question\QuestionsAdminFilterRequest;
use App\Policies\QuestionPolicy;
use App\Events\Question\QuestionCreate;
use App\Events\Question\QuestionExpertise;
use App\Repositories\QuestionRepository;
use App\Repositories\CityRepository;
use App\Repositories\CategoryLawRepository;
use App\Repositories\ExpertiseRepository;
use App\Models\Question;
use App\Models\City;
use App\Models\CategoryLaw;
use App\Models\Theme;
use App\Models\User;
use App\Models\Lawyer;
use App\Models\Expertise;
use App\Models\File;


/**
 * Вопросы.
 */
class QuestionController extends Controller
{

    use FileHelper,
        GetOrCreateUserForService;

    /**
     * Репозиторий для работы с вопросами.
     * @var QuestionRepository
     */
    protected $questions;

    /**
     * Репозиторий для работы с городами.
     * @var CategoryLawRepository
     */
    protected $cities;

    /**
     * Репозиторий категорий права.
     * @var CategoryLawRepository
     */
    protected $categoryLaws;

    /**
     * Репозиторий экспертизы.
     * @var ExpertiseRepository
     */
    protected $expertises;

    /**
     * Конструктор.
     * @param QuestionRepository $question
     */
    public function __construct(QuestionRepository $questions, CityRepository $cities, CategoryLawRepository $categoryLaws, ExpertiseRepository $expertises)
    {
        $this->questions = $questions;
        $this->cities = $cities;
        $this->categoryLaws = $categoryLaws;
        $this->expertises = $expertises;
    }

    /**
     * Получение данных для формы.
     * @return array
     */
    public function getFormVars()
    {
        $formVars = [];
        $formVars['statuses'] = Question::getStatuses();
        $formVars['froms'] = Question::getFroms();
        $formVars['cities'] = $this->cities->getList();
        $formVars['categories'] = $this->categoryLaws->getList();
        return $formVars;
    }

    protected function getSearchQuestions($fields)
    {
        $fields = array_merge(['search' => null, 'q' => null, 'category' => null], $fields);
        if ($fields['category']) {
            $category = CategoryLaw::find($fields['category']);
            if ($category) {
                return redirect()->route('questions.category', ['category' => $category, 'q' => $fields['q'], 'search' => 'y']);
            }
        }
        if ($fields['q'] && is_numeric($fields['q'])) {
            $question = Question::find(intval($fields['q']));
            if ($question) {
                return redirect()->route('question.view', ['question' => $question]);
            }
        }

        if($fields['q']) {
            $questions = $this->questions->paginateBySearch($fields['q']);
        }
        else {
            $questions = $this->questions->paginate();
        }

        return $questions;
    }

    /**
     * Отображения списка всех вопросов постранично.
     * @return \Illuminate\Http\Response
     */
    public function questions(Request $request)
    {
        $questions = $this->getSearchQuestions($request->all());
        if ($questions instanceof RedirectResponse) {
            return $questions;
        }

        return view('question.index', [
            'questions' => $questions,
            'search' => true,
            'formVars' => $this->getFormVars(),
        ]);
    }

    /**
     * Вопросы категории права.
     * @param  CategoryLaw $category
     * @return \Illuminate\Http\Response
     */
    public function questionsCategory(Request $request, CategoryLaw $category)
    {
        if (Auth::check()) {
            $this->authorize('view', $category);
        }
        elseif($category->status == CategoryLaw::UNPUBLISHED) {
            return abort(404);
        }

        $questions = $this->getSearchQuestions($request->all());
        if ($questions instanceof RedirectResponse) {
            return $questions;
        }

        $parents = [];
        if ($category->parent) {
            $parents[] = [
                'name' => $category->parent->name,
                'url' => route('questions.category', ['category' => $category->parent]),
            ];
        }
        return view('question.index', [
            'questions' => $questions,
            'filtered' => [
                'title' => $category->name,
                'description' => $category->description,
                'type' => 'category-law',
                'parents' => $parents,
            ],
            'categoryLaw' => $category,
            'formVars' => $this->getFormVars(),
        ]);
    }

    /**
     * Вопросы темы.
     * @param  Theme $theme
     * @return \Illuminate\Http\Response
     */
    public function questionsTheme(Theme $theme)
    {
        if (Auth::check()) {
            $this->authorize('view', $theme);
        }
        elseif($theme->status == Theme::UNPUBLISHED) {
            return abort(404);
        }

        $categoryLaw = $theme->categoryLaw;
        $parents = [$categoryLaw];
        $parentCategoryLaw = $categoryLaw;
        while ($parentCategoryLaw = $parentCategoryLaw->parent) {
            if ($parentCategoryLaw->parent_id) {
                $parents[] = [
                    'name' => $parentCategoryLaw->name,
                    'url' => route('questions.category', ['category' => $parentCategoryLaw]),
                ];
            }
        }
        if (count($parents) > 1) {
            $parents = array_reverse($parents);
        }
        return view('question.index', [
            'questions' => $this->questions->paginateByTheme($theme),
            'filtered' => [
                'title' => $theme->name,
                'description' => $theme->description,
                'type' => 'theme',
                'parents' => $parents,
            ],
            'categoryLaw' => $categoryLaw,
            'formVars' => $this->getFormVars(),
        ]);
    }

    /**
     * Вопросы из города.
     * @param  City $city
     * @return \Illuminate\Http\Response
     */
    public function questionsCity(City $city)
    {
        if (Auth::check()) {
            $this->authorize('view', $city);
        }
        elseif($city->status == City::UNPUBLISHED) {
            return abort(404);
        }

        return view('question.index', [
            'questions' => $this->questions->paginateByCity($city),
            'filtered' => [
                'title' => $city->name,
                'description' => $city->description,
                'type' => 'city',
                'parents' => [],
            ],
            'city' => $city,
            'formVars' => $this->getFormVars(),
        ]);
    }

    /**
     * Вопросы пользователя.
     * @param  User $user
     * @return \Illuminate\Http\Response
     */
    public function questionsUser(User $user)
    {
        $this->authorize('questions-user', Question::class);

        return view('question.index', [
            'questions' => $this->questions->paginateByUser($user),
            'filtered' => [
                'title' => $user->firstname,
                'description' => '',
                'type' => 'user',
                'parents' => [],
            ],
            'user' => $user,
            'formVars' => $this->getFormVars(),
        ]);
    }

    /**
     * Переадресация на страницу вопроса.
     * @param  intger $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function redirectToQuestion($id)
    {
        $question = Question::findOrFail($id);
        return redirect()->route('question.view', ['question' => $question]);
    }

    /**
     * Отображение вопроса.
     * @param  Question $question
     * @return \Illuminate\Http\Response
     */
    public function view(Question $question)
    {
        if (Auth::guest()) {
            if (!QuestionPolicy::viewMinimal($question)) {
                abort(403, 'Unauthorized');
            }
        }
        else {
            $this->authorize('view', $question);
        }

        $question->load('answers.to', 'answers.likes', 'answers.file', 'answers.file.parent', 'answers.file.owner', 'answers.answers', 'answers.answers.file', 'answers.from', 'answers.from.user', 'answers.from.user.photo', 'answers.from.user.city', 'answers.from.user.questions', 'answers.from.user.documents', 'answers.from.user.calls', 'answers.from.answers', 'answers.from.qanswers', 'answers.complaints', 'answers.clarifies', 'expertises.lawyer', 'expertises.lawyer.user');

        return view('question.view', [
            'question' => $question,
            'expertises' => $this->expertises->getMessagesForQuestion($question),
        ]);
    }

    /**
     * Форма добавления вопроса.
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function createForm(Request $request)
    {
        if (!Auth::guest()) {
            $this->authorize('create', Question::class);
        }

        return view('question.create', [
            'formVars' => $this->getFormVars(),
        ]);
    }

    /**
     * Создания вопрса.
     * @param  QuestionRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function create(QuestionRequest $request)
    {
        $question = new Question($request->except('firstname', 'email', 'telephone'));
        // получение или создание юзера
        $result = $this->getOrCreateUserForService($request, 'create', Question::class);
        $question->user()->associate($result['user']);
        // можно сразу публиковать?
        if ($result['user']->can('publish', Question::class)) {
            $question->status = Question::STATUS_IN_WORK;
        }
        // сохранение с генерацией события
        if ($question->save()) {
            event(new QuestionCreate($question, $result['user'], $result['params']));
        }
        // загрузка файла
        if ($request->hasFile('file') && $file = $this->saveRequestFile($request->file('file'), config('site.question.file.directory', 'private/questions'))) {
            $fileModel = new File(['file' => $file, 'field' => 'file']);
            $fileModel->user()->associate($result['user']);
            $question->file()->save($fileModel);
        }

        return redirect()->route('question.view', ['question' => $question])->with('success', trans('question.message.created'));
    }

    /**
     * Обновление пользователем.
     * @param  Request $request
     * @param  integer  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $question = Question::findOrFail($id);

        $this->authorize('update', $question);

        $this->validate($request, [
            'description' => 'required|string|max:' . config('site.question.max_text', 5000),
        ]);

        $question->update($request->only('description'));

        return response()->json([
            'description' => $question->description,
            'messages' => [
                'success' => trans('question.message.updated'),
            ],
        ]);
    }

    /**
     * Отправка на экспертизу.
     * @param  integer $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function expertise($id)
    {
        // получение вопроса и проверка доступа
        $question = Question::findOrFail($id);
        
        $this->authorize('onexpertise', $question);

        $user = Auth::user();

        // Инициатор
        $expertise = new Expertise(['message' => null]);
        $expertise->question()->associate($question);
        $expertise->lawyer()->associate($user->lawyer);
        $expertise->type = Expertise::TYPE_OWNER;
        $expertise->save();

        // кроме экспертов ответивших на вопрос.
        $noAnswered = $question->load('answers.from')->answers->filter(function($item) use ($user) {
            return $item->from->expert;
        })->pluck('from_id')->unique()->toArray();
        // Создание экспертов.
        $experts = Lawyer::inRandomOrder()->where('expert', 1)->whereNotIn('id', $noAnswered)->take(config('site.expertise.expert_count', 3))->get();
        foreach ($experts as $expert) {
            $exp = new Expertise(['message' => null]);
            $exp->type = Expertise::TYPE_EXPERT;
            $exp->question()->associate($question);
            $exp->lawyer()->associate($expert);
            $exp->save();
        }

        // изменение статуса
        $question->status = Question::STATUS_EXPERTISE;
        if ($question->save()) {
            event(
                new QuestionExpertise($question, $user, [
                    'expertise' => $expertise,
                    'experts' => $experts,
                ])
            );
        }

        return response()->json([
            'expertises' => view('expertise.question', ['question' => $question->load('expertises', 'expertises.lawyer', 'expertises.lawyer.user'), 'expertises' => $this->expertises->getMessagesForQuestion($question)])->render(),
            'messages' => [
                'success' => trans('expertise.message.question_expertise'),
            ],
        ]);
    }

    /**
     * Закрытие экспертизы.
     * @param  integer $id
     * @return \Illuminate\Http\Response
     */
    public function expertiseClose($id)
    {
        $question = Question::findOrFail($id);
        
        $this->authorize('admin', User::class);

        $user = Auth::user();

        $question->status = Question::STATUS_COMPLETED;
        $question->save();

        return response()->json([
            'redirect' => route('question.view', ['question' => $question]),
        ]);
    }

    /**
     * Список вопрос для админа.
     * @param  QuestionsAdminFilterRequest $request
     * @return \Illuminate\Http\Response
     */
    public function questionsAdmin(QuestionsAdminFilterRequest $request)
    {
        $this->authorize('admin', User::class);

        $fields = ['id', 'title', 'city_id', 'sticky', 'category_law_id', 'status'];
        $questions = Helper::getRequestModel(
            Question::class,
            $request->all(),
            $fields,
            [
                'title' => [
                    'condition' => 'like',
                    'value' => "%:value%",
                ],
            ]
        );
        $questions->with('categoryLaw', 'city');

        return view('question.admin.index', [
            'questions' => $questions->paginate(config('admin.page', 20))->appends($request->only($fields)),
            'formVars' => $this->getFormVars(),
            'request' => $request,
        ]);
    }

    /**
     * Форма бновления вопроса для админа.
     * @param  Request $request
     * @param  integer $id
     * @return \Illuminate\Http\Response
     */
    public function updateFormAdmin($id)
    {
        $question = Question::findOrFail($id);

        return view('question.admin.update', [
            'formVars' => $this->getFormVars(),
            'question' => $question,
        ]);
    }

    /**
     * Обновление вопроса админом.
     * @param  QuestionUpdateRequest $request
     * @param  integer               $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateAdmin(QuestionUpdateRequest $request, $id)
    {
        $question = Question::findOrFail($id);
        $question->fill($request->all());
        $question->status = $request->input('status');
        $question->sticky = $request->input('sticky');
        $question->save();

        return redirect()->route('question.update.form.admin', ['id' => $question->id])->with('success', trans('question.message.updated'))->with('updating', true);
    }

    /**
     * Отображение формы подтвержение удаления.
     * @param  integer $id
     * @return \Illuminate\Http\Response
     */
    public function deleteFormAdmin($id)
    {
        $question = Question::findOrFail($id);

        return view('question.admin.delete.admin', ['question' => $question]);
    }

    /**
     * Удаление вопроса.
     * @param  integer $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteAdmin($id)
    {
        $question = Question::findOrFail($id);
        $question->delete();

        return redirect()->route('questions.admin')->with('warning', trans('question.message.deleted'))->with('updating', true);
    }

}
