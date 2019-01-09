<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


/**
 * Подписка на обновления.
 */
class Subscribe extends Model
{
    const UPDATED_AT = null;

    /**
     * Подписка на владельца сущности(услуги).
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function owner()
    {
        return $this->morphTo('owner');
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
     * События подписки.
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function events()
    {
        return $this->hasMany(Event::class, 'owner_id', 'owner_id')->where('owner_type', '=', $this->owner_type);
    }

    public function scopeByOwner($query, $owner)
    {
        return $query->where('owner_type', '=', $owner::MORPH_NAME)->where('owner_id', '=', $owner->id);
    }

    public function scopeByUser($query, User $user)
    {
        return $query->where('user_id', '=', $user->id);
    }

}
