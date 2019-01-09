<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Libs\DateCreatedUpdated;
use App\Libs\RelationForceDeleteGeneral;

/**
 * Уточнения.
 */
class Clarify extends Model
{
    use DateCreatedUpdated,
        RelationForceDeleteGeneral;

    /**
     * Короткое имя для morphMap.
     */
    const MORPH_NAME = 'clarify';

    /**
     * @var array
     */
    protected $fillable = ['text'];

    /**
     * Вопрос, документ, ответ...
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function to()
    {
        return $this->morphTo('to');
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
     * Коротко об ответе.
     * @return string
     */
    public function getAboutAttribute()
    {
        return str_limit(strip_tags($this->text), 50);
    }

    /**
     * Установка по умолчанию.
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSetDefault($query)
    {
        return $query->orderBy('created_at', 'asc')->with('file');
    }

}
