<?php 

namespace App\Libs;

use App\Helper;

trait StatusGeneral {

    /**
     * Получение статусов.
     * @return array
     */
    public static function getStatuses()
    {
        return [
            self::STATUS_BLOCKED => trans('statuses.blocked'),
            self::STATUS_UNPUBLISHED => trans('statuses.unpublished'),
            self::STATUS_IN_WORK => trans('statuses.in_work'),
            self::STATUS_LAWYER_SELECTED => trans('statuses.lawyer_selected'),
            self::STATUS_COMPLETED => trans('statuses.completed'),
        ];
    }

    /**
     * Получение ключей статуса.
     * @return array
     */
    public static function getStatusKeys()
    {
        return array_keys(self::getStatuses());
    }

    /**
     * Установка статуса.
     * @param integer $value
     */
    public function setStatusAttribute($value)
    {
        $statuses = self::getStatuses();
        $this->attributes['status'] = isset($statuses[$value]) ? $value : self::STATUS_DEFAULT;
    }

    /**
     * Название статуса у вопроса.
     * @return string
     */
    public function getStatusLabelAttribute()
    {
        $statuses = self::getStatuses();
        return isset($statuses[$this->status]) ? $statuses[$this->status] : $this->status;
    }

}