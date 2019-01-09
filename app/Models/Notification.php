<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Libs\EventTypeDefine;


/**
 * Уведомления.
 */
class Notification extends Model implements EventTypeDefine
{

    /**
     * Отключение даты обновления.
     */
    const UPDATED_AT = null;

    protected $primaryKey = ['user_id', 'type'];

    public $incrementing = false;

    protected $fillable = ['type'];


    /**
     * Группы опопвещения.
     */
    public static $group = [
        'site_newsletter' => [
            self::TYPE_TIPS_ADVICE_LAWYERS,
            self::TYPE_NEWS,
        ],
        'ordered_services' => [
            self::TYPE_CHAT_MESSAGES,
            self::TYPE_NEW_ANSWERS,
        ],
        'notifications' => [
            self::TYPE_CONSULTATIONS_CITY,
            self::TYPE_CONSULTATIONS_SPECIALIZATION,
            self::TYPE_VOTE_POST_COMMENTS,
            // self::TYPE_CHANGE_BALANCE,
            self::TYPE_CLARIFY_ADD,
            // self::TYPE_LEADS_EVENT,
            self::TYPE_PERSONAL_CONSULTATIONS,
            self::TYPE_VOTE_ANSWERS,
            self::TYPE_COMPLAINT,
            self::TYPE_MESSAGES_ADMIN,
            self::TYPE_CHAT_MESSAGES,
            self::TYPE_TELEPHONE_CONSULTATION,
        ],
        'admin' => [
            self::TYPE_CHAT_MESSAGES,
            self::TYPE_USER_REGISTRATION,
            self::TYPE_USER_COMPLAINT,
        ],
    ];

    public static $typeGroup = [
        'user' => ['site_newsletter', 'ordered_services'],
        'lawyer' => ['notifications'],
        'company' => ['notifications'],
        'admin' => ['site_newsletter', 'admin'],
    ];

    /**
     * Типы уведомлений.
     * @return array
     */
    public static function getTypes()
    {
        return [
            self::TYPE_TIPS_ADVICE_LAWYERS => trans('notification.types.tips_advice_lawyers'),
            self::TYPE_NEWS => trans('notification.types.news'),
            self::TYPE_CHAT_MESSAGES => trans('notification.types.chat_messages'),
            self::TYPE_NEW_ANSWERS => trans('notification.types.new_answers'),
            self::TYPE_CONSULTATIONS_CITY => trans('notification.types.consultations_city'),
            self::TYPE_CONSULTATIONS_SPECIALIZATION => trans('notification.types.consultations_specialization'),
            self::TYPE_VOTE_POST_COMMENTS => trans('notification.types.vote_post_comments'),
            // self::TYPE_CHANGE_BALANCE => trans('notification.types.change_balance'),
            self::TYPE_CLARIFY_ADD => trans('notification.types.clarify_add'),
            // self::TYPE_LEADS_EVENT => trans('notification.types.leads_event'),
            self::TYPE_PERSONAL_CONSULTATIONS => trans('notification.types.personal_consultations'),
            self::TYPE_VOTE_ANSWERS => trans('notification.types.vote_answers'),
            self::TYPE_COMPLAINT => trans('notification.types.complaint'),
            self::TYPE_MESSAGES_ADMIN => trans('notification.types.messages_admin'),
            self::TYPE_TELEPHONE_CONSULTATION => trans('notification.types.telephone_consultation'),
            self::TYPE_USER_REGISTRATION => trans('notification.types.user_registration'),
            self::TYPE_USER_COMPLAINT => trans('notification.types.user_complaint'),
        ];
    }

    /**
     * Пользователь.
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
