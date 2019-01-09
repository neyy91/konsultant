<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Artem328\LaravelYandexKassa\Events\BeforeCancelOrderResponse;
use Artem328\LaravelYandexKassa\Events\BeforeCheckOrderResponse;
use Artem328\LaravelYandexKassa\Events\BeforePaymentAvisoResponse;
use Artem328\LaravelYandexKassa\Requests\YandexKassaRequest;
use App\Models\Pay;
use App\Models\Question;
use App\Models\Document;
use App\Models\Call;
use App\Models\Lawyer;

class YandexKassaAppController extends Controller
{
    private $routes = [
        Question::MORPH_NAME => ['question.view', 'question'],
        Document::MORPH_NAME => ['document.view', 'document'],
        Call::MORPH_NAME => ['call.view', 'call'],
        Lawyer::MORPH_NAME => ['lawyer.view', 'lawyer'],
    ];

    /**
     * Параметры по умолчанию.
     */
    protected function getDefaultResponseParameters(YandexKassaRequest $request)
    {
        return [
            'performedDatetime' => $request->get('requestDatetime'),
            'code' => $request->isValidHash() ? 0 : 1,
            'invoiceId' => $request->get('invoiceId'),
            'shopId' => yandex_kassa_shop_id()
        ];
    }

    public function payLocal(YandexKassaRequest $request)
    {
        if (env('APP_ENV') === 'prod') {
            return abort(404);
        }
        event(new BeforeCheckOrderResponse($request, $this->getDefaultResponseParameters($request)));
        event(new BeforePaymentAvisoResponse($request, $this->getDefaultResponseParameters($request)));
        $pay = Pay::findOrFail($request->get('orderNumber'));

        return redirect($request->get('shopSuccessURL'));
    }

    public function fail(Request $request)
    {
        return view('errors.simple', [
            'title' => trans('pay.titles.fail'),
            'message' => trans('pay.message.fail'),
            'back' => route('home'),
        ]);
    }

    public function success(Request $request)
    {
        $pay = Pay::findOrFail($request->get('orderNumber'));
        $service = $pay->service;

        $id = $service::MORPH_NAME;
        if (isset($this->routes[$id])) {
            return redirect()->route($this->routes[$id][0], [$this->routes[$id][1] => $service])->with('success', trans($service::LANG_ID . '.message.payment_success'));
        }

        return redirect()->route('home');
    }
}
