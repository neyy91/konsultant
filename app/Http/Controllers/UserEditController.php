<?php

namespace App\Http\Controllers;

use Auth;
use Session;
use Hash;
use Debugbar;
use Illuminate\Http\Request;
use Illuminate\Contracts\Auth\Authenticatable;

use App\Helper;
use App\Libs\FileHelper;
use App\Libs\UserEmailChange;
use App\Http\Requests;
use App\Http\Requests\User\PersonalEditUserRequest;
use App\Http\Requests\User\PhotoEditUserRequest;
use App\Http\Requests\User\ContactsEditUserRequest;
use App\Http\Requests\User\ChatEditUserRequest;
use App\Http\Requests\User\ExperienceEditUserRequest;
use App\Http\Requests\User\EmailPasswordEditUserRequest;
use App\Http\Requests\User\AdvancedEditUserRequest;
use App\Http\Requests\User\HonorsEditUserRequest;
use App\Http\Requests\User\EducationEditUserRequest;
use App\Http\Requests\User\EducationFileEditUserRequest;
use App\Http\Requests\User\NotificationsEditUserRequest;
use App\Http\Controllers\UserController;
use App\Repositories\CityRepository;
use App\Repositories\UserRepository;
use App\Models\User;
use App\Models\Lawyer;
use App\Models\CategoryLaw;
use App\Models\File;
use App\Models\Notification;
use App\Models\Education;


/**
 * Редактирование данных пользователя/юриста.
 */
class UserEditController extends Controller
{

    use FileHelper,
        UserEmailChange;

    /**
     * Репозиторий городов.
     * @var CityRepository
     */
    protected $cities;

    /**
     * Репозиторий городов.
     * @var UserRepository
     */
    protected $users;

    /**
     * Конструктор.
     * @param CityRepository $cities
     * @param UserRepository $users
     */
    public function __construct(CityRepository $cities, UserRepository $users)
    {
        $this->middleware('auth');
        
        $this->cities = $cities;
        $this->users = $users;
    }

    /**
     * Получение данных для формы.
     * @return array
     */
    public function getFormVars()
    {
        $formVars = [];
        $formVars['cities'] = $this->cities->getList();
        $formVars['genders'] = User::getGenders();
        $formVars['statuses'] = Lawyer::getStatuses();
        $formVars['years'] = Education::getYears();
        return $formVars;
    }

    /**
     * Перенаправление на основную.
     * @return \Illuminate\Http\RedirectResponse
     */
    public function index()
    {
        return redirect()->route('user.edit.personal');
    }

    /**
     * Форма редактирования основных(персональных) данных.
     * @return \Illuminate\Http\Response
     */
    public function personalForm(Authenticatable $user)
    {
        $current = UserRepository::TAB_PERSONAL;
        $this->authorize('edit', [User::class, $current]);

        return view('user.edit.index', [
            'user' => $user,
            'tabs' => $this->users->getEditList(),
            'formVars' => $this->getFormVars(),
            'current' => $current,
        ]);
    }

    /**
     * Сохраниение основных(персональных) данных.
     * @param  PersonalEditUserRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function personal(PersonalEditUserRequest $request)
    {
        $current = UserRepository::TAB_PERSONAL;
        $this->authorize('edit', [User::class, $current]);

        $user = Auth::user();
        $user->update($request->all());
        if ($request->input('status') && $user->lawyer) {
            $user->lawyer->update($request->only('status'));
        }
        return response()->json([
            'messages' => [
                'success' => trans('user.message.saved')
            ],
        ]);
    }

    /**
     * Форма редактирования фото.
     * @return \Illuminate\Http\Response
     */
    public function photoForm()
    {
        $current = UserRepository::TAB_PHOTO;
        $this->authorize('edit', [User::class, $current]);

        $user = Auth::user();
        return view('user.edit.index', [
            'user' => $user,
            'tabs' => $this->users->getEditList(),
            'current' => 'photo',
        ]);
    }

