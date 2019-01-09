<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Libs\Status;
use App\Libs\StatusDefine;
use App\Libs\Slug;


/**
 * Города.
 */
class City extends Model implements StatusDefine
{
    use Status,
        Slug;

    /**
     * @var boolean
     */
    public $timestamps = false;

    /**
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
     * Регион города.
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function region()
    {
        return $this->belongsTo(Region::class);
    }

    /**
     * Вопросы.
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    /**
     * Пользователи из города.
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }

    /**
     * Установка по умолчанию.
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSetDefault($query)
    {
        return $query->orderBy('sort', 'asc')->where('status', self::PUBLISHED)->with('region');
    }

    public function lawyers()
    {
        static $tUser;
        if (!$tUser) {
            $tUser = (new User)->getTable();
        }
        return $this->hasManyThrough(Lawyer::class, User::class, 'city_id', 'user_id')->where($tUser . '.status', true);
    }

}
