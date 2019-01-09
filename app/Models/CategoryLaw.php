<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Libs\Status;
use App\Libs\StatusDefine;
use App\Libs\Slug;
use App\Libs\DateCreatedUpdated;
use App\Libs\From;
use App\Libs\FromDefine;


/**
 * Категория права.
 */
class CategoryLaw extends Model implements StatusDefine, FromDefine
{
    use Status,
        Slug,
        From,
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
     * Подкатегории.
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function childs()
    {
        return $this->hasMany(CategoryLaw::class, 'parent_id');
    }

    /**
     * Родитель категории.
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function parent()
    {
        return $this->belongsTo(CategoryLaw::class, 'parent_id');
    }

    /**
     * Ссылка на вопросы
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    /**
     * Теми категории права.
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function themes()
    {
        return $this->hasMany(Theme::class, 'category_law_id');
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
     * Коротко о категории права.
     * @return string
     */
    public function getAboutAttribute()
    {
        return $this->name;
    }
}
