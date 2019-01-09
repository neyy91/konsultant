<?php

namespace App\Models;

use Str;
use Date;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use App\Libs\DateCreatedUpdated;

/**
 * Пользователи сайта.
 */
class User extends Authenticatable
{

    use DateCreatedUpdated,
        Notifiable;

    /**
     * Короткое имя для morphMap.
     */
    const MORPH_NAME = 'user';

    /**
     * Константы переноса строк.
     */
    const LINEBREAK_CTRENTER = 'ctrenter';
    const LINEBREAK_SHIFTENTER = 'shiftenter';
    const LINEBREAK_DEFAULT = self::LINEBREAK_CTRENTER;

    /**
     * Константы пола.
     */
    const GENDER_MALE = 'male';
    const GENDER_FEMALE = 'female';

    /**
     * Типы пользователей.
     */
    const TYPE_USER = 'user';
    const TYPE_LAWYER = 'lawyer';
    const TYPE_COMPANY = 'company';
    const TYPE_ADMIN = 'admin';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'firstname', 'lastname', 'middlename', 'telephone', 'birthday', 'city_id', 'gender', 'email', 'password', 'linebreak'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * Имя пользователя.
     * @return string
     */
    public function getNameAttribute()
    {
        return $this->display_name;
    }

    /**
     * ФИО.
     * @return string
     */
    public function getFullnameAttribute()
    {
        return ($this->lastname ? $this->lastname . ' ' : '') .  $this->firstname . ($this->middlename ? ' ' . $this->middlename : '');
    }

    /**
     * Основное отображаемое имя.
     * @return string
     */
    public function getDisplayNameAttribute()
    {
        return ($this->lastname ? $this->lastname . ' ' : '') .  $this->firstname;
    }

    /**
     * Фамилия и инициалы
     * @return string
     */
    public function getShortNameAttribute()
    {
        return ($this->lastname ? $this->lastname . ' ' : '') .  Str::substr($this->firstname, 0, 1) . '.' . ($this->middlename ? ' ' . Str::substr($this->middlename, 0, 1) . '.' : '');
    }

    /**
     * Это юрист?
     * @return boolean
     */
    public function getIsLawyerAttribute()
    {
        return $this->type == self::TYPE_LAWYER;
    }

    /**
     * Это компания?
     * @return boolean
     */
    public function getIsCompanyAttribute()
    {
        return $this->type == self::TYPE_COMPANY;
    }

    /**
     * Это обычный пользователь(клиента)?
     * @return boolean
     */
    public function getIsUserAttribute()
    {
        return $this->type == self::TYPE_USER && $this->roles->count() == 0;
    }

    /**
     * Это админ?
     * @return boolean
     */
    public function getIsAdminAttribute()
    {
        return $this->roles->count() > 0 && $this->roles->where('user_id', $this->id)->count() > 0;
    }

    /**
     * О пользователе.
     * @return string
     */
    public function getAboutAttribute()
    {
        return $this->display_name;
    }

    /**
     * Тип пользователя.
     * @return string
     */
    public function getTypeAttribute()
    {
        return $this->lawyer ? ($this->lawyer->companyowner ? self::TYPE_COMPANY : self::TYPE_LAWYER) : ($this->isAdmin ? self::TYPE_ADMIN : self::TYPE_USER);
    }

    /**
     * Название типа пользователя.
     * @return string
     */
    public function getTypeLabelAttribute()
    {
        $types = self::getTypes();
        return isset($types[$this->type]) ? $types[$this->type] : $this->type;
    }

    /**
     * Название статуса.
     * @return string
     */
    public function getStatusLabelAttribute()
    {
        $statuses = self::getStatuses();
        $status = $this->status ?: 0;
        return isset($statuses[$status]) ? $statuses[$status] : $status;
    }

    /**
     * Получение время последнего доступа.
     * @param  string|Date $value
     * @return string|null
     */
    public function getLastTimeAttribute($value)
    {
        return $value === null ? null : new Date($value);
    }

    /**
     * Установка время последнего доступа.
     * @param  string|Date $value
     */
    public function setLastTimeAttribute($value)
    {
        $this->attributes['last_time'] = self::getDateFormatDBValue($value);
    }

    /**
     * Получение время последнего входа.
     * @param  string|Date $value
     * @return string|null
     */
    public function getLoginAtAttribute($value)
    {
        return $value === null ? null : new Date($value);
    }

