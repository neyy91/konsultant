<?php

namespace App\Models;

use Date;
use Illuminate\Database\Eloquent\Model;

use App\Libs\HasManyThroughWithKeyTrait;
use App\Libs\DateCreatedUpdated;


/**
 * Юристы.
 */
class Lawyer extends Model
{
    /**
     * Traints.
     */
    use HasManyThroughWithKeyTrait;

    /**
     * Короткое имя для morphMap.
     */
    const MORPH_NAME = 'lawyer';

    const LANG_ID = 'lawyer';


    /**
     * Отключаем автосоздание время обновления и создания.
     * @var boolean
     */
    public $timestamps = false;

    /**
     * Обновление родителя.
     * @var array
     */
    protected $touches = ['user'];

    /**
     * Статусы.
     */
    const STATUS_ADVOCATE = 1;
    const STATUS_NOTARY = 2;
    const STATUS_LAWYER = 3;

    /**
     * @var array
     */
    protected $fillable = ['status', 'contactphones', 'contactemail', 'fax', 'site', 'skype', 'callavailable', 'timezone', 'weekdays', 'weekdaysfrom', 'weekendto', 'weekend', 'weekendfrom', 'weekendto', 'category_law_id', 'companyname', 'position', 'experience', 'costcall', 'costchat', 'costdocument', 'cost', 'aboutmyself'];

    /**
     * Дата рождения в формате Date.
     * @return \Jenssegers\Date\Date
     */
    public function getBirthdayAttribute($value)
    {
        return new Date($value);
    }

    /**
     * Контакты в string array.
     * @param  string $value
     * @return array
     */
    public function getContactphonesAttribute($value)
    {
        return explode('|', $value);
    }

    /**
     * Установка contacts attribute.
     * @param mixed $value
     * @return string
     */
    public function setContactphonesAttribute($value)
    {
        if (is_array($value)) {
            $value = implode('|', array_filter($value));
        }
        $this->attributes['contactphones'] = $value;
    }

    /**
     * Все статусы юриста.
     * @return array
     */
    public static function getStatuses()
    {
        return [
            self::STATUS_ADVOCATE => trans('user.status.advocate'),
            self::STATUS_NOTARY => trans('user.status.notary'),
            self::STATUS_LAWYER => trans('user.status.lawyer'),
        ];
    }

    /**
     * Название статуса.
     * @return string
     */
    public function getStatusLabelAttribute()
    {
        $statuses = self::getStatuses();
        return isset($statuses[$this->status]) ? $statuses[$this->status] : $this->status;
    }

    /**
     * Опыт списком.
     * @return array
     */
    public static function getExperiences()
    {
        static $experiences = [];
        if (empty($experiences)) {
            $years = range(0, 60);
            $experiences = array_map(function ($value) {
                $num = ($value > 20 && ($num = $value%10) !== 0) ? $num : $value;
                return trans_choice('user.experience_year', $num, ['year' => $value]);
            }, array_combine($years, $years));
        }
        return $experiences;
    }

    /**
     * Текст опыта.
     * @return string
     */
    public function getExperienceLabelAttribute()
    {
        if (!isset($experiences)) {
            $experiences = self::getExperiences();
        }
        return isset($experiences[$this->experience]) ? $experiences[$this->experience] : $this->experience;
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
     * Образование.
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function education()
    {
        return $this->hasOne(Education::class);
    }

    /**
     * Награды.
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function honors()
    {
        return $this->morphMany(File::class, 'owner')->where('field', 'honor');
    }

    /**
     * Специализации.
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function specializations()
    {
        return $this->belongsToMany(CategoryLaw::class, (new Specialization)->getTable());
    }

    /**
     * Закладки.
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function bookmarks()
    {
        return $this->hasMany(Bookmark::class);
    }

    /**
     * Категории закладок.
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function bookmarkCategories()
    {
        return $this->hasMany(BookmarkCategory::class);
    }

    /**
     * Оценки от юриста.
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function likes()
    {
        return $this->morphMany(Like::class, 'from');
    }

    /**
     * Оценили юриста.
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function liked()
    {
        static $tLike, $tAnswer;
        if (!($tLike && $tAnswer)) {
            $tLike = (new Like)->getTable();
            $tAnswer = (new Answer)->getTable();
        }
        return $this->hasManyThrough(Like::class, Answer::class, 'from_id', 'entity_id', 'id')->where($tLike . '.entity_type', Answer::MORPH_NAME)->where($tAnswer . '.to_type', Question::MORPH_NAME)->distinct();
    }

    public function questionsAnswered()
    {
        static $tAnswer;
        if (!$tAnswer) {
            $tAnswer = (new Answer)->getTable();
        }
        return $this->hasManyThroughWithKey(Question::class, Answer::class, 'to_id', 'from_id', 'id')->where($tAnswer . '.from_type', Lawyer::MORPH_NAME)->where($tAnswer . '.to_type', Question::MORPH_NAME)->distinct();
    }

    /**
     * Ответы юриста.
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function answers()
    {
        return $this->morphMany(Answer::class, 'from');
    }

    /**
     * Ответы юриста на вопрос.
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function qanswers()
    {
        return $this->morphMany(Answer::class, 'from')->where('to_type', '=', Question::MORPH_NAME);
    }

    /**
     * Компания.
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Scope активные пользователи.
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        static $tUser, $tLawyer;
        if (!($tUser && $tLawyer)) {
            $tUser = (new User)->getTable();
            $tLawyer = (new Lawyer)->getTable();
        }
        return $query->join($tUser, $tLawyer . '.user_id', '=', $tUser . '.id')->where($tUser . '.status', true);
    }

}
