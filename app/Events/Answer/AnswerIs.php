<?php

namespace App\Events\Answer;

use Illuminate\Queue\SerializesModels;

/**
 * Ответ выбран.
 */
class AnswerIs extends AnswerEvent 
{

    use SerializesModels;

}
