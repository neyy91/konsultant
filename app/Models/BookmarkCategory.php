<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookmarkCategory extends Model
{

    /**
     * @var array
     */
    protected $fillable = ['name'];

    /**
     * @var boolean
     */
    public $timestamps = false;

    /**
     * Закладки.
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function bookmarks()
    {
        return $this->hasMany(Bookmark::class, 'category_id');
    }
}
