<?php

namespace App\Events\Question;

use Illuminate\Queue\SerializesModels;


/**
 * Создание вопроса.
 */
class QuestionCreate extends QuestionEvent
{
    
    use SerializesModels;

}
