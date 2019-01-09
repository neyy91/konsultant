<?php 

namespace App\Repositories;

use Auth;

use App\Models\User;
use App\Models\Lawyer;
use App\Models\CategoryLaw;
use App\Models\City;
use App\Models\Bookmark;
use App\Models\BookmarkCategory;
use App\Models\Question;
use App\Models\Specialization;
use App\Models\Notification;
use App\Models\Subscribe;


/**
* Репозиторий для пользователей.
*/
class UserRepository
{

    /**
     * @var integer
     */
    const DEFAULT_PAGE = 10;
    const DEFAULT_TAKE = 5;

    /**
     * Табы.
     */
    const TAB_PERSONAL = 'personal';
    const TAB_PHOTO = 'photo';
    const TAB_CHAT = 'chat';
    const TAB_EMAIL_PASSWORD = 'email_password';
    const TAB_NOTIFICATIONS = 'notifications';
    const TAB_CONTACTS = 'contacts';
    const TAB_EDUCATION = 'education';
    const TAB_EXPERIENCE = 'experience';
    const TAB_ADVANCED = 'advanced';
    const TAB_HONORS = 'honors';
    const TAB_EMPLOYEES = 'employees';

    /**
     * @var array
     */
    static $with = [
        'bookmarks' => ['entity', 'category'],
        'lawyers' => ['specializations', 'user', 'user.photo', 'user.city'],
    ];

    /**
     * Тип уведомлений для пользователя.
     */
    public function getUserNotificationTypes(User $user)
    {
        $types = [];
        if (!isset(Notification::$typeGroup[$user->type])) {
            return $types;
        }
        foreach (Notification::$typeGroup[$user->type] as $group) {
            $types = array_merge($types, Notification::$group[$group]);
        }
        return $types;
    }

    /**
     * Установка оповещения для пользователя.
     * @param  User   $user
     */
    public function initialNotificationsUser(User $user)
    {
        $types = $this->getUserNotificationTypes($user);
        foreach ($types as $type) {
            $notifi = new Notification(['type' => $type]);
            $notifi->user()->associate($user);
            $notifi->save();
        }
    }

    /**
     * Список пункто для редактирования.
     * @param  string|null $type
     * @return array|null
     */
    public static function getEditList($type = null)
    {
        if ($type === null) {
            $type = Auth::user()->type;
        }
        $tabs = [];
        
        $tabs[User::TYPE_USER] = [
            self::TAB_PERSONAL, self::TAB_PHOTO, self::TAB_CHAT, self::TAB_EMAIL_PASSWORD, self::TAB_NOTIFICATIONS
        ];

        $tabs[User::TYPE_LAWYER] = [
            self::TAB_PERSONAL, self::TAB_PHOTO, self::TAB_CONTACTS, self::TAB_EDUCATION, self::TAB_CHAT, self::TAB_EXPERIENCE, self::TAB_EMAIL_PASSWORD, self::TAB_ADVANCED, self::TAB_HONORS, self::TAB_NOTIFICATIONS
        ];

        $tabs[User::TYPE_COMPANY] = $tabs[User::TYPE_LAWYER];
        array_splice($tabs[User::TYPE_COMPANY], 3, 0, [self::TAB_EMPLOYEES]);

        $tabs[User::TYPE_ADMIN] = $tabs[User::TYPE_USER];
        return isset($tabs[$type]) ? $tabs[$type] : null;
    }

    /**
     * Список юристов постранично.
     * @param  integer|null $city
     * @param  integer|null $category
     * @param  integer|null $page
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function paginateLawyers($city = null, $category = null, $page = null)
    {
        $tlawyer = (new Lawyer)->getTable();
        $lawyers = Lawyer::select($tlawyer . '.*')->with(self::$with['lawyers']);
        if ($city) {
            $city = City::findOrFail($city);
            $tUser = (new User)->getTable();
            $lawyers->join($tUser, $tUser . '.id', '=', $tlawyer . '.user_id')->where($tUser . '.status', true)->where($tUser . '.city_id', $city->id);
        }
        if ($category) {
            $category = CategoryLaw::findOrFail($category);
            $tspec = (new Specialization)->getTable();
            $lawyers->join($tspec, $tspec . '.lawyer_id', '=', $tlawyer . '.id')->where($tspec . '.category_law_id', $category->id);
        } 
        $page = $page ? $page : config('site.lawyers.page', self::DEFAULT_PAGE);
        return $lawyers->orderBy('rating', 'desc')->paginate($page);
    }

    /**
     * Список закладок постранично.
     * @param  Lawyer       $lawyer
     * @param  integer|null $category
     * @param  integer|null $page
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function paginateBookmarkQuestions(Lawyer $lawyer, $category = null, $page = null)
    {
        $page = $page ? $page : config('site.user.bookmark.page', self::DEFAULT_PAGE);
        $tbm = (new Bookmark)->getTable();
        $tqst = (new Question)->getTable();
        $questions = Question::select($tqst . '.*')->join($tbm . ' as bookmarks', 'bookmarks.question_id', '=', $tqst . '.id')->where('bookmarks.lawyer_id', $lawyer->id)->published()->defaultWith()->orderBy('bookmarks.created_at', 'desc');
        if ($category) {
            $questions->where('bookmarks.category_id', $category);
        }
        return $questions->paginate($page);
    }

    /**
     * Категории закладок.
     * @param Lawyer $lawyer
     * @return array
     */
    public function bookmarkCategories(Lawyer $lawyer)
    {
        return BookmarkCategory::whereNull('lawyer_id')->orWhere('lawyer_id', $lawyer->id)->orderBy('name', 'asc')->get()->all();
    }

    /**
     * Количество закладок.
     * @param  Lawyer       $lawyer
     * @param  integer|null $category [description]
     * @return integer;
     */
    public function bookmarksCount(Lawyer $lawyer, $category = null)
    {
        if ($category) {
            return $lawyer->bookmarks->where('category_id', $category)->count();
        }
        else {
            return $lawyer->bookmarks->count();
        }
    }


    /**
     * Получение сотрудников.
     * @param  Lawyer $companyOwner
     * @return array
     */
    public function getAllEmployees(Lawyer $companyOwner)
    {
        return Lawyer::active()->where('companyowner', 0)->where('company_id', '=', $companyOwner->company_id)->get()->all();
    }

    public function getAllEmployeeIds(Lawyer $companyOwner)
    {
        return Lawyer::where('companyowner', 0)->where('company_id', '=', $companyOwner->company_id)->get()->pluck('id')->all();
    }

    public function getSubscibe(User $user, $owner)
    {
        return Subscribe::byUser($user)->byOwner($owner)->first();
    }

}