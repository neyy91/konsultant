<?php

namespace App\Models;

use Auth;
use Illuminate\Database\Eloquent\Model;

use App\Libs\Slug;
use App\Libs\DateCreatedUpdated;
use App\Libs\StatusGeneral;
use App\Libs\StatusGeneralDefine;
use App\Libs\From;
use App\Libs\FromDefine;
use App\Libs\PayField;
use App\Libs\PayFieldDefine;
use App\Libs\RelationForceDeleteGeneral;
use App\Libs\ScopePublishedGeneral;


/**
 * Вопросы.
 */
class Question extends Model implements StatusGeneralDefine, FromDefine, PayFieldDefine
{
    use Slug,
        DateCreatedUpdated,
        RelationForceDeleteGeneral,
        ScopePublishedGeneral,
        From
    ;

    use StatusGeneral {
            getStatuses as private getStatusesGeneral;
    }

    use PayField {
        getPays as private getPaysTrait;
    }

    /**
     * Поле для slug.
     * @var string
     */
    const SLUG_FIELD = 'title';

    /**
     * Короткое имя для morphMap.
     */
    const MORPH_NAME = 'question';

    /**
     * Статус экспертиза.
     */
    const STATUS_EXPERTISE = 4;

    /**
     * ID языковго файла(название без расширения).
     */
    const LANG_ID = 'question';

    /**
     * Оплата по умолчанию.
     */
    const PAY_DEFAULT = self::PAY_VIP;

    /**
     * @var array
     */
    protected $fillable = ['from', 'category_law_id', 'title', 'description', 'city_id', 'pay'];

    /**
     * @var array
     */
    protected $casts = [
        'from' => 'integer',
        'status' => 'integer',
        'sticky' => 'boolean',
    ];

/*    protected $dates = [
        'created_at',
        'updated_at',
    ];
*/
    /**
     * Получение ключа для Route.
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    /**
     * Проверка и установка значения category_law_id.
     * @param integer $value
     */
    public function setCategoryLawIdAttribute($value)
    {
        $categoryLaw = CategoryLaw::findOrFail($value);
        $this->attributes['category_law_id'] = $categoryLaw->id;
    }

    /**
     * Проверка и установка значения city_id.
     * @param integer $value
     */
    public function setCityIdAttribute($value)
    {
        $city = City::findOrFail($value);
        $this->attributes['city_id'] = $city->id;
    }

    /**
     * Эксперты для этого вопроса.
     */
    public function getExpertiseExpertsAttribute()
    {
        return $this->expertises->count() > 0 ? $this->expertises->where('type', Expertise::TYPE_EXPERT) : $this->expertises;
    }

    /**
     * Инициатор экспертизы
     */
    public function getExpertiseOwnerAttribute()
    {
        return $this->expertises->count() > 0 ? $this->expertises->where('type', Expertise::TYPE_OWNER)->first() : $this->expertises;
    }

    /**
     * Сообщения экспертов.
     */
    public function getExpertiseMessages()
    {
        return $this->expertises->count() > 0 ? $this->expertises->where('type', '=', Expertise::TYPE_MESSAGE) : $this->expertises;
    }

    /**
     * Статусы вопроса.
     * @return array
     */
    public static function getStatuses()
    {
        $statuses = self::getStatusesGeneral();
        $statuses[self::STATUS_EXPERTISE] = trans('statuses.expertise');
        return $statuses;
    }

    /**
     * Типы оплаты за услугу.
     * @return array
     */
    public static function getPays()
    {
        $pays = self::getPaysTrait();
        unset($pays[self::PAY_SIMPLE]);
        return $pays;
    }

    /**
     * Сумма для оплаты.
     * @return integer
     */
    public function getPayCostAttribute()
    {
        $sums = config('site.question.pay');
        return isset($sums[$this->pay]) ? $sums[$this->pay] : 0;
    }

    /**
     * Коротко об вопросе.
     * @return string
     */
    public function getAboutAttribute()
    {
        return $this->title;
    }

    /**
     * Ссылка на категорию права.
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function categoryLaw()
    {
        return $this->belongsTo(CategoryLaw::class);
    }

    /**
     * Темы вопроса.
     * @return \Illuminate\Database\Eloquent\Relations\belongsToMany
     */
    public function themes()
    {
        return $this->belongsToMany(Theme::class);
    }

    /**
     * Ссылка на город.
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function city()
    {
        return $this->belongsTo(City::class);
    }

    /**
     * Получение ответов.
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function answers()
    {
        return $this->morphMany(Answer::class, 'to');
    }

    /**
     * Уточнения для вопроса.
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function clarifies()
    {
        return $this->morphMany(Clarify::class, 'to');
    }

    /**
     * Получение файла.
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne
     */
    public function file()
    {
        return $this->morphOne(File::class, 'owner')->where('field', 'file');
    }

    /**
     * Жалобы.
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function complaints()
    {
        return $this->morphMany(Complaint::class, 'against');
    }

    /**
     * Оценки.
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function likes()
    {
        return $this->morphMany(Like::class, 'entity');
    }

    /**
     * Пользователь.
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Экспертиза вопроса. Сообщения экспертов.
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function expertises()
    {
        return $this->hasMany(Expertise::class);
    }

    /**
     * Установка по умолчанию.
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSetDefault($query)
    {
        return $query->orderBy('sticky', 'desc')->orderBy('created_at', 'desc')->whereNotNull('slug')->published()->defaultWith();
    }

    /**
     * Подключаемые отночения по умолчанию.
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeDefaultWith($query)
    {
        return $query->with('categoryLaw', 'city', 'answers', 'clarifies', 'city.region', 'user');
    }

    /**
     * Уставнока фильтра для пользователя.
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeCurrentUser($query)
    {
        return $query->where('user_id', Auth::user()->id);
    }

}
