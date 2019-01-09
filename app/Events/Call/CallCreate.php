<?php

namespace App\Events\Call;

use Illuminate\Queue\SerializesModels;

/**
 * Создание звонка.
 */
class CallCreate extends CallEvent
{
    
    use SerializesModels;

}
