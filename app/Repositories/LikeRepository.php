<?php 

namespace App\Repositories;

use App\Models\Like;
use App\Models\User;
use App\Models\Lawyer;
use App\Models\Answer;
use App\Models\Question;

/**
* Репозиторий для оценок.
*/
class LikeRepository
{

    /**
     * @var integer
     */
    const DEFAULT_PAGE = 10;
    const DEFAULT_TAKE = 4;

    /**
     * With по умолчанию.
     * @var array
     */
    static $with = [
        'default' => ['user', 'entity'],
    ];

    public function countLikedLawyerQuestionAnswer(Lawyer $lawyer, $rating = null)
    {
        $liked =  $lawyer->liked();
        if ($rating && is_numeric($rating)) {
            $liked->where('rating', $rating);
        }
        return $liked->count();
    }

    /**
     * Последние отзывы пользователя.
     * @param  User   $user
     * @return Collection
     */
    public function latestLikedLawyerQuestionAnswer(Lawyer $lawyer, $take = null, $with = null)
    {
        $take = $take ? $take : config('site.user.like.take', self::DEFAULT_TAKE);
        $with = $with ? $with : self::$with['default'];
        return $lawyer->liked()->latest()->with($with)->take($take)->get();
    }

    public function paginateLikedLawyerQuestionAnswer(Lawyer $lawyer, $page = null, $with = null)
    {
        
        $page = $page ? $page : config('site.user.like.page', self::DEFAULT_PAGE);
        $with = $with ? $with : self::$with['default'];
        return $lawyed->liked()->orderBy('created_at', 'desc')->with($with)->paginate($page);
    }
}