    /**
     * Сохраниение фото.
     * @param  PersonalEditUserRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function photo(PhotoEditUserRequest $request)
    {
        $current = UserRepository::TAB_PHOTO;
        $this->authorize('edit', [User::class, $current]);

        if ($request->hasFile('photo') && $photoPath = $this->saveRequestFile($request->file('photo'), config('site.user.photo.directory', 'public/photos'))) {
            $user = Auth::user();
            if ($user->photo) {
                $user->photo->delete();
            }
            $user->photo = $user->photo()->create(['file' => $photoPath, 'field' => 'photo']);
        }
        return view('user.edit.photo_iframe',[
            'user' => $user,
            'messages' => [
                'success' => trans('user.message.photo_uploaded')
            ],
        ]);
    }

    /**
     * Форма редактирования контактов.
     * @return \Illuminate\Http\Response
     */
    public function contactsForm()
    {
        $current = UserRepository::TAB_CONTACTS;
        $this->authorize('edit', [User::class, $current]);

        $user = Auth::user();
        return view('user.edit.index', [
            'user' => $user,
            'tabs' => $this->users->getEditList(),
            // 'formVars' => $this->getFormVars(),
            'current' => 'contacts',
        ]);
    }

    /**
     * Сохраниение контактов.
     * @param  ContactsEditUserRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function contacts(ContactsEditUserRequest $request)
    {
        $current = UserRepository::TAB_CONTACTS;
        $this->authorize('edit', [User::class, $current]);

        $user = Auth::user();
        if ($user->lawyer) {
            $lawyer = $user->lawyer;
            $fields = ['contactphones', 'contactemail', 'fax', 'site', 'skype'];
            foreach ($fields as $field) {
                $lawyer->$field = $request->input($field);
            }
            $lawyer->save();
            $messages = [
                'success' => trans('user.message.saved'),
            ];
        }
        else {
            $messages = [
                'danger' => trans('user.message.not_lawyer'),
            ];
        }
        return response()->json([
            'messages' => $messages,
        ]);
    }

    /**
     * Форма редактирования настройки чата и звонков.
     * @return \Illuminate\Http\Response
     */
    public function chatForm()
    {
        $current = UserRepository::TAB_CHAT;
        $this->authorize('edit', [User::class, $current]);

        $user = Auth::user();
        $formVars = [
            'timezones' => trans('timezone'),
            'linebreaks' => User::getLinebreaks(),
            'times' => Helper::floatToTime(Helper::times()),
        ];
        return view('user.edit.index', [
            'user' => $user,
            'tabs' => $this->users->getEditList(),
            'formVars' => $formVars,
            'current' => 'chat',
        ]);
    }

    /**
     * Сохраниение настройки чата и звонков.
     * @param  ChatEditUserRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function chat(ChatEditUserRequest $request)
    {
        $current = UserRepository::TAB_CHAT;
        $this->authorize('edit', [User::class, $current]);

        $user = Auth::user();
        // телефон юриста
        if ($telephone = $request->input('telephone')) {
            $user->telephone = $telephone;
        }
        // телефон юриста
        $lawyer = $user->lawyer;
        if ($lawyer) {
            $lawyer->fill($request->all());
            $lawyer->save();
        }
        $user->save();

        return response()->json([
            'messages' => [
                'success' => trans('user.message.saved'),
            ],
        ]);
    }

    /**
     * Форма редактирования опыта.
     * @return \Illuminate\Http\Response
     */
    public function experienceForm()
    {
        $current = UserRepository::TAB_EXPERIENCE;
        $this->authorize('edit', [User::class, $current]);

        $user = Auth::user();
        $formVars = [
            'specializations' => CategoryLaw::with('childs')->whereNull('parent_id')->get(),
            'experiences' => Lawyer::getExperiences(),
        ];
        // dd($user->lawyer->specializations->toArray());
        return view('user.edit.index', [
            'user' => $user,
            'tabs' => $this->users->getEditList(),
            'formVars' => $formVars,
            'current' => 'experience',
        ]);
    }

