<?php 

namespace App\Libs;

/**
 * От кого. Необходимо implements FromDefine - там определены константы.
 */
trait From {

    /**
     * Получение списка возможных значений поля from.
     * @return array
     */
    public static function getFroms()
    {
        return [
            self::FROM_PRIVATE_PERSON => trans('question.from.private_persion'),
            self::FROM_BUSINESS => trans('question.from.business'),
        ];
    }

    /**
     * Получение ключей поля from.
     * @return array
     */
    public static function getFromKeys()
    {
        return array_keys(self::getFroms());
    }

    /**
     * Установка атрибуты from.
     * @param integer $value
     * @return void
     */
    public function setFromAttribute($value)
    {
        $froms = self::getFroms();
        $this->attributes['from'] = isset($froms[$value]) ? $value : self::FROM_DEFAULT;
    }

    /**
     * Получение название атрибуты from.
     * @return string
     */
    public function getFromLabelAttribute()
    {
        $froms = self::getFroms();
        return isset($froms[$this->from]) ? $froms[$this->from] : $this->from;
    }

}