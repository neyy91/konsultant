<?php 

namespace App\Repositories;

use \Str;

use App\Models\Question;
use App\Models\Theme;
use App\Models\City;
use App\Models\User;

/**
* Репозиторий для вопросов.
*/
class QuestionRepository
{

    /**
     * @var integer
     */
    const DEFAULT_PAGE = 10;

    /**
     * @var integer
     */
    const DEFAULT_TAKE = 10;


    /**
     * Получение всех городов.
     * @param  array  $options
     * @return array
     */
    public function getAll()
    {
        $options = array_merge($this->options, $options);
        return Question::setDefault()->get()->all();
    }

    /**
     * Получение списка вопросов.
     * @param  string $label
     * @param  string $key
     * @param  array  $options
     * @return array
     */
    public function getList($label = 'name', $key = 'id')
    {
        return Question::setDefault()->pluck($label, $key);
    }

    /**
     * Получения id категории права
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
     * Вопросы постранично.
     * @param  integer $pages
     * @param  array   $options
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function paginate($page = null)
    {
        $page = $page ? $page : config('site.question.page', self::DEFAULT_PAGE);
        return Question::setDefault()->paginate($page);
    }

    /**
     * Вопросы категории права постранично.
     * @param  CategoryLaw|integer $categoryLaw
     * @param  integer              $page
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function paginateByCategory($categoryLaw, $page = null)
    {
        $page = $page ? $page : config('site.question.page', self::DEFAULT_PAGE);
        return Question::setDefault()->where('category_law_id', $this->getId($categoryLaw))->paginate($page);
    }

    /**
     * Вопросы категории права постранично.
     * @param  Theme   $theme
     * @param  integer $page
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function paginateByTheme(Theme $theme, $page = null)
    {
        $page = $page ? $page : config('site.question.page', self::DEFAULT_PAGE);
        return $theme->questions()->setDefault()->paginate($page);
    }

    /**
     * Вопросы из города постранично.
     * @param  City|integer $city
     * @param  integer      $page
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function paginateByCity($city, $page = null)
    {
        $page = $page ? $page : config('site.question.page', self::DEFAULT_PAGE);
        return Question::setDefault()->where('city_id', $this->getId($city))->paginate($page);
    }

    /**
     * Вопросы из города постранично.
     * @param  User|integer $user
     * @param  integer      $page
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function paginateByUser($user, $page = null)
    {
        $page = $page ? $page : config('site.user.list.page', self::DEFAULT_PAGE);
        return Question::setDefault()->where('user_id', $this->getId($user))->paginate($page);
    }

    /**
     * Поиск по названию.
     * @param  [type] $q    [description]
     * @param  [type] $page [description]
     * @return [type]       [description]
     */
    public function paginateBySearch($q, $page = null)
    {
        $page = $page ? $page : config('site.user.list.page', self::DEFAULT_PAGE);
        return Question::setDefault()->where('title', 'like', "%{$q}%")->paginate($page);
    }


    /**
     * Вопросы категории права.
     * @param  CategoryLaw|integer $categoryLaw
     * @param  integer             $take
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function take($take = null)
    {
        $take = $take ? $take : config('site.question.take', self::DEFAULT_TAKE);
        return Question::setDefault()->take($take)->get();
    }


    /**
     * Вопросы категории права.
     * @param  CategoryLaw|integer $categoryLaw
     * @param  integer             $take
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function takeByCategory($categoryLaw, $take = null)
    {
        $take = $take ? $take : config('site.question.take', self::DEFAULT_TAKE);
        return Question::setDefault()->where('category_law_id', $this->getId($categoryLaw))->take($take)->get();
    }

    /**
     * Вопросы темы.
     * @param  Theme   $theme
     * @param  integer $take
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function takeByTheme(Theme $theme, $take = null)
    {
        $take = $take ? $take : config('site.question.take', self::DEFAULT_TAKE);
        return $theme->questions()->setDefault()->take($take)->get();
    }


    /**
     * Вопросы по городам
     * @param  City|integer $city
     * @param  integer      $take
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function takeByCity($city, $take = null)
    {
        $take = $take ? $take : config('site.question.take', self::DEFAULT_TAKE);
        return Question::setDefault()->where('city_id', $this->getId($city))->take($take)->get();
    }

    /**
     * Вопросы пользователя.
     * @param  User|integer $user
     * @param  integer      $take
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function takeByUser($user, $take = null)
    {
        $take = $take ? $take : config('site.user.order.take', self::DEFAULT_TAKE);
        return Question::setDefault()->where('user_id', $this->getId($user))->take($take)->get();
    }

}