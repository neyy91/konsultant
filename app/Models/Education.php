<?php

namespace App\Models;

use Date;
use Illuminate\Database\Eloquent\Model;

use App\Libs\DateCreatedUpdated;


/**
 * Образование юриста.
 */
class Education extends Model
{
    use DateCreatedUpdated;

    /**
     * Короткое имя для morphMap.
     */
    const MORPH_NAME = 'education';

    /**
     * Название таблицы.
     * @return string
     */
    public function getTable()
    {
        return 'educations';
    }

    protected $fillable = ['country', 'city', 'university', 'faculty', 'year'];

    protected $casts = [
        'year' => 'integer',
        'checked' => 'boolean',
    ];

    /**
     * Юрист.
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function lawyer()
    {
        return $this->belongsTo(Lawyer::class);
    }

    /**
     * Список годов выпуска.
     * @return array
     */
    public static function getYearsList()
    {
        static $years = null;
        if (!$years) {
            $years1 = self::getYears();
            $years = array_combine($years1, $years1);
        }
        return $years;
    }

    /**
     * Массив годов выпуска с ключом и значением.
     * @return array
     */
    public static function getYears()
    {
        return range(Date::parse('-60 year')->year, Date::parse('+3 year')->year);
    }

    /**
     * Файл подтверждающий диплом.
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne
     */
    public function file()
    {
        return $this->morphOne(File::class, 'owner')->where('field', 'file');
    }

}