    /**
     * Сохраниение данных о опыте.
     * @param  ExperienceEditUserRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function experience(ExperienceEditUserRequest $request)
    {
        $current = UserRepository::TAB_EXPERIENCE;
        $this->authorize('edit', [User::class, $current]);

        $user = Auth::user();
        $lawyer = $user->lawyer;
        $messages = [];
        if ($lawyer) {
            $lawyer->update($request->only(['companyname', 'position', 'experience']));
        }
        $spec = $request->input('specialization');
        if ($spec && is_array($spec)) {
            $lawyer->specializations()->sync($spec);
        }
        return response()->json([
            'messages' => [
                'success' => trans('user.message.saved'),
            ],
        ]);
    }

    /**
     * Форма редактирования опыта.
     * @return \Illuminate\Http\Response
     */
    public function emailPasswordForm()
    {
        $current = UserRepository::TAB_EMAIL_PASSWORD;
        $this->authorize('edit', [User::class, $current]);

        $user = Auth::user();
        return view('user.edit.index', [
            'user' => $user,
            'tabs' => $this->users->getEditList(),
            'current' => 'email_password',
        ]);
    }

    /**
     * Сохраниение email и пароля.
     * @param  EmailPasswordEditUserRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function emailPassword(EmailPasswordEditUserRequest $request)
    {
        $current = UserRepository::TAB_EMAIL_PASSWORD;
        $this->authorize('edit', [User::class, $current]);

        $user = Auth::user();
        $messages = [];
        $jq = [];
        $password = $request->input('current_password');
        if ($password && !Hash::check($password, $user->getAuthPassword())) {
            return response()->json([
                'messages' => [
                    'danger' => trans('user.message.password_wrong'),
                ],
            ]);
        }
        // изменения email
        $email = $request->input('email');
        if ($email && $email != $user->email) {
            $this->requestChangeUserEmail($email);
            $messages['info'][] = trans('user.message.email_change_url_send');
        }
        // изменения пароля
        if ($new_password = $request->input('new_password')) {
            $user->fill([
                'password' => Hash::make($new_password),
            ])->save();
            $messages['success'][] = trans('user.message.password_changed');
        }
        return response()->json([
            'password_empty' => true,
            'messages' => $messages,
        ]);
    }

    /**
     * Изменения email.
     * @param  string $token
     * @return Illuminate\Http\RedirectResponse
     */
    public function emailChange($token)
    {
        $current = UserRepository::TAB_EMAIL_PASSWORD;
        $this->authorize('edit', [User::class, $current]);

        if ($token && $this->changeUserEmailByToken($token)) {
            Session::flash('success', trans('user.message.email_changed'));
        }
        else {
            Session::flash('error', trans('user.message.email_changed'));
        }
        return redirect()->route('user.edit.email_password.form');
    }


    /**
     * Форма редактирования дополнительных опции.
     * @return \Illuminate\Http\Response
     */
    public function advancedForm()
    {
        $current = UserRepository::TAB_ADVANCED;
        $this->authorize('edit', [User::class, $current]);

        $user = Auth::user();
        return view('user.edit.index', [
            'user' => $user,
            'tabs' => $this->users->getEditList(),
            'current' => 'advanced',
        ]);
    }

    /**
     * Сохраниение дополнительных данных.
     * @param  AdvancedEditUserRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function advanced(AdvancedEditUserRequest $request)
    {
        $current = UserRepository::TAB_ADVANCED;
        $this->authorize('edit', [User::class, $current]);


        $user = Auth::user();
        $lawyer = $user->lawyer;
        if ($lawyer) {
            $lawyer->update($request->only(['costcall', 'costchat', 'costdocument', 'cost', 'aboutmyself']));
        }
        return response()->json([
            'messages' => [
                'success' => trans('user.message.saved'),
            ],
        ]);
    }

    /**
     * Форма добавления/удаления наград.
     * @return \Illuminate\Http\Response
     */
    public function honorsForm()
    {
        $current = UserRepository::TAB_HONORS;
        $this->authorize('edit', [User::class, $current]);

        $user = Auth::user();
        return view('user.edit.index', [
            'user' => $user,
            'tabs' => $this->users->getEditList(),
            'current' => 'honors',
        ]);
    }

