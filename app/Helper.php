<?php 

namespace App;

use \Gate;
use \Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;

use App\Libs\FileHelper;
use App\Models\User;
use App\Models\Clarify;


class Helper
{
    use FileHelper;

    const RATING_STAT = 1.96;

    /**
     * Генерация slug.
     * @param  string $string
     * @param  array  $addToEnd
     * @return string
     */
    public static function slug($string, array $addToEnd = [])
    {
        $separator = config('site.slug.separator', '-');
        $toEnd = (!empty($addToEnd) ? $separator . implode($separator, $addToEnd) : '') . config('site.slug.suffix', '');
        $limit = config('site.slug.limit', 100) - Str::length($toEnd);
        $slug = rtrim(Str::limit(Str::slug($string, $separator), $limit, ''), $separator);
        $result = $slug . $toEnd;
        return $result;
    }

    /**
     * В нижний регистр заглавие.
     * @param  string $string
     * @return string
     */
    public static function lcfirst($string)
    {
        return mb_strtolower(mb_substr($string, 0, 1, 'UTF-8'), 'UTF-8') . mb_substr($string, 1);
    }

    /**
     * Получение where запроса.
     * @param  array  $values
     * @param  array  $type
     * @return array
     */
    public static function getWhereFromRequest(array $values, array $type = [])
    {
        $result = [];
        foreach ($values as $key => $value) {
            if ($value === '' || $value === null) {
                continue;
            }
            if (isset($type[$key])) {
                $result[] = [
                    isset($type[$key]['field']) ? $type[$key]['field'] : $key,
                    isset($type[$key]['condition']) ? $type[$key]['condition'] : '=',
                    isset($type[$key]['value']) ? str_replace(':value', $value, $type[$key]['value']) : $value,
                ];
            }
            else {
                $result[] = [
                    $key,
                    '=',
                    $value
                ];
            }
        }
        return $result;
    }

    /**
     * Получение модели из значений запроса.
     * @param string|mixed $model
     * @param array $inputs
     * @param array $inputs
     * @param array $type
     * @param array $with
     * @param array $order
     * @return \Illuminate\Database\Query\Builder
     */
    public static function getRequestModel($model, array $inputs, array $fields, array $type = [], $defaultOrder = ['order' => ['created_at', 'desc']])
    {
        $where = self::getWhereFromRequest(Arr::only($inputs, $fields), $type);
        if (is_string($model)) {
            $getModel = $model::where($where);
        }
        else {
            $getModel = $model->where($where);
        }
        $orderName = array_keys($defaultOrder)[0];
        $orderBy = isset($inputs[$orderName]) && is_array($inputs[$orderName]) ? $inputs[$orderName] : $defaultOrder[$orderName];
        $getModel->orderBy($orderBy[0], $orderBy[1]);
        return $getModel;
    }

    /**
     * Получение списка часов и минут с шагом.
     * @param  integer $from в часах
     * @param  integer $to в часах
     * @param  integer $step в минутах
     * @return array
     */
    public static function times($from = 0, $to = 23, $step = 10)
    {
        $times = [];
        for ($h = $from; $h < $to; $h++) { 
            for ($m = 0; $m  < 60; $m+=$step) { 
                $times[] = $h + $m/100;
            }
        }
        return $times;
    }

    /**
     * Преобразование массива вещественного числа в виде времени H:i
     * @param  array  $times
     * @return array
     */
    public static function floatToTime(array $times)
    {
        $times = array_combine($times, $times);
        return array_map(function($value) {
            $h = floor($value);
            $m = ($value - $h)*100;
            return ($h < 10 ? '0' . $h: $h) . ':' . ($m <= 9 ? '0' . $m : $m);
        }, $times);
    }

    public static function rating($up, $down)
    {
        if (!$up) {
            return 0;
        }
        $z = self::RATING_STAT;
        $n = $up + $down;
        $phat = 1.0 * $up/$n;
        // TODO: удалить умножение на 10.
        return round(10*(($phat + $z*$z/(2*$n) - $z*sqrt(($phat*(1 - $phat) + $z*$z/(4*$n))/$n))/(1 + $z*$z/$n)), 1);
    }

    /**
     * Рейтинг если выбран.
     * @param  integer $selected
     * @param  integer $all
     * @return integer
     */
    public static function ratingSelected($selected, $all)
    {
        return self::rating($selected, $all - $selected);
    }

}