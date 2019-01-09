<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


/**
 * Специализации юриста.
 */
class Specialization extends Model
{
    /**
     * @var boolean
     */
    public $timestamps = false;

    /**
     * Юрист.
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToOne
     */
    public function user()
    {
        return $this->belongsToOne(Lawyer::class);
    }

    /**
     * Категории.
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToOne
     */
    public function categoryLaw()
    {
        return $this->belongsToOne(CategoryLaw::class);
    }
}
