<?php 

namespace App\Libs;

/**
 * Определение от кого.
 */
interface FromDefine {

    /**
     * От кого вопрос.
     */
    const FROM_PRIVATE_PERSON = 1;
    const FROM_BUSINESS = 2;
    const FROM_DEFAULT = self::FROM_PRIVATE_PERSON;
}