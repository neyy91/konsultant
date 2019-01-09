<?php 

namespace App\Libs;

/**
 * Определение статусов.
 */
interface StatusDefine {

    /**
     * Статусы.
     */
    /**
     * @var integer
     */
    const PUBLISHED = 1; // Опубликован
    /**
     * @var integer
     */
    const UNPUBLISHED = -1; // Не опубликован
}