    /**
     * Установка время последнего входа.
     * @param  string|Date $value
     */
    public function setLoginAtAttribute($value)
    {
        $this->attributes['login_at'] = self::getDateFormatDBValue($value);
    }

    /**
     * Получение время последнего выход.
     * @param  string|Date $value
     * @return string|null
     */
    public function getLogoutAtAttribute($value)
    {
        return $value === null ? null : new Date($value);
    }

    /**
     * Установка время последнего выход.
     * @param  string|Date $value
     */
    public function setLogoutAtAttribute($value)
    {
        $this->attributes['logout_at'] = self::getDateFormatDBValue($value);
    }

    /**
     * Переносы строки.
     * @return array
     */
    public static function getLinebreaks()
    {
        return [
            self::LINEBREAK_CTRENTER => trans('user.linebreak.ctrenter'),
            self::LINEBREAK_SHIFTENTER => trans('user.linebreak.shiftenter'),
        ];
    }

    /**
     * Список полов.
     * @return array
     */
    public static function getGenders()
    {
        return [
            self::GENDER_MALE => trans('user.gender.male'),
            self::GENDER_FEMALE => trans('user.gender.female'),
        ];
    }

    /**
     * Типы пользователей.
     * @return array
     */
    public static function getTypes()
    {
        return [
            self::TYPE_USER => trans('user.type.user'),
            self::TYPE_LAWYER => trans('user.type.lawyer'),
            self::TYPE_COMPANY => trans('user.type.company'),
            self::TYPE_ADMIN => trans('user.type.admin'),
        ];
    }

    /**
     * Статусы.
     * @return array
     */
    public static function getStatuses()
    {
        return [trans('app.inactive'), trans('app.active')];
    }

    /**
     * Пользователь онлайн.
     * @return boolean
     */
    public function isOnline()
    {
        return $this->last_time && Date::now()->diff($this->last_time)->s < config('site.user.online_time', 120);
    }

    /**
     * Профиль юриста.
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function lawyer()
    {
        return $this->hasOne(Lawyer::class, 'user_id');
    }

    /**
     * Город.
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function city()
    {
        return $this->belongsTo(City::class, 'city_id');
    }

    /**
     * Вопросы пользователя.
     * @param boolean $default
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function questions()
    {
        return $this->hasMany(Question::class, 'user_id');
    }

    /**
     * Документы пользователя.
     * @param boolean $default
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function documents()
    {
        return $this->hasMany(Document::class, 'user_id');
    }

    /**
     * Консультации по телефону пользователя.
     * @param boolean $default
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function calls()
    {
        return $this->hasMany(Call::class);
    }

    /**
     * Получение фото пользователя.
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne
     */
    public function photo()
    {
       return $this->morphOne(File::class, 'owner')->where('field', 'photo');
    }

    /**
     * Оценки пользователя.
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function likes()
    {
        return $this->hasMany(Like::class, 'user_id');
    }

    /**
     * Ответы пользователя.
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function answers()
    {
        return $this->morphMany(Answer::class, 'from');
    }

    /**
     * Уведомления пользователя.
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    /**
     * Роли пользователя.
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function roles()
    {
        return $this->hasMany(Role::class);
    }

    /**
     * Чаты.
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function chats()
    {
        return $this->hasMany(Chat::class, 'from_id');
    }

    /**
     * Подписки.
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function subscribes()
    {
        return $this->hasMany(Subscribe::class, 'user_id');
    }

    /**
     * Активные пользователи.
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActiveStatus($query)
    {
        return $query->where($this->getTable() . '.status', true);
    }

    /**
     * Клиенты.
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeUsers($query)
    {
        return $query->doesntHave('lawyer')->doesntHave('roles');
    }

    /**
     * Юристы.
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeLawyers($query)
    {
        return $query->whereHas('lawyer', function ($query) {
            $query->where('companyowner', '!=', 1);
        });
    }

    /**
     * Компании.
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeCompanies($query)
    {
        return $query->whereHas('lawyer', function ($query) {
            $query->where('companyowner', '=', 1);
        });
    }

    /**
     * Админы.
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeAdmins($query)
    {
        return $query->whereHas('roles', function ($query) {
            $query->where('role', '=', Role::ADMIN);
        });
    }

}