    /**
     * Сохраниение диплома или награды.
     * @param  HonorsEditUserRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function honors(HonorsEditUserRequest $request)
    {
        $current = UserRepository::TAB_HONORS;
        $this->authorize('edit', [User::class, $current]);

        $user = Auth::user();
        $lawyer = $user->lawyer;
        if ($lawyer && $lawyer->honors && ($count = $lawyer->honors->count()) < config('site.user.honors.count', 4) && $request->hasFile('honor') && $honorPath = $this->saveRequestFile($request->file('honor'), config('site.user.honors.directory', 'public/honors'))) {
            $honor = $lawyer->honors()->create(['file' => $honorPath, 'field' => 'honor']);
            $count++;
        }
        return view('user.edit.honor_iframe',[
            'user' => $user,
            'honor' => $honor,
            'count' => $count,
            'messages' => [
                'success' => trans('user.message.honor_uploaded')
            ],
        ]);
    }

    /**
     * Удаление диплома или награды.
     * @param  File   $honor
     * @return \Illuminate\Http\JsonResponse
     */
    public function honorDelete(File $honor)
    {
        $current = UserRepository::TAB_HONORS;
        $this->authorize('edit', [User::class, $current]);

        $honor->delete();
        return response()->json([
            'messages' => [
                'success' => trans('user.message.honor_delete'),
            ],
        ]);
    }

    /**
     * Форма редактирования образования.
     * @return \Illuminate\Http\Response
     */
    public function educationForm()
    {
        $current = UserRepository::TAB_EDUCATION;
        $this->authorize('edit', [User::class, $current]);

        $user = Auth::user();
        return view('user.edit.index', [
            'user' => $user,
            'tabs' => $this->users->getEditList('lawyer'),
            'formVars' => $this->getFormVars(),
            'current' => 'education',
        ]);
    }

    /**
     * Сохраниение контактов.
     * @param  EducationEditUserRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function education(EducationEditUserRequest $request)
    {
        $current = UserRepository::TAB_EDUCATION;
        $this->authorize('edit', [User::class, $current]);

        $user = Auth::user();
        $lawyer = $user->lawyer;
        $messages = [];
        if ($lawyer->education) {
            if ($lawyer->education->checked) {
                $messages['warning'] = trans('user.message.not_change_education');
            }
            else {
                $lawyer->education->update($request->all());
                $messages['success'] = trans('user.message.education_info_updated');
            }
        }
        else {
            $lawyer->education()->create($request->all());
            $messages['success'] = trans('user.message.education_added');
        }
        
        return response()->json([
            'action_text' => trans('form.action.update_info'),
            'messages' => $messages,
        ]);
    }

    /**
     * Загрузка файла для подтверждения образования.
     * @param  EducationFileEditUserRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function educationFile(EducationFileEditUserRequest $request)
    {
        $current = UserRepository::TAB_EDUCATION;
        $this->authorize('edit', [User::class, $current]);

        $user = Auth::user();
        if ($user->lawyer && ($education = $user->lawyer->education) && $request->hasFile('file') && $filePath = $this->saveRequestFile($request->file('file'), config('site.user.education.file.directory', 'private/education'))) {
            if ( $education->file && $education->file->count() != 0) {
                $education->file->delete();
            }
            $file = $education->file()->create(['file' => $filePath, 'field' => 'file']);
        }
        return view('user.edit.education_iframe',[
            'user' => $user,
            'file' => $file ? $file : null,
            'messages' => [
                'success' => trans('user.message.image_diploma_success_uploaded')
            ],
        ]);
    }

    /**
     * Форма редактирования образования.
     * @return \Illuminate\Http\Response
     */
    public function notificationsForm()
    {
        $current = UserRepository::TAB_NOTIFICATIONS;
        $this->authorize('edit', [User::class, $current]);

        $user = Auth::user();
        $formVars = [
            'notifications' => [],
            'notifi_types' => Notification::getTypes(),
        ];
        foreach (Notification::$typeGroup[$user->type] as $group) {
            $formVars['notifications'][$group] = Notification::$group[$group];
        }
        return view('user.edit.index', [
            'user' => $user,
            'tabs' => $this->users->getEditList(),
            'formVars' => $formVars,
            'current' => 'notifications',
        ]);
    }

