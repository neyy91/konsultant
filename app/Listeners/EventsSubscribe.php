<?php

namespace App\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\Models\Event;


/**
 * События.
 */
class EventsSubscribe
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    protected function onEvent($type, $entity, $user, $owner = null, $params = [])
    {
        $myEvent = new Event(['type' => $type]);
        $myEvent->entity()->associate($entity);
        $myEvent->user()->associate($user);
        if ($owner) {
            $myEvent->owner()->associate($owner);
        }
        if (!empty($params)) {
            $myEvent->params = serialize($params);
        }
        $myEvent->save();
        return $myEvent;
    }

    public function onClarifyCreate($event)
    {
        $this->onEvent(Event::TYPE_CLARIFY_ADD, $event->clarify, $event->user, $event->clarify->to);
    }

    public function onAnswerCreate($event)
    {
        $this->onEvent(Event::TYPE_NEW_ANSWERS, $event->answer, $event->user, $event->answer->parent, ['new' => true]);
    }

    public function onQuestionExpertise($event)
    {
        $this->onEvent(Event::TYPE_EXPERTISE, $event->params['expertise'], $event->user, $event->question);
    }

    public function onAnswerIs($event)
    {
        $this->onEvent(Event::TYPE_ANSWER_IS, $event->answer, $event->user, $event->answer->parent);
    }

    public function onAnswerUpdate($event)
    {
        $this->onEvent(Event::TYPE_NEW_ANSWERS, $event->answer, $event->user, $event->answer->parent, ['new' => false]);
    }

    public function onLikeCreate($event)
    {
        $this->onEvent(Event::TYPE_VOTE_POST_COMMENTS, $event->entity, $event->user, $event->like->user);
    }

    public function subscribe($events)
    {
        // $events->listen(\App\Events\Call\CallCreate::class, self::class . '@onCallCreate');
        // $events->listen(\App\Events\Document\DocumentCreate::class, self::class . '@onDocumentCreate');
        // $events->listen(\App\Events\Question\QuestionCreate::class, self::class . '@onQuestionCreate');
        $events->listen(\App\Events\Clarify\ClarifyCreate::class, self::class . '@onClarifyCreate');
        $events->listen(\App\Events\Answer\AnswerCreate::class, self::class . '@onAnswerCreate');
        $events->listen(\App\Events\Question\QuestionExpertise::class, self::class . '@onQuestionExpertise');
        $events->listen(\App\Events\Answer\AnswerIs::class, self::class . '@onAnswerIs');
        $events->listen(\App\Events\Answer\AnswerUpdate::class, self::class . '@onAnswerUpdate');
        $events->listen(\App\Events\Like\LikeCreate::class, self::class . '@onLikeCreate');
    }

}
