<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Libs\EventTypeDefine;


/**
 * События на сайте.
 */
class Event extends Model implements EventTypeDefine
{
    const UPDATED_AT = null;

    protected $fillable = ['type'];

    public function getParamsAttribute($value)
    {
        return $value ? unserialize($value) : [];
    }

    public function setParamsAttribute($value)
    {
        $this->attributes['params'] = serialize($value);
    }

    public function getMessageAttribute()
    {
        switch ($this->type) {
            case self::TYPE_CLARIFY_ADD:
                return trans('event.types.clarify_add.message', [
                    'user' => $this->entity->to->user->display_name,
                    'url' => route($this->entity->to_type . '.clarify', [$this->entity->to_type => $this->entity->to, 'clarify' => $this->entity]),
                    'to_service' => trans('event.to_service.' . $this->type),
                    'title' => $this->owner->title,
                ]);
                break;
            case self::TYPE_NEW_ANSWERS:
                $answer_actions = trans('event.answer_actions.' . isset($this->params['new']) && $this->params['new'] === false) ? 'update_answer' : 'answer';
                return trans('event.types.new_answers.message', [
                    'status' => $this->entity->from->statusLabel ?: trans('lawyer.about'),
                    'lawyer_url' => route('lawyer', ['lawyer' => $this->entity->from]),
                    'user' => $this->entity->from->user->display_name,
                    'url' => route($this->entity->to_type . '.answer', [$this->entity->to_type => $this->entity->to, 'answer' => $this->entity]),
                    'action' => $answer_actions,
                    'title' => $this->owner && $this->owner->title ? $this->owner->title : trans('app.error_title'),
                ]);
                break;
            case self::TYPE_EXPERTISE:
                return trans('event.types.expertise.message', [
                    'lawyer_url' => route('lawyer', ['lawyer' => $this->entity->from]),
                    'user' => $this->entity->from->user->display_name,
                    'url' => route($this->entity->to_type . '.answer', [$this->entity->to_type => $this->entity->to, 'answer' => $this->entity]),
                    'title' => $this->owner && $this->owner->title ? $this->owner->title : trans('app.error_title'),
                ]);
                break;

            case self::TYPE_ANSWER_IS:
                return trans('event.types.answer_is.message', [

                ]);
        }
        return $message;
    }

    /**
     * Сущность события.
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function entity()
    {
        return $this->morphTo('entity');
    }

    /**
     * Владелец сущности.
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
     * Подписки.
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function subscribes()
    {
        return $this->hasMany(Subscribe::class, 'owner_id', 'owner_id')->where('owner_type', '=', $this->owner_type);
    }
}
