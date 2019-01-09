<?php 

namespace App\Repositories;

use App\Models\Call;
use App\Models\City;

/**
* Репозиторий для консультации по телефону.
*/
class CallRepository
{

    /**
     * @var integer
     */
    const DEFAULT_PAGE = 10;
    const DEFAULT_TAKE = 10;

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
     * Консультации по телефону постранично.
     * @param  integer $pages
     * @param  array   $options
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function paginate($page = null)
    {
        $page = $page ? $page : config('site.call.page', self::DEFAULT_PAGE);
        return Call::setDefault()->paginate($page);
    }

    /**
     * Консультации по телефону из города постранично.
     * @param  City|integer $city
     * @param  integer      $page
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function paginateByCity($city, $page = null)
    {
        $page = $page ? $page : config('site.call.page', self::DEFAULT_PAGE);
        return Call::setDefault()->where('city_id', $this->getId($city))->paginate($page);
    }

    /**
     * Консультации по телефону пользователя.
     * @param  User|integer $user
     * @param  integer      $page
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function paginateByUser($user, $page = null)
    {
        $page = $page ? $page : config('site.user.order.page', self::DEFAULT_PAGE);
        return Call::setDefault()->where('user_id', $this->getId($user))->paginate($page);
    }

    /**
     * Консультации по телефону  пользователя.
     * @param  User|integer $user
     * @param  integer      $take
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function takeByUser($user, $take = null)
    {
        $take = $take ? $take : config('site.user.order.take', self::DEFAULT_TAKE);
        return Call::setDefault()->where('user_id', $this->getId($user))->take($take)->get();
    }

}