<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;

use App\Helper;
use App\Libs\FileHelper;
use App\Libs\GetOrCreateUserForService;
use App\Http\Requests;
use App\Http\Requests\Call\CallRequest;
use App\Http\Requests\Call\CallUpdateRequest;
use App\Http\Requests\Call\CallsAdminFilterRequest;
use App\Repositories\CityRepository;
use App\Repositories\CallRepository;
use App\Events\Call\CallCreate;
use App\Models\Call;
use App\Models\City;
use App\Models\User;
use App\Models\File;


/**
 * Контролер консультации по телефону.
 */
class CallController extends Controller
{
    use FileHelper,
        GetOrCreateUserForService;

    /**
     * Репозиторий для работы с консультациями по телефону.
     * @var \App\Models\CallRepository
     */
    protected $calls;

    /**
     * Репозиторий для работы с городами.
     * @var \App\Models\CityRepository
     */
    protected $cities;

    /**
     * Конструктор.
     * @param CallRepository $calls
     * @param CityRepository $cities
     */
    public function __construct(CallRepository $calls, CityRepository $cities)
    {
        $this->calls = $calls;
        $this->cities = $cities;
    }

    /**
     * Получение данных для формы.
     * @return array
     */
    public function getFormVars()
    {
        $formVars = [];
        $formVars['statuses'] = Call::getStatuses();
        $formVars['cities'] = $this->cities->getList();
        return $formVars;
    }

    /**
     * Отображения списка всех звонков постранично.
     * @return \Illuminate\Http\Response
     */
    public function calls()
    {
        $this->authorize('list-call', Call::class);

        $calls = $this->calls->paginate();

        return view('call.index', [
            'calls' => $calls,
        ]);
    }

    /**
     * Список звонков для админа.
     * @param  CallsAdminFilterRequest $request
     * @return \Illuminate\Http\Response
     */
    public function callsAdmin(CallsAdminFilterRequest $request)
    {
        $calls = Helper::getRequestModel(
            Call::class,
            $request->all(),
            ['id', 'title', 'city_id', 'status'],
            [
                'title' => [
                    'condition' => 'like',
                    'value' => "%:value%",
                ],
            ]
        );

        return view('call.admin.index', [
            'calls' => $calls->paginate(config('admin.page', 20)),
            'formVars' => $this->getFormVars(),
            'request' => $request,
        ]);
    }

    /**
     * Консультациями по телефону из города.
     * @param  City $city
     * @return \Illuminate\Http\Response
     */
    public function callsCity(City $city)
    {
        $this->authorize('list-call', Call::class);

        return view('call.index', [
            'calls' => $this->calls->paginateByCity($city),
            'filtered' => [
                'title' => $city->name,
                'description' => $city->description,
                'type' => 'city',
                'parents' => [],
            ],
            'city' => $city,
        ]);
    }

    /**
     * Консультациями по телефону пользователя.
     * @param  City $city
     * @return \Illuminate\Http\Response
     */
    public function callsUser(User $user)
    {
        $this->authorize('calls-user', Call::class);

        return view('call.index', [
            'calls' => $this->calls->paginateByUser($user),
            'filtered' => [
                'title' => $user->firstname,
                'type' => 'user',
                'parents' => [],
            ],
            'city' => $user,
        ]);
    }

    /**
     * Переадресация на страницу заказа консультации по телефону.
     * @param  intger $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function redirectToCall($id)
    {
        $call = Call::findOrFail($id);
        return redirect()->route('call.view', ['call' => $call]);
    }

    /**
     * Отображение заказа консультации по телефону.
     * @param Request $request
     * @param  Call $call
     * @return \Illuminate\Http\Response
     */
    public function view(Request $request, Call $call)
    {
        $this->authorize('view', $call);

        if ($request->input('payed')) {
            return redirect()->route('call.view', ['call' => $call])->with('success', trans('pay.message.payment_success'));
        } elseif($request->input('failed')) {
            return redirect()->route('call', ['call' => $call])->with('error', trans('pay.message.failed'));
        }

        $call->load('answers.to', 'answers.likes', 'answers.file', 'answers.file.parent', 'answers.file.owner', 'answers.answers', 'answers.answers.file', 'answers.from', 'answers.from.user', 'answers.from.user.photo', 'answers.from.user.city', 'answers.from.user.documents', 'answers.from.user.calls', 'answers.from.answers', 'answers.from.qanswers', 'answers.complaints', 'answers.clarifies');
        return view('call.view', [
            'call' => $call,
        ]);
    }

    /**
     * Форма добавления заказа консультации по телефону.
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function createForm(Request $request)
    {
        if (!Auth::guest()) {
            $this->authorize('create', Call::class);
        }

        $call = new Call();
        $call->pay = Call::PAY_DEFAULT;

        return view('call.create', [
            'formVars' => $this->getFormVars(),
            'call' => $call,
        ]);
    }

    /**
     * Создания вопрса.
     * @param  CallRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function create(CallRequest $request)
    {
        $call = new Call($request->except('firstname', 'email', 'telephone'));
        // создать или получить пользователя и параметры
        $result = $this->getOrCreateUserForService($request, 'create', Call::class);
        $call->user()->associate($result['user']);
        // можно сразу публиковать?
        if ($result['user']->can('publish', Call::class)) {
            $call->status = Call::STATUS_IN_WORK;
        }
        // сохранение с генерацией события
        if ($call->save()) {
            event(new CallCreate($call, $result['user'], $result['params']));
        }
        // загрузка файла
        if ($request->hasFile('file') && $file = $this->saveRequestFile($request->file('file'), config('site.call.file.directory', 'private/calls'))) {
            $fileModel = new File(['file' => $file, 'field' => 'file']);
            $fileModel->user()->associate($result['user']);
            $call->file()->save($fileModel);
        }

        return redirect()->route('call.view', ['call' => $call])->with('success', trans('call.message.created'));
    }

    /**
     * Обновление заказа консультации по телефону. Доступ ограничен.
     * @param  Request $request
     * @param  integer $id
     * @return \Illuminate\Http\Response
     */
    public function updateFormAdmin(Request $request, $id)
    {
        $call = Call::findOrFail($id);
        return view('call.admin.update', [
            'formVars' => $this->getFormVars(),
            'call' => $call,
        ]);
    }

    /**
     * Обновление заказа консультации по телефону.
     * @param  CallRequest $request
     * @param  integer         $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateAdmin(CallUpdateRequest $request, $id)
    {
        $call = Call::findOrFail($id);
        $call->fill($request->all());
        $call->status = $request->input('status');
        $call->save();

        return redirect()->route('call.update.form.admin', ['id' => $call->id])->with('success', trans('call.message.updated'))->with('updating', true);
    }

    /**
     * Отображение формы подтвержение удаления.
     * @param  integer $id
     * @return \Illuminate\Http\Response
     */
    public function deleteFormAdmin($id)
    {
        $call = Call::findOrFail($id);
        return view('call.admin.delete', ['call' => $call]);
    }

    /**
     * Удаление заказа консультации по телефону.
     * @param  integer $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteAdmin($id)
    {
        $call = Call::findOrFail($id);
        $call->delete();
        return redirect()->route('calls.admin')->with('warning', trans('call.message.deleted'))->with('updating', true);
    }

}
