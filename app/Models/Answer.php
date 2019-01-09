<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Libs\DateCreatedUpdated;
use App\Libs\RelationForceDeleteGeneral;

/**
 * Ответы.
 */
class Answer extends Model
{
    use DateCreatedUpdated,
        RelationForceDeleteGeneral;

    /**
     * Короткое имя для morphMap.
     */
    const MORPH_NAME = 'answer';

    /**
     * @var array
     */
    protected $fillable = ['text', 'cost', 'rate'];

    /**
     * @var array
     */
    protected $casts = [
        'is' => 'boolean',
        'cost' => 'integer',
        'rate' => 'integer',
    ];

    /**
     * Пользователь вопроса.
     * @return User
     */
    public function getUserAttribute()
    {
        return $this->from && $this->from_type == Lawyer::MORPH_NAME ? $this->from->user : $this->from;
    }

    /**
     * Родитель(сервис: вопрос, документ, звонок).
     * @return Question|Document|Call
     */
    public function getParentAttribute()
    {
        return $this->to && $this->to_type == self::MORPH_NAME ? $this->to->to : $this->to;
    }

    /**
     * Вопрос, документ, звонок, ответ.
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function to()
    {
        return $this->morphTo('to');
    }

    /**
     * Получение ответов.
     * @param boolean $default
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function answers()
    {
        return $this->morphMany(Answer::class, 'to');
    }

    /**
     * Вопрос.
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function question()
    {
        return $this->morphTo('to', 'question');
    }

    /**
     * Файл ответа.
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne
     */
    public function file()
    {
        return $this->morphOne(File::class, 'owner')->where('field', 'file');
    }

    /**
     * Уточнения.
     * @return \Illuminate\Database\Eloquent\Relations\morphMany
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
     * Оценки.
     * @param boolean $default
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function likes()
    {
        return $this->morphMany(Like::class, 'entity');
    }

    /**
     * Пользователь.
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function from()
    {
        return $this->morphTo('from');
    }

    /**
     * Установка по умолчанию.
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSetDefault($query)
    {
        return $query->orderBy('created_at', 'asc')->with('file', 'likes');
    }

    /**
     * Установка по умолчанию ответа юристу(на ответ).
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSetDefaultReply($query)
    {
        return $query->orderBy('created_at', 'asc')->with('file');
    }

    /**
     * Коротко об ответе.
     * @return string
     */
    public function getAboutAttribute()
    {
        return $this->text ? str_limit(strip_tags($this->text), 50) : trans('answer.not_text');
    }

}