    /**
     * Сохраниение уведомления.
     * @param  NotificationsEditUserRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function notifications(NotificationsEditUserRequest $request)
    {
        $current = UserRepository::TAB_NOTIFICATIONS;
        $this->authorize('edit', [User::class, $current]);

        $user = Auth::user();
        $userNotifies = $user->notifications->keyBy('type');
        $notificationTypes = $this->users->getUserNotificationTypes($user);
        $reqNotifies = $request->input('notifications');
        foreach ($notificationTypes as $type) {
            $userNotifi = isset($userNotifies[$type]);
            $reqNotifi = isset($reqNotifies[$type]) && $reqNotifies[$type];
            if ($userNotifi && !$reqNotifi) {
                $user->notifications()->where(['type' => $type])->delete();
            }
            elseif(!$userNotifi && $reqNotifi) {
                $user->notifications()->create(['type' => $type]);
            }
        }
        return response()->json([
            'messages' => [
                'success' => trans('user.message.notifications_saved'),
            ],
        ]);
    }


    /**
     * Редактирование сотрудников.
     * @param  Authenticatable $user
     * @return \Illuminate\Http\Response
     */
    public function employeesForm()
    {
        $current = UserRepository::TAB_EMPLOYEES;
        $this->authorize('edit', [User::class, $current]);
        $this->authorize('companyOwner', [Lawyer::class]);

        $user = Auth::user();

        return view('user.edit.index', [
            'user' => $user,
            'employees' => $user->lawyer->companyowner ? $this->users->getAllEmployees($user->lawyer) : [],
            'tabs' => $this->users->getEditList(),
            'current' => 'employees',
        ]);
    }

    /**
     * Проверка id юриста.
     * @param  Request         $request
     * @param  Authenticatable $user
     * @return void
     */
    protected function employeeValidate(Request $request)
    {
        $current = UserRepository::TAB_EMPLOYEES;
        $this->authorize('edit', [User::class, $current]);

        $user = Auth::user();
        $this->validate($request, [
            'id' => 'required|not_in:' . implode(',', $this->users->getAllEmployeeIds($user->lawyer)) . '|exists:' . (new Lawyer)->getTable() . ',id,companyowner,0',
        ], trans('user.validation.employees'));

    }

    /**
     * Добавления сотрудника.
     * @param  Authenticatable $user
     * @param  Request         $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function employeesAdd(Request $request)
    {
        $current = UserRepository::TAB_EMPLOYEES;
        $this->authorize('edit', [User::class, $current]);
        $this->authorize('companyOwnerActive', [Lawyer::class]);

        $user = Auth::user();
        $this->employeeValidate($request, $user);
        $employee = Lawyer::find($request->input('id'));
        $employee->company()->associate($user->lawyer->company);
        $employee->save();

        return response()->json([
            'html' => view('user.edit.employee_list', [
                'employees' => $this->users->getAllEmployees($user->lawyer),
            ])->render(),
            'messages' => [
                'success' => trans('user.message.employee_success_add'),
            ],
        ]);
    }

    /**
     * Удаление сотрудника.
     * @param  Authenticatable $user
     * @param  Request         $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function employeesDelete(Lawyer $employee, Request $request)
    {
        $current = UserRepository::TAB_EMPLOYEES;
        $this->authorize('edit', [User::class, $current]);
        $this->authorize('companyOwnerActive', [Lawyer::class]);

        $user = Auth::user();
        $messages = [];
        if ($user->lawyer->company_id == $employee->company_id) {
            $employee->company()->dissociate();
            $employee->save();
            $messages['info'] = trans('user.message.employee_success_remove');
        }
        else {
            $messages['danger'] = trans('user.message.employee_not_in_company');
        }

        return response()->json([
            'html' => view('user.edit.employee_list', [
                'employees' => $this->users->getAllEmployees($user->lawyer),
            ])->render(),
            'messages' => $messages,
        ]);
    }

}
