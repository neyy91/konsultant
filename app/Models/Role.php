<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Роли.
 */
class Role extends Model
{

    const ADMIN = 'admin';
    const SUPPORT = 'support';
    const MODERATOR = 'moderator';

    /**
     * Отключение даты обновления.
     */
    const UPDATED_AT = null;

    /**
     * Первичный ключ.
     * @var array
     */
    protected $primaryKey = ['user_id', 'role'];

    /**
     * Автоинкремент отключаем.
     * @var boolean
     */
    public $incrementing = false;


    /**
     * Автозаполнение.
     * @var array
     */
    protected $fillable = ['role'];

    /**
     * Пользователь роли.
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        $this->belongsTo(User::class);
    }

    /**
     * Названия ролей.
     * @return array
     */
    public static function getRoleNames()
    {
        return [
            self::ADMIN => trans('user.roles.admin'),
            self::SUPPORT => trans('user.roles.support'),
            self::MODERATOR => trans('user.roles.moderator'),
        ];
    }

    /**
     * Название роли.
     * @return string|null
     */
    public function getRoleLabelAttribute()
    {
        $roles = self::getRoleNames();
        return $this->role && $roles[$this->role] ? $role[$this->role] : $this->role;
    }
}
