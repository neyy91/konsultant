<?php

namespace App\Listeners;

use Artem328\LaravelYandexKassa\Events\BeforeCheckOrderResponse;
use Artem328\LaravelYandexKassa\Events\BeforePaymentAvisoResponse;
use App\Models\Pay;


/**
 * События пользователя.
 */
class YandexKassaSubscripe
{

    /**
     * Проверка заказа.
     * @param  BeforeCheckOrderResponse $event
     * @return null|array
     */
    public function onCheckOrderResponse(BeforeCheckOrderResponse $event)
    {
        $orderNumber = $event->request->get('orderNumber');
        $pay = Pay::findOne($orderNumber);
        if (!$pay) {
            $event->responseParameters['code'] = 100;
            $event->responseParameters['message'] = trans('pay.message.pay_not_found');
            return $event->responseParameters;
        }

        $pay->status = Pay::STATUS_PAY;
        $pay->cost = $event->request->get('orderSumAmount');

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
        $orderNumber = $event->request->get('orderNumber');
        $pay = Pay::findOne($orderNumber);
        if (!$pay) {
            $event->responseParameters['code'] = 100;
            $event->responseParameters['message'] = trans('pay.message.pay_not_found');
            return $event->responseParameters;
        }

        $pay->status = Pay::STATUS_PAYED;

        if (!$pay->save()) {
            $event->responseParameters['code'] = 100;
            $event->responseParameters['message'] = trans('pay.message.pay_not_save');
            return $event->responseParameters;
        }

        return null;
    }

    /**
     * Subscribe.
     * @param  Illuminate\Events\Dispatcher $events
     */
    public function subscribe($events)
    {
        $events->listen(BeforeCheckOrderResponse::class, self::class . '@onCheckOrderResponse');
        $events->listen(BeforePaymentAvisoResponse::class, self::class . '@onPaymentAvisoResponse');
    }

    
}
