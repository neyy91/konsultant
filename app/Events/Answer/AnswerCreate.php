<?php

namespace App\Events\Answer;

use Illuminate\Queue\SerializesModels;


/**
 * Создание ответа.
 */
class AnswerCreate extends AnswerEvent
{
    
    use SerializesModels;

}
