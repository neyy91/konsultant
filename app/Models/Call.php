<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Libs\Slug;
use App\Libs\StatusGeneral;
use App\Libs\StatusGeneralDefine;
use App\Libs\DateCreatedUpdated;
use App\Libs\RelationForceDeleteGeneral;
use App\Libs\ScopePublishedGeneral;
use App\Libs\PayField;
use App\Libs\PayFieldDefine;

class Call extends Model implements StatusGeneralDefine, PayFieldDefine
{
    use Slug,
        StatusGeneral,
        DateCreatedUpdated,
        RelationForceDeleteGeneral,
        ScopePublishedGeneral,
        PayField
    ;

    /**
     * Поле для slug.
     * @var string
     */
    const SLUG_FIELD = 'title';

    /**
     * Короткое имя для morphMap.
     */
    const MORPH_NAME = 'call';

    /**
     * Id перевода.
     */
    const LANG_ID = 'call';

    /**
     * Оплата по умолчанию.
     */
    const PAY_DEFAULT = self::PAY_STANDART;

    /**
     * @var array
     */
    protected $fillable = ['title', 'description' , 'city_id', 'pay'];

    /**
     * @var array
     */
    protected $casts = [
        'status' => 'integer',
    ];

    /**
     * Получение ключа для Route.
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'slug';
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
     * Сумма для оплаты.
     * @return integer
     */
    public function getPayCostAttribute()
    {
        $sums = config('site.call.pay');
        return isset($sums[$this->pay]) ? $sums[$this->pay] : 0;
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
     * Получение файла.
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne
     */
    public function file()
    {
        return $this->morphOne(File::class, 'owner')->where('field', 'file');
    }

    /**
     * Ответы.
     * @param boolean $default
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function answers()
    {
        return $this->morphMany(Answer::class, 'to');
    }

    /**
     * Уточнения.
     * @param boolean $default
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function clarifies()
    {
        return $this->morphMany(Clarify::class, 'to');
    }

    /**
     * Жалобы.
     * @param boolean $default
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function complaints()
    {
        return $this->morphMany(Complaint::class, 'against');
    }

    /**
     * Пользователь.
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Коротко об ответе.
     * @return string
     */
    public function getAboutAttribute()
    {
        return $this->title;
    }

    /**
     * Установка по умолчанию.
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSetDefault($query)
    {
        return $query->orderBy('created_at', 'desc')->whereNotNull('slug')->published()->defaultWith();
    }

    /**
     * Подключаемые отночения по умолчанию.
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeDefaultWith($query)
    {
        return $query->with('answers', 'city', 'user');
    }

    /**
     * Уставнока фильтра для пользователя.
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeCurrentUser($query)
    {
        return $query->where('user_id', \Auth::user()->id);
    }
    
}
