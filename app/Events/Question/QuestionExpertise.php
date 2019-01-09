<?php

namespace App\Events\Question;

use Illuminate\Queue\SerializesModels;


/**
 * Экспертиза вопроса.
 */
class QuestionExpertise extends QuestionEvent
{

    use SerializesModels;

}
