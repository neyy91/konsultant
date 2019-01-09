<?php 

namespace App\Repositories;

use App\Models\City;

/**
* Репозиторий для городов.
*/
class CityRepository
{

    const DEFAULT_COUNT = 10;

    /**
     * Получение всех городов.
     * @param  array  $options
     * @return array
     */
    public function getAllDefault()
    {
        return City::setDefault()->get()->all();
    }

    /**
     * Получение всех городов.
     * @param  array  $options
     * @return array
     */
    public function getAll()
    {
        return City::orderBy('sort', 'asc')->get()->all();
    }

    /**
     * Список в виде масива [$key => $label].
     * @param  string $label
     * @param  string $key
     * @return array
     */
    public function getList($label = 'name', $key = 'id')
    {
        return City::setDefault()->pluck($label, $key);
    }

    protected function getCount($count = null)
    {
        if (!$count) {
            $count = config('site.city.count', self::DEFAULT_COUNT);
        }
        return $count;
    }

    public function take($count = null)
    {
        return City::setDefault()->take($this->getCount($count))->get()->all();
    }

}