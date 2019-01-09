<?php

namespace App\Events\Like;

use Illuminate\Queue\SerializesModels;


/**
 * Создание документа.
 */
class LikeCreate extends LikeEvent
{
    
    use SerializesModels;

}