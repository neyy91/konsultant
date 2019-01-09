<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Libs\DateCreatedUpdated;


/**
 * Закладки пользователей.
 */
class Bookmark extends Model
{

    use DateCreatedUpdated;

    protected $guarded = ['*'];

    /**
     * Ссылка на сущность
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    /**
     * Пользователь.
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Категория.
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo(BookmarkCategory::class, 'category_id');
    }
}
