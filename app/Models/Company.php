<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Libs\Status;
use App\Libs\StatusDefine;
use App\Libs\DateCreatedUpdated;

/**
 * Компании.
 */
class Company extends Model implements StatusDefine
{

    use Status, DateCreatedUpdated;

    /**
     * Короткое имя для morphMap.
     */
    const MORPH_NAME = 'company';

    /**
     * @var array
     */
    protected $fillable = ['name', 'description'];

    /**
     * Владелец.
     * @return Lawyer|null
     */
    public function getOwnerAttribute()
    {
        return $this->lawyers->count() > 0 ? $this->lawyers->where('companyowner', 1)->first() : $this->lawyers;
    }

    /**
     * Сотрудники.
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function lawyers()
    {
        return $this->hasMany(Lawyer::class)->with('user');
    }

    /**
     * Активные компании.
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query, $active = true)
    {
        return $query->where('status', $active);
    }

    /**
     * Логотип компании.
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne
     */
    public function logo()
    {
        return $this->morphOne(File::class, 'owner')->where('field', 'logo');
    }

    /**
     * О компании.
     * @return string
     */
    public function getAboutAttribute()
    {
        return $this->name;
    }
}
