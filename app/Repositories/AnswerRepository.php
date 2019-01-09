<?php 

namespace App\Repositories;

use App\Models\Answer;
use App\Models\Question;
use App\Models\Document;
use App\Models\User;
use App\Models\Lawyer;

/**
* Репозиторий для ответов.
*/
class AnswerRepository
{

    /**
     * @var integer
     */
    const DEFAULT_PAGE = 5;

    /**
     * @var integer
     */
    const DEFAULT_TAKE = 5;

    /**
     * With по умолчанию.
     * @var array
     */
    static $with = [
        'default' => ['answers', 'categoryLaw', 'city', 'city.region', 'user']
    ];

    /**
     * Получения id
     * @param  mixed|integer $model
     * @return integer
     */
    protected function getId($model)
    {
        if (isset($model->id)) {
            $model = $model->id;
        }
        else {
            $model = $model;
        }
        return $model;
    }

    /**
     * Ответы пользователя постранично.
     * @param  Lawyer $lawyer
     * @param  integer      $page
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function paginateByLawyer(Lawyer $lawyer, $page = null)
    {
        $page = $page ? $page : config('site.user.answer.page', self::DEFAULT_PAGE);
        return Answer::setDefault()->where('from_id', $this->getId($lawyer))->where('from_type', $lawyer::MORPH_NAME)->paginate($page);
    }

    /**
     * Ответы пользователя.
     * @param  Lawyer $lawyer
     * @param  integer      $take
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function takeByLawyer(Lawyer $lawyer, $take = null)
    {
        $take = $take ? $take : config('site.user.answer.take', self::DEFAULT_TAKE);
        return Answer::setDefault()->where('from_id', $this->getId($lawyer))->where('from_type', $lawyer::MORPH_NAME)->take($take)->get();
    }

    protected function answeredQuestionsQuery(User $user, $with)
    {
        $with = $with ? $with : self::$with['default'];
        $answers = (new Answer)->getTable();
        $questions = (new Question)->getTable();
        return Question::select($questions . '.*')->distinct($questions . '.id')->with($with)->join($answers . ' as answers', 'answers.to_id', '=', $questions . '.id')->where('answers.to_type', Question::MORPH_NAME)->where('answers.user_id', $user->id);
    }

    public function countLawyerAnsweredQuestions(Lawyer $lawyer)
    {
        return $lawyer->questionsAnswered()->published()->count();
    }

    public function takeLawyerAnsweredQuestions(Lawyer $lawyer, $take = null, $with = null)
    {
        $with = $with ? $with : self::$with['default'];
        $take = $take ? $take : config('site.user.question.take', self::DEFAULT_TAKE);
        $tQst = (new Question)->getTable();
        return $lawyer->questionsAnswered()->published()->with($with)->orderBy($tQst . '.created_at', 'desc')->take($take)->get();
    }

    public function paginateLawyerAnsweredQuestions(Lawyer $lawyer, $page = null, $with = null)
    {
        $page = $page ? $page : config('site.user.question.page', self::DEFAULT_PAGE);
        $with = $with ? $with : self::$with['default'];
        $tQst = (new Question)->getTable();
        return $lawyer->questionsAnswered()->published()->orderBy($tQst . '.created_at', 'desc')->with($with)->paginate($page);
    }

    public function countAnsweredSpecializations(Lawyer $lawyer, $category)
    {
        $tQst = (new Question)->getTable();
        return $lawyer->questionsAnswered()->where($tQst . '.category_law_id', $category)->count();
    }

}