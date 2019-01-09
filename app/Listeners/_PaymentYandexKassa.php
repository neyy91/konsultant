<?php

namespace App\Listeners;

use Auth;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Artem328\LaravelYandexKassa\Events\BeforeCheckOrderResponse;
use App\Models\Pay;

/**
 * Добавление информации об оплате и проверка.
 */
class PaymentYandexKassa
{

    /**
     * Запуск события.
     *
     * @param  BeforeCheckOrderResponse  $event
     * @return void
     */
    public function handle(BeforeCheckOrderResponse $event)
    {
        $serviceType = $event->request->get('service_type');
        $serviceId = $event->request->get('service_id');
        if (!in_array($serviceType, Pay::getServiceTypes()) || !$serviceId) {
            $event->responseParameters['code'] = 100;
            $event->responseParameters['message'] = trans('pay.message.service_not_found');
            return $event->responseParameters;
        }

        $pay = new Pay();
        $pay->status = Pay::STATUS_PAY;
        $pay->service_type = $serviceType;
        $pay->service_id = $serviceId;
        $pay->cost = $event->request->get('orderSumAmount');
        $pay->user()->associate(Auth::user());

        if (!$pay->save()) {
            $event->responseParameters['code'] = 100;
            $event->responseParameters['message'] = trans('pay.message.pay_not_save');
            return $event->responseParameters;
        }

        if (!$pay->service) {
            $event->responseParameters['code'] = 100;
            $event->responseParameters['message'] = trans('pay.message.service_not_found');
            return $event->responseParameters;
        }

        return null;
    }
}
