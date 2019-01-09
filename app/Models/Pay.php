<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pay extends Model
{
    const MORPH_NAME = 'pay';

    const STATUS_FAILED = -1;
    const STATUS_CANCELED = 0;
    const STATUS_START = 1;
    const STATUS_PAY = 2;
    const STATUS_PAYED = 3;
    const STATUS_SUCCESS = 4;
    const STATUS_DEFAULT = self::STATUS_START;

    /**
     * Оплата услуг.
     * @return array
     */
    public static function getServices()
    {
        return [
            Question::MORPH_NAME => trans('pay.services.question'),
            Document::MORPH_NAME => trans('pay.services.document'),
            Call::MORPH_NAME => trans('pay.services.call'),
            Lawyer::MORPH_NAME => trans('pay.services.lawyer'),
        ];
    }

    /**
     * Id услуг.
     * @return array
     */
    public static function getServiceTypes()
    {
        return array_keys(self::getServices());
    }

    /**
     * Статусы.
     * @return array
     */
    public static function getStatuses()
    {
        return [
            self::STATUS_FAILED => trans('pay.statuses.failed'),
            self::STATUS_CANCELED => trans('pay.statuses.canceled'),
            self::STATUS_PAY => trans('pay.statuses.pay'),
            self::STATUS_PAYED => trans('pay.statuses.payed'),
            self::STATUS_SUCCESS => trans('pay.statuses.success'),
        ];
    }

    /**
     * Ключи статусов.
     * @return array
     */
    public static function getStatusKeys()
    {
        return array_keys(self::getStatuses());
    }

    /**
     * Название статуса.
     * @return string|integer
     */
    public function getStatusLabelAttribute()
    {
        $statuses = self::getStatuses();
        return isset($statuses[$this->status]) ? $statuses[$this->status] : $this->status;
    }

    /**
     * Оплачен.
     * @return boolean
     */
    public function getIsPayedAttribute()
    {
        return in_array($this->status, [self::STATUS_PAYED, self::STATUS_SUCCESS]);
    }

    /**
     * Пользователь.
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Услуга для оплаты.
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function service()
    {
        return $this->morphTo('service');
    }
}
