<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Libs\DateCreatedUpdated;


/**
 * Экспертиза.
 */
class Expertise extends Model
{
    /**
     * Короткое имя для morphMap.
     */
    const MORPH_NAME = 'expertise';

    /**
     * Типы экспертиз.
     */
    const TYPE_EXPERT = 'expert';
    const TYPE_MESSAGE = 'message';
    const TYPE_OWNER = 'owner';

    /**
     * @var array
     */
    protected $fillable = ['message'];

    /**
     * Типы экспертиз.
     * @return array
     */
    public static function getTypes()
    {
        return [
            self::TYPE_EXPERT => trans('expertise.type.' . self::TYPE_EXPERT),
            self::TYPE_MESSAGE => trans('expertise.type.' . self::TYPE_MESSAGE),
            self::TYPE_OWNER => trans('expertise.type.' . self::TYPE_OWNER),
        ];
    }

    /**
     * О экспертизе.
     * @return string
     */
    public function getAboutAttribute()
    {
        return trans('app.from') . ' ' . $this->lawyer->user->display_name;
    }

    /**
     * Название типа.
     * @return string
     */
    public function getTypeLabelAttribute()
    {
        $types = self::getTypes();
        return $this->type && $types[$this->type] ? $types[$this->type] : $this->type;
    }

    /**
     * Вопрос.
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    /**
     * Юрист.
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function lawyer()
    {
        return $this->belongsTo(Lawyer::class);
    }

    /**
     * По умолчанию
     */
    public function scopeSetDefault($query)
    {
        $query->orderBy('created_at', 'asc');
    }
}
