<?php 

namespace App\Libs;

/**
 * Статусы. Необходимо implements StatusDefine - там определены константы.
 */
trait Status {

    /**
     * Установка атрибуты status. Проверка на корректность.
     * @param integer $value
     * @return void
     */
    public function setStatusAttribute($value)
    {
        $statuses = self::getStatuses();
        $this->attributes['status'] = isset($statuses[$value]) ? $value : self::UNPUBLISHED;
    }

    /**
     * Получение названия статуса.
     * @return integer
     */
    public function getStatusLabelAttribute()
    {
        $statuses = self::getStatuses();
        return isset($statuses[$this->status]) ? $statuses[$this->status]: self::UNPUBLISHED;
    }

    /**
     * Список статусов.
     * @return array
     */
    public static function getStatuses()
    {
        return [
            self::PUBLISHED => trans('statuses.published'),
            self::UNPUBLISHED => trans('statuses.unpublished'),
        ];
    }

    /**
     * Список ключей статуса.
     * @return array
     */
    public static function getStatusKeys()
    {
        return array_keys(self::getStatuses());
    }

    /**
     * Активные.
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePublished($query)
    {
        return $query->where('status', self::PUBLISHED);
    }

    /**
     * Неактивные.
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeUnpublished($query)
    {
        return $query->where('status', self::UNPUBLISHED);
    }

}