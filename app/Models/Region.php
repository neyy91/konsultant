<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Регионы, области.
 */
class Region extends Model
{

    /**
     * @var boolean
     */
    public $timestamps = false;

    /**
     * @var array
     */
    protected $fillable = ['name'];

    /**
     * Города региона.
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function cities()
    {
        return $this->hasMany(City::class);
    }

}
