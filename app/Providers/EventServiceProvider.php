<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        // \App\Events\Question\QuestionCreate::class => [],
        // \App\Events\Document\DocumentCreate::class => [],
        // \App\Events\Call\CallCreate::class => [],
        // \App\Events\Question\QuestionExpertise::class => [],
        // \App\Events\Answer\AnswerCreate::class => [],
        // \App\Events\Answer\AnswerIs::class => [],
        // \App\Events\Chat\ChatMessage::class => [],
        // \App\Events\Complaint\ComplaintCreate::class => [],
        // \App\Events\Like\LikeCreate::class => [],
        // \Artem328\LaravelYandexKassa\Events\BeforeCheckOrderResponse::class => [],
        // \Artem328\LaravelYandexKassa\Events\BeforePaymentAvisoResponse::class => []
    ];

    /**
     * Подпискчики
     * @var array
     */
    protected $subscribe = [
        \App\Listeners\UserEventSubscriber::class,
        \App\Listeners\PaymentServiceSubscriber::class,
        // \App\Listeners\YandexKassaSubscripe::class,
        // \App\Listeners\EventsSubscribe::class,
    ];

    /**
     * Register any other events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
