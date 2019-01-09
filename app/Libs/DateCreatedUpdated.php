<?php 

namespace App\Libs;

use Date;

trait DateCreatedUpdated {

    /**
     * Дата в формате БД.
     * @param  string|Date $value
     * @return string
     */
    protected static function getDateFormatDBValue($value = null)
    {
        if ($value === null) {
            $value = Date::now();
        }
        return $value instanceof Date ? $value->format('Y-m-d H:i:s') : $value;
    }

    /**
     * Дата создания формата short.
     * @return string
     */
    public function getCreatedShortAttribute()
    {
        return $this->created_at === null ? null : $this->created_at->format(config('site.date.short', 'd.m.Y'));
    }

    /**
     * Дата создания формата middle.
     * @return string|null
     */
    public function getCreatedMiddleAttribute()
    {
        return $this->created_at === null ? null : $this->created_at->format(config('site.date.middle', 'd.m.Y H:i'));
    }

    /**
     * Дата создания формата long.
     * @return string|null
     */
    public function getCreatedLongAttribute()
    {
        return $this->created_at === null ? null : $this->created_at->format(config('site.date.long', 'j F Y, H:i'));
    }

    /**
     * Дата создания.
     * @param integer $value
     * @return \Jenssegers\Date\Date|null
     */
    public function getCreatedAtAttribute($value)
    {
        return $value === null ? null : new Date($value);
    }

    /**
     * Дата обновления формата short.
     * @return string|null
     */
    public function getUpdatedShortAttribute()
    {
        return $this->updated_at === null ? null : $this->updated_at->format(config('site.date.short', 'd.m.Y'));
    }
    
    /**
     * Дата обновления формата middle.
     * @return string|null
     */
    public function getUpdatedMiddleAttribute()
    {
        return $this->updated_at === null ? null : $this->updated_at->format(config('site.date.middle', 'd.m.Y H:i'));
    }

    /**
     * Дата обновления формата long.
     * @return string|null
     */
    public function getUpdatedLongAttribute()
    {
        return $this->updated_at === null ? null : $this->updated_at->format(config('site.date.long', 'j F Y, H:i'));
    }

    /**
     * Дата обновления.
     * @param  integer $value
     * @return \Jenssegers\Date\Date|null
     */
    public function getUpdatedAtAttribute($value)
    {
        return $value === null ? null : new Date($value);
    }

}