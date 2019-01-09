<?php 

namespace App\Repositories;

use App\Models\Expertise;
use App\Models\Question;

/**
* Репозиторий для экспертизы.
*/
class ExpertiseRepository
{

    /**
     * Получение всех экспертиз для вопроса.
     * @param  integer|Question  $options
     * @return array|null
     */
    public function getMessagesForQuestion($question)
    {
        if (is_numeric($question)) {
            $question = Question::find($question);
            if (!$question) {
                return null;
            }
        }
        return Expertise::setDefault()->where('question_id', '=', $question->id)->where('type', '=', Expertise::TYPE_MESSAGE)->get()->all();
    }


}