<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use SleepingOwl\WithJoin\WithJoinTrait;

use App\Libs\DateCreatedUpdated;

/**
 * Оценки.
 */
class Like extends Model
{

    use DateCreatedUpdated,
        WithJoinTrait;

    /**
     * @var integer
     */
    const RATING_LIKE = 1;

    /**
     * @var integer
     */
    const RATING_DONT_LIKE = -1;

    /**
     * @var array
     */
    protected $fillable = ['rating', 'text'];

    protected $casts = [
        'rating' => 'integer',
    ];

    /**
     * Коротко об оценке.
     * @return string
     */
    public function getAboutAttribute()
    {
        return trans('like.rating.' ($this->rating == self::RATING_LIKE ? 'like' : 'dont_like'));
    }

    /**
     * Текст оценки
     * @return string|integer
     */
    public function getRatingLabelAttribute()
    {
        $ratingLabels = self::ratingLabels();
        return isset($ratingLabels[$this->rating]) ? $ratingLabels[$this->rating] : $this->rating;
    }

    /**
     * Текст для оценки.
     * @return string
     */
    public function getDisplayTextAttribute()
    {
        return $this->text ? $this->text : trans('like.' . ($this->rating == 1 ? 'liked_text' : 'disliked_text'));
    }

    /**
     * Массив с ключом и тектом оценок.
     * @return array
     */
    public static function ratings()
    {
        return [
            self::RATING_LIKE => trans('like.rating.like'),
            self::RATING_DONT_LIKE => trans('like.rating.dont_like'),
        ];
    }

    /**
     * Ключи оценок.
     * @return array
     */
    public static function ratingKeys()
    {
        return array_keys(self::ratings());
    }

    /**
     * Установка по умолчанию.
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSetDefault($query)
    {
        return $query->orderBy('created_at', 'desc')->where('rating', '!=', 0);
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
     * Сущность оценки.
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function entity()
    {
        return $this->morphTo('entity');
    }

}
