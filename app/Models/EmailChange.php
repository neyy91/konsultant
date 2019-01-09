<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailChange extends Model
{
    const UPDATED_AT = null;

    protected $primaryKey = 'token';

    public $incrementing = false;

    public $fillable = ['email', 'token', 'user'];


    /**
     * Получение объекта пользователя.
     * @return \App\Models\User
     */
    public function getUserAttribute()
    {
        return User::find($this->user_id);
    }

    /**
     * Установка id пользователя.
     * @param \App\Models\User $value
     */
    public function setUserAttribute($value)
    {
        $this->attributes['user_id'] = $value->id;
    }
}
