<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Libs\Slug;
use App\Libs\DateCreatedUpdated;
use App\Libs\StatusGeneral;
use App\Libs\StatusGeneralDefine;
use App\Libs\RelationForceDeleteGeneral;
use App\Libs\ScopePublishedGeneral;

/**
 * Документы.
 */
class Document extends Model implements StatusGeneralDefine
{
    use Slug,
        DateCreatedUpdated,
        RelationForceDeleteGeneral,
        ScopePublishedGeneral,
        StatusGeneral
    ;

    /**
     * Поле для slug.
     * @var string
     */
    const SLUG_FIELD = 'title';

    /**
     * Короткое имя для morphMap.
     */
    const MORPH_NAME = 'document';

    /**
     * ID языковго файла(название без расширения).
     */
    const LANG_ID = 'document';

    /**
     * @var array
     */
    protected $fillable = ['document_type_id', 'cost' , 'title', 'description', 'city_id', 'telephone'];

    /**
     * @var array
     */
    protected $casts = [
        'status' => 'integer',
        'sticky' => 'boolean',
        'sort' => 'integer',
        'cost'=> 'integer',
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
     * Тип документа.
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function documentType()
    {
        return $this->belongsTo(DocumentType::class);
    }

    /**
     * Проверка и установка значения document_type_id.
     * @param integer $value
     */
    public function setDocumentTypeIdAttribute($value)
    {
        $documentType = DocumentType::findOrFail($value);
        $this->attributes['document_type_id'] = $documentType->id;
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
     * Выбранное предложение.
     * @return Answer
     */
    public function getIsAnswerAttribute()
    {
        return $this->answers()->where('is', 1)->first();
    }

    /**
     * Сумма для оплаты.
     * @return integer
     */
    public function getPayCostAttribute()
    {
        return ($isAnswer = $this->isAnswer) && $isAnswer->is && $isAnswer->cost ? $isAnswer->cost : $this->cost;
    }

    /**
     * Документ оплачен.
     * @return boolean
     */
    public function getIsPayedAttribute()
    {
        return $this->payment && $this->payment->isPayed;
    }

    /**
     * Город.
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function city()
    {
        return $this->belongsTo(City::class);
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
     * Получение файла.
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne
     */
    public function file()
    {
        return $this->morphOne(File::class, 'owner')->where('field', 'file');
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
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Оплаты.
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function payment()
    {
        return $this->morphOne(Pay::class, 'service');
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
        return $query->orderBy('created_at', 'desc')->whereNotNull('slug')->published()->with('documentType', 'city', 'answers', 'clarifies');
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
