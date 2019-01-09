<?php

namespace App\Listeners;

use Artem328\LaravelYandexKassa\Events\BeforeCheckOrderResponse;
use Artem328\LaravelYandexKassa\Events\BeforePaymentAvisoResponse;
use App\Events\Question\QuestionCreate;
use App\Events\Document\DocumentCreate;
use App\Events\Call\CallCreate;
use App\Events\Answer\AnswerIs;
use App\Models\Question;
use App\Models\Document;
use App\Models\Call;
use App\Models\Pay;


/**
 * События пользователя.
 */
class PaymentServiceSubscriber
{

    /**
     * При создании вопроса.
     * @param  QuestionCreate $event
     */
    public function onQuestionCreate(QuestionCreate $event)
    {
        $question = $event->question;
        if ($question->pay === Question::PAY_FREE) {
            return;
        }

        $pay = new Pay();
        $pay->status = $question->payCost === 0 ? Pay::STATUS_SUCCESS : Pay::STATUS_START;
        $pay->service()->associate($question);
        $pay->cost = $question->payCost;
        $pay->user()->associate($event->user);
        if (!$pay->save()) {
            return abort(500);
        }
    }

    /**
     * При создание документа.
     * @param  DocumentCreate $event
     */
    public function onDocumentCreate(DocumentCreate $event)
    {
        $pay = new Pay();
        $pay->status = Pay::STATUS_START;
        $pay->service()->associate($event->document);
        $pay->user()->associate($event->user);
        $pay->cost = $event->document->cost;

        if (!$pay->save()) {
            return abort(500);
        }
    }

    /**
     * При заказе звонка.
     * @param  CallCreate $event
     */
    public function onCallCreate(CallCreate $event)
    {
        $call = $event->call;
        if ($call->pay === Call::PAY_FREE) {
            return;
        }

        $pay = new Pay();
        $pay->status = $call->payCost === 0 ? Pay::STATUS_SUCCESS : Pay::STATUS_START;
        $pay->service()->associate($call);
        $pay->cost = $call->payCost;
        $pay->user()->associate($event->user);
        if (!$pay->save()) {
            return abort(500);
        }
    }

    /**
     * Проверка заказа.
     * @param  BeforeCheckOrderResponse $event
     * @return null|array
     */
    public function onCheckOrderResponse(BeforeCheckOrderResponse $event)
    {
        $orderNumber = $event->request->get('orderNumber');
        $pay = Pay::find($orderNumber);
        if (!$pay) {
            $event->responseParameters['code'] = 100;
            $event->responseParameters['message'] = trans('pay.message.pay_not_found');
            return $event->responseParameters;
        }
        $service = $pay->service;

        $sum = $event->request->get('orderSumAmount') ?: $event->request->get('sum');
        if (!$sum || $sum && $sum < $service->payCost) {
            $event->responseParameters['code'] = 100;
            $event->responseParameters['message'] = trans('pay.message.not_full_paid');
            return $event->responseParameters;
        }
        $pay->cost = $sum;

        $pay->status = Pay::STATUS_PAY;

        if (!$pay->save()) {
            $event->responseParameters['code'] = 100;
            $event->responseParameters['message'] = trans('pay.message.pay_not_save');
            return $event->responseParameters;
        }

        return null;
    }

    /**
     * Оплата заказа.
     * @param  BeforePaymentAvisoResponse $event
     * @return null|array
     */
    public function onPaymentAvisoResponse(BeforePaymentAvisoResponse $event)
    {
        $response = null;
        $orderNumber = $event->request->get('orderNumber');
        $pay = Pay::find($orderNumber);
        if (!$pay) {
            $event->responseParameters['code'] = 100;
            $event->responseParameters['message'] = trans('pay.message.pay_not_found');
            return $event->responseParameters;
        }

        $pay->status = Pay::STATUS_PAYED;

        if (!$pay->save()) {
            $event->responseParameters['code'] = 100;
            $event->responseParameters['message'] = trans('pay.message.pay_not_saved');
            return $event->responseParameters;
        }

        /*
         * Дейсвие после оплаты.
         */
        $actionAfter = 'afterPaymentAviso' . ucfirst($pay->service::MORPH_NAME);
        if (method_exists($this, $actionAfter)) {
            $response = $this->$actionAfter($pay, $event);
        }

        return $response;
    }

    /**
     * После оплаты вопроса.
     * @param  Pay                        $pay
     * @param  BeforePaymentAvisoResponse $event
     * @return null|array
     */
    protected function afterPaymentAvisoQuestion(Pay $pay, BeforePaymentAvisoResponse $event)
    {
        $question = $pay->service;
        if ($question->status === Question::STATUS_UNPUBLISHED) {
            $question->status = Question::STATUS_IN_WORK;
            if (!$question->save()) {
                $event->responseParameters['code'] = 100;
                $event->responseParameters['message'] = trans('app.messages.data_not_saved');
                return $event->responseParameters;
            }
        }

        return null;
    }

    /**
     * После оплаты звонка.
     * @param  Pay                        $pay
     * @param  BeforePaymentAvisoResponse $event
     * @return null|array
     */
    protected function afterPaymentAvisoCall(Pay $pay, BeforePaymentAvisoResponse $event)
    {
        $call = $pay->service;
        if ($call->status === Call::STATUS_UNPUBLISHED) {
            $call->status = Call::STATUS_IN_WORK;
            if (!$call->save()) {
                $event->responseParameters['code'] = 100;
                $event->responseParameters['message'] = trans('app.messages.data_not_saved');
                return $event->responseParameters;
            }
        }

        return null;
    }

    /**
     * Выбор ответа клиентом.
     * @param  AnswerIs $event
     */
    public function onAnswerIs(AnswerIs $event)
    {
        $service = $event->answer->to;
        if ($service::MORPH_NAME === Document::MORPH_NAME && $event->answer->is) {
            $service->payment->cost = $service->payCost;
            $service->payment->save();
        }
    }

    /**
     * Subscribe.
     * @param  Illuminate\Events\Dispatcher $events
     */
    public function subscribe($events)
    {
        $events->listen(QuestionCreate::class, self::class . '@onQuestionCreate');
        $events->listen(DocumentCreate::class, self::class . '@onDocumentCreate');
        $events->listen(CallCreate::class, self::class . '@onCallCreate');
        // Yandex kassa
        $events->listen(BeforeCheckOrderResponse::class, self::class . '@onCheckOrderResponse');
        $events->listen(BeforePaymentAvisoResponse::class, self::class . '@onPaymentAvisoResponse');

        $events->listen(AnswerIs::class, self::class . '@onAnswerIs');
    }

}
