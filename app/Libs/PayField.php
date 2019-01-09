<?php 

namespace App\Libs;

use App\Models\Pay;

/**
 * Платная услуга. Необходимо implements PayDefine - там определены константы.
 */
trait PayField {

    /**
     * Получение списка возможных значений поля pay.
     * @return array
     */
    public static function getPays()
    {
        return [
            self::PAY_FREE => trans(self::LANG_ID . '.pays.free'),
            self::PAY_SIMPLE => trans(self::LANG_ID . '.pays.simple'),
            self::PAY_STANDART => trans(self::LANG_ID . '.pays.standart'),
            self::PAY_VIP => trans(self::LANG_ID . '.pays.vip'),
        ];
    }

    /**
     * Получение ключей поля pay.
     * @return array
     */
    public static function getPayKeys()
    {
        return array_keys(self::getPays());
    }

    /**
     * Установка атрибуты pay.
     * @param integer $value
     * @return void
     */
    public function setPayAttribute($value)
    {
        $pays = self::getPays();
        $this->attributes['pay'] = isset($pays[$value]) ? $value : self::PAY_DEFAULT;
    }

    /**
     * Получение название атрибуты pay.
     * @return string
     */
    public function getPayLabelAttribute()
    {
        $pays = self::getPays();
        return isset($pays[$this->pay]) ? $pays[$this->pay] : $this->pay;
    }

    /**
     * Услуга оплачена.
     * @return boolean
     */
    public function getIsPayedAttribute()
    {
        return $this->payment && $this->payment->isPayed;
    }

    /**
     * Оплаты.
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function payment()
    {
        return $this->morphOne(Pay::class, 'service');
    }

}