<?php

namespace App\Events\Answer;

use Illuminate\Queue\SerializesModels;


/**
 * Обновление ответа.
 */
class AnswerUpdate extends AnswerEvent
{
    
    use SerializesModels;

}
