<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Libs\Status;
use App\Libs\StatusDefine;
use App\Libs\Slug;
use App\Libs\DateCreatedUpdated;


/**
 * Темы.
 */
class Theme extends Model implements StatusDefine
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
     * Установка по умолчанию.
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSetDefault($query)
    {
        return $query->orderBy('sort', 'asc')->orderBy('id', 'desc')->where('status', self::PUBLISHED);
    }

    /**
     * Категории права.
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function categoryLaw()
    {
        return $this->belongsTo(CategoryLaw::class, 'category_law_id');
    }

    /**
     * Вопросы по теме.
     * @return \Illuminate\Database\Eloquent\Relations\belongsToMany
     */
    public function questions()
    {
        return $this->belongsToMany(Question::class);
    }

}
