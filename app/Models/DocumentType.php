<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Libs\Status;
use App\Libs\StatusDefine;
use App\Libs\Slug;
use App\Libs\DateCreatedUpdated;


/**
 * Типы документов.
 */
class DocumentType extends Model implements StatusDefine
{

    use Status,
        Slug,
        DateCreatedUpdated;

    /**
     * Массовое заполнения полей.
     * @var array
     */
    protected $fillable = ['name', 'slug', 'status', 'sort', 'description', 'text'];

    /**
     * @var array
     */
    protected $casts = [
        'sort' => 'integer',
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
     * Потомки - документы.
     * @param boolean $default
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function childs()
    {
        return $this->hasMany(DocumentType::class, 'parent_id');
    }

    /**
     * Родитель документа.
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function parent()
    {
        return $this->belongsTo(DocumentType::class, 'parent_id');
    }

    /**
     * Вопросы.
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function documents()
    {
        return $this->hasMany(Document::class);
    }


    /**
     * Установка по умолчанию.
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSetDefault($query)
    {
        return $query->orderBy('sort', 'asc')->orderBy('id', 'desc')->where('status', self::PUBLISHED);
    }

    /**
     * Коротко о документе.
     * @return string
     */
    public function getAboutAttribute()
    {
        return $this->name;
    }

}
