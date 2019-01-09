<?php

namespace App\Models;

use Auth;
use Date;
use Illuminate\Database\Eloquent\Model;

use Helper;
use App\Libs\DateCreatedUpdated;
use App\Models\User;

/**
 * Чат пользователя. Сообщения, если message not null; или чаты(диалоги), если message is null.
 */
class Chat extends Model
{

    use DateCreatedUpdated;

    /**
     * Короткое имя для morphMap.
     */
    const MORPH_NAME = 'chat';

    /**
     * Время просмотра.
     */
    const VIEWED_AT = 'viewed_at';

    /**
     * Исходящие и входящие сообщения.
     */
    const INCOMING = 'incoming';
    const OUTGOING = 'outgoing';

    /**
     * Что это?
     */
    const IS_MESSAGE = 'message'; // сообщение
    const IS_DIALOG = 'dialog'; // диалог

    /**
     * @var array
     */
    protected $fillable = ['message'];

    /**
     * Получение ключа для Route.
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    /**
     * Дата просмотра.
     * @param integer $value
     * @return \Jenssegers\Date\Date
     */
    public function getViewedAtAttribute($value)
    {
        return $value === null ? null : new Date($value);
    }

    /**
     * Дата просмотра формата short.
     * @return string
     */
    public function getViewedShortAttribute()
    {
        return $this->viewed_at === null ? null : $this->viewed_at->format(config('site.date.short', 'd.m.Y'));
    }
    
    /**
     * Дата просмотра формата middle.
     * @return string
     */
    public function getViewedMiddleAttribute()
    {
        return $this->viewed_at === null ? null : $this->viewed_at->format(config('site.date.middle', 'd.m.Y H:i'));
    }

    /**
     * Дата просмотра формата long.
     * @return string
     */
    public function getViewedLongAttribute()
    {
        return $this->viewed_at === null ? null : $this->viewed_at->format(config('site.date.long', 'j F Y, H:i'));
    }

    /**
     * Установка даты просмотра.
     * @param string|Date $value
     */
    public function setViewedAtAttribute($value)
    {
        $this->attributes['viewed_at'] = self::getDateFormatDBValue($value);
    }

    /**
     * О сообщении.
     * @return string
     */
    public function getAboutAttribute()
    {
        return $this->from->display_name . ' -> ' . $this->to->display_name . ' ' . $this->createdLong;
    }

    /**
     * Тип сообщения.
     * @param  User|null $me
     * @return string
     */
    public function getMessageType($me = null)
    {
        if (!$me) {
            $me = Auth::user();
        }
        return $this->to->id == $me->id ? self::INCOMING : self::OUTGOING;
    }

    /**
     * От кого.
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function from()
    {
        return $this->belongsTo(User::class, 'from_id');
    }

    /**
     * Кому.
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function to()
    {
        return $this->belongsTo(User::class, 'to_id');
    }

    /**
     * Отправка сообщения.
     * @param  string $message
     * @param  integer|App\Model\User $user
     * @param  integer|App\Model\User|null $me
     * @return boolean
     */
    public static function sendMessage($message, $user, $me = null)
    {
        $chat = new self(['message' => $message]);
        if (is_integer($user)) {
            $user = User::find($user);
        }
        if (!$me) {
            $me = Auth::user();
        }
        elseif(is_numeric($me)) {
            $me = User::find($me);
        }
        $chat->to()->associate($user);
        $chat->from()->associate($me);
        $chat->is = self::IS_MESSAGE;
        $chat->save();
        return $chat->save() ? $chat : null;
    }

    /**
     * Исходящие сообщения для пользователя.
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  User|null                             $me от пользователя.
     * @param  User|array|null                       $to пользователю
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOutgoing($query, $me = null, $to = null)
    {
        if (!$me) {
            $me = Auth::user();
        }
        if ($to) {
            if (is_array($to)) {
                $query->whereIn('to_id', $to);
            } else {
                $query->where('to_id', '=', $to->id);
            }
            
        }
        return $query->where('from_id', '=', $me->id);
    }

    /**
     * Входящие сообщения от пользователя.
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  User|null                             $me для пользователя.
     * @param  User|array|null                       $from от пользователя
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeIncoming($query, $me = null, $from = null)
    {
        if (!$me) {
            $me = Auth::user();
        }
        if ($from) {
            if (is_array($from)) {
                $query->whereIn('from_id', $from);
            }
            else {
                $query->where('from_id', '=', $from->id);
            }
        }
        return $query->where('to_id', '=', $me->id);
    }

    /**
     * Входящие и исходящие от пользователя.
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  User|null                             $user от пользователя
     * @param  User|null                             $me пользователю.
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSetUsers($query, $user, $me = null)
    {
        if (!$me) {
            $me = Auth::user();
        }
        return $query->where(function($query) use ($user, $me) {
            // исходящие
            return $query->outgoing($me, $user);
        })->orWhere(function($query) use ($user, $me) {
            // входящие
            return $query->incoming($me, $user);
        });
    }

    /**
     * Только сообщения.
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOnlyMessages($query)
    {
        return $query->where('is', '=', self::IS_MESSAGE);
    }

    /**
     * Только диалоги.
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOnlyDialogs($query)
    {
        return $query->where('is', '=', self::IS_DIALOG);
    }

    /**
     * Только диалоги пользователя.
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOnlyUserDialogs($query, $user = null)
    {
        if (!$user) {
            $user = Auth::user();
        }
        return $query->onlyDialogs()->where('from_id', '=', $user->id);
    }

    /**
     * Непросмотренные сообщения.
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeUnviewed($query)
    {
        return $query->whereNull(self::VIEWED_AT);
    }
}
