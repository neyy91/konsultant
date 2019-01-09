<?php
namespace App\Http\Controllers;

use Auth;
use Validator;
use Hash;
use Illuminate\Http\Request;

use App\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Http\Requests\Question\QuestionsUserFilterRequest;
use App\Http\Requests\Call\CallsUserFilterRequest;
use App\Http\Requests\Document\DocumentsUserFilterRequest;
use App\Http\Requests\User\UsersAdminFilterRequest;
use App\Http\Requests\User\UserAdminUpdateRequest;
use App\Repositories\QuestionRepository;
use App\Repositories\DocumentRepository;
use App\Repositories\CallRepository;
use App\Repositories\AnswerRepository;
use App\Repositories\CityRepository;
use App\Repositories\LikeRepository;
use App\Repositories\UserRepository;
use App\Repositories\CategoryLawRepository;
use App\Models\User;
use App\Models\Question;
use App\Models\Call;
use App\Models\Document;
use App\Models\Lawyer;
use App\Models\Bookmark;
use App\Models\BookmarkCategory;
use App\Models\City;
use App\Models\CategoryLaw;
use App\Models\Company;
use App\Models\Education;


/**
 * Контролер пользователей.
 */
class UserController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    /**
     * Репозиторий вопросов.
     * @var QuestionRepository
     */
    protected $questions;

    /**
     * Репозиторий документов.
     * @var DocumentRepository
     */
    protected $documents;

    /**
     * Репозиторий конультации телефону.
     * @var CallRepository
     */
    protected $calls;

    /**
     * Репозиторий ответов.
     * @var AnswerRepository
     */
    protected $answers;

    /**
     * Репозиторий городов.
     * @var CityRepository
     */
    protected $cities;

    /**
     * Репозиторий оценок.
     * @var LikeRepository
     */
    protected $likes;

    /**
     * Репозиторий пользователей.
     * @var UserRepository
     */
    protected $users;

    /**
     * Репозиторий категории права.
     * @var CategoryLawRepository
     */
    protected $categories;

    /**
     * Конструктор.
     * @param UserRepository $users
     * @param QuestionRepository $questions
     * @param DocumentRepository $documents
     * @param CallRepository $calls
     * @param AnswerRepository $answers
     * @param CityRepository $cities
     * @param LikeRepository $likes
     * @param CategoryLawRepository $categories
     * @return void
     */
    public function __construct(UserRepository $users, QuestionRepository $questions, DocumentRepository $documents, CallRepository $calls, AnswerRepository $answers, CityRepository $cities, LikeRepository $likes, CategoryLawRepository $categories)
    {
        $this->middleware('auth');
        
        $this->users = $users;
        $this->questions = $questions;
        $this->documents = $documents;
        $this->calls = $calls;
        $this->answers = $answers;
        $this->cities = $cities;
        $this->likes = $likes;
        $this->categories = $categories;
    }

    /**
     * Получение данных для формы.
     * @return array
     */
    public function getFormVars()
    {
        $formVars = [];
        $formVars['cities'] = $this->cities->getList();
        $perpage = config('site.user.perpages', [10, 20, 50, 100]);
        $formVars['perpages'] = array_combine($perpage, $perpage);
        $formVars['statuses'] = ['0' => trans('app.inactives'), '1' => trans('app.actives')];
        $formVars['lawyer_statuses'] = Lawyer::getStatuses();
        $formVars['types'] = User::getTypes();
        $formVars['education_years'] = Education::getYearsList();
        $formVars['genders'] = User::getGenders();
        return $formVars;
    }

    /**
     * Личный кабинет клиента.
     * @param  User   $user
     * @return \Illuminate\Http\Response
     */
    protected function dashboardUser(User $user)
    {
        $questions = $this->questions->takeByUser($user);
        $documents = $this->documents->takeByUser($user);
        $calls = $this->calls->takeByUser($user);
        return view('user.dashboard.user', [
            'user' => $user,
            'questions' => $questions,
            'documents' => $documents,
            'calls' => $calls,
        ]);
    }

    /**
     * Личный кабинет юриста
     * @param  User   $user
     * @return \Illuminate\Http\Response
     */
    protected function dashboardLawyer(Lawyer $lawyer)
    {
        $answers = $this->answers->takeByLawyer($lawyer);
        return view('user.dashboard.lawyer', [
            'lawyer' => $lawyer,
            'answers' => $answers,
        ]);
    }

    /**
     * Личный кабинет.
     * @return \Illuminate\Http\Response
     */
    public function dashboard()
    {
        $user = Auth::user();
        if ($user->lawyer) {
            return $this->dashboardLawyer($user->lawyer);
        }
        else {
            return $this->dashboardUser($user);
        }
    }

    /**
     * Все вопросы пользователя.
     * @param  QuestionsUserFilterRequest $request
     * @param  integer                    $id
     * @return \Illuminate\Http\Response
     */
    public function questions(QuestionsUserFilterRequest $request)
    {
        $this->authorize('client', User::class);

        $fields = ['city'];
        $questions = Helper::getRequestModel(
            Question::class,
            $request->all(),
            $fields,
            [
                'city' => [
                    'field' => 'city_id',
                ],
            ]
        );
        $questions->currentUser()->with('answers', 'city', 'city.region');
        $perpage = $request->input('perpage') ? $request->input('perpage') : config('site.user.perpages', [10, 20, 50, 100])[0];

        return view('user.question.index', [
            'questions' => $questions->paginate($perpage)->appends($request->only($fields)),
            'formVars' => $this->getFormVars(),
            'request' => $request,
        ]);
    }

    /**
     * Все вопросы пользователя.
     * @param  CallsUserFilterRequest $request
     * @param  integer                $id
     * @return \Illuminate\Http\Response
     */
    public function calls(CallsUserFilterRequest $request)
    {
        $this->authorize('client', User::class);

        $fields = ['city'];
        $calls = Helper::getRequestModel(
            Call::class,
            $request->all(),
            $fields,
            [
                'city' => [
                    'field' => 'city_id',
                ],
            ]
        );
        $calls->currentUser()->with('answers', 'city', 'city.region');
        $perpage = $request->input('perpage') ? $request->input('perpage') : config('site.user.perpages', [10, 20, 50, 100])[0];

        return view('user.call.index', [
            'calls' => $calls->paginate($perpage)->appends($request->only($fields)),
            'formVars' => $this->getFormVars(),
            'request' => $request,
        ]);
    }

    /**
     * Все вопросы пользователя.
     * @param  DocumentsUserFilterRequest $request
     * @param  integer                    $id
     * @return \Illuminate\Http\Response
     */
    public function documents(DocumentsUserFilterRequest $request)
    {
        $this->authorize('client', User::class);

        $fields = ['city'];
        $documents = Helper::getRequestModel(
            Document::class,
            $request->all(),
            $fields,
            [
                'city' => [
                    'field' => 'city_id',
                ],
            ]
        );
        $documents->currentUser()->with('answers', 'city', 'city.region');
        $perpage = $request->input('perpage') ? $request->input('perpage') : config('site.user.perpages', [10, 20, 50, 100])[0];

        return view('user.document.index', [
            'documents' => $documents->paginate($perpage)->appends($request->only($fields)),
            'formVars' => $this->getFormVars(),
            'request' => $request,
        ]);
    }

    public function bookmarks(Request $request, $category = null)
    {
        $this->authorize('bookmarks', Bookmark::class);
        
        $user = Auth::user();
        if ($category) {
            $category = BookmarkCategory::findOrFail($category);
        }

        return view('user.lawyer.bookmarks', [
            'lawyer' => $user->lawyer,
            'questions' => $this->users->paginateBookmarkQuestions($user->lawyer, $category ? $category->id : null),
            'categories' => $this->users->bookmarkCategories($user->lawyer),
            'category' => $category ? $category : null,
            'management' => $request->input('show') == 'management' ? true : false,
            'userRepository' => $this->users,
        ]);
    }

    /**
     * Список компании для админа.
     * @param UsersAdminFilterRequest $request
     * @return \Illuminate\Http\Response
     */
    public function usersAdmin(UsersAdminFilterRequest $request)
    {
        $fields = ['id', 'status', 'firstname', 'lastname', 'city'];
        $users = Helper::getRequestModel(
            User::class,
            $request->all(),
            $fields,
            [
                'firstname' => [
                    'condition' => 'like',
                    'value' => "%:value%",
                ],
                'lastname' => [
                    'condition' => 'like',
                    'value' => "%:value%",
                ],
                'city' => [
                    'field' => 'city_id',
                ],
            ],
            [
                'order' => ['lastname', 'asc'],
            ]
        );
        if ($type = $request->input('type')) {
            switch ($type) {
                case User::TYPE_USER:
                    $users->users();
                    break;
                case User::TYPE_LAWYER:
                    $users->lawyers();
                    break;
                case User::TYPE_COMPANY:
                    $users->companies();
                    break;
                case User::TYPE_ADMIN:
                    $users->admins();
                    break;
            }
        }

        $users->with('lawyer', 'roles', 'city');

        return view('user.admin.index', [
            'users' => $users->paginate(config('admin.page', 20))->appends($request->only($fields)),
            'formVars' => $this->getFormVars(),
            'request' => $request,
        ]);
    }

    /**
     * Страница редактировании компании.
     * @param  User $user
     * @return \Illuminate\Http\Response
     */
    public function updateFormAdmin(User $user)
    {
        return view('user.admin.update', [
            'user' => $user,
            'formVars' => $this->getFormVars(),
        ]);
    }

    /**
     * Обновление данных пользователя.
     * @param  UserAdminUpdateRequest $request
     * @param  User                   $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateAdmin(UserAdminUpdateRequest $request, User $user)
    {
        if (!Hash::check($request->input('password'), Auth::user()->getAuthPassword())) {
            return redirect()->route('user.update.admin', ['user' => $user])->with('danger', trans('user.message.password_wrong'));
        }
        $datas = $request->all();
        unset($datas['password']);
        if ($datas['new_password']) {
            $datas['password'] = Hash::make($datas['new_password']);
            unset($datas['new_password']);
        }
        
        $user->update($datas);

        return redirect()->route('user.update.admin', ['user' => $user, 'tab' => 'user'])->with('success', trans('user.message.user_updated'));
    }

    /**
     * Отображение формы подтвержение удаления.
     * @param  integer $id
     * @return \Illuminate\Http\Response
     */
    public function deleteFormAdmin($id)
    {
        $user = User::findOrFail($id);
        return view('user.admin.delete', ['user' => $user]);
    }

    /**
     * Удаление ответа.
     * @param  integer $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteAdmin($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return redirect()->route('users.admin')->with('warning', trans('user.message.user_deleted'))->with('updating', true);
    }

}
