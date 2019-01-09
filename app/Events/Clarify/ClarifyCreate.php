<?php

namespace App\Events\Clarify;

use Illuminate\Queue\SerializesModels;

/**
 * Создание уточнения.
 */
class ClarifyCreate extends ClarifyEvent
{
    use SerializesModels;

}
