<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Libs\DateCreatedUpdated;


/**
 * Жалобы.
 */
class Complaint extends Model
{
    use DateCreatedUpdated;

    const TYPE_CONTACT_IN_ANSWER = 1;
    const TYPE_PLAGIARY = 2;
    const TYPE_DUPLICATING_ANSWERS = 3;
    const TYPE_SMALL_ANSWER = 4;
    const TYPE_INCORRECT_DESIGN = 5;
    const TYPE_OFFER_BYPASS = 6;
    const TYPE_INSULT = 7;
    const TYPE_POSITIVE_RATING = 8;
    const TYPE_AD_SPAM = 9;
    const TYPE_INVALID_ANSWER = 10;
    const TYPE_OTHER = 11;

    /**
     * @var array
     */
    protected $fillable = ['type', 'comment'];

    protected $casts = [
        'type' => 'integer',
    ];

    /**
     * Жалоба на.
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function against()
    {
        return $this->morphTo('against');
    }

    /**
     * Установка по умолчанию.
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSetDefault($query)
    {
        return $query->orderBy('created_at', 'desc')->with('against');
    }

    /**
     * Список переводов для ответа.
     * @return array
     */
    public static function getTypesTrans()
    {
        return [
            self::TYPE_CONTACT_IN_ANSWER => 'complaint.type.contact_in_answer',
            self::TYPE_PLAGIARY => 'complaint.type.plagiary',
            self::TYPE_DUPLICATING_ANSWERS => 'complaint.type.duplicating_answers',
            self::TYPE_SMALL_ANSWER => 'complaint.type.small_answer',
            self::TYPE_INCORRECT_DESIGN => 'complaint.type.incorrect_design',
            self::TYPE_OFFER_BYPASS => 'complaint.type.offer_bypass',
            self::TYPE_INSULT => 'complaint.type.insult',
            self::TYPE_POSITIVE_RATING => 'complaint.type.positive_rating',
            self::TYPE_AD_SPAM => 'complaint.type.ad_spam',
            self::TYPE_INVALID_ANSWER => 'complaint.type.invalid_answer',
            self::TYPE_OTHER => 'complaint.type.other',
        ];
    }

    /**
     * Список типов.
     * @return array
     */
    public static function getTypes()
    {
        return array_map(function($value) {
            return trans($value);
        }, self::getTypesTrans());
    }

    /**
     * Список описания типов.
     * @return array
     */
    public static function getTypeDescriptions()
    {
        return array_map(function($value) {
            return trans($value . '_description');
        }, self::getTypesTrans());
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
     * Название типа.
     * @return string
     */
    public function getAttributeTypeLabel()
    {
        $types = self::getTypes();
        return isset($types[$this->type]) ? $types[$this->type] : $this->type;
    }

    /**
     * Ключи типов.
     * @return array
     */
    public static function getTypeKeys()
    {
        return array_keys(self::getTypesTrans());
    }

}
