<?php 

namespace App\Libs;

/**
 * Определение цены услуги.
 */
interface PayFieldDefine {

    /**
     * Оплата услуги.
     */
    const PAY_FREE = 'free';
    const PAY_SIMPLE = 'simple';
    const PAY_STANDART = 'standart';
    const PAY_VIP = 'vip';
    // const PAY_DEFAULT = self::PAY_VIP;
}