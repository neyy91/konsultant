<?php 

namespace App\Libs;

/**
 * Определение статусов.
 */
interface StatusGeneralDefine {

    /**
     * Статусы.
     */
    const STATUS_BLOCKED = -1;
    const STATUS_UNPUBLISHED = 0;
    const STATUS_IN_WORK = 1;
    const STATUS_LAWYER_SELECTED = 2;
    const STATUS_COMPLETED = 3;
    const STATUS_DEFAULT = self::STATUS_UNPUBLISHED;
}