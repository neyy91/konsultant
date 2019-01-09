<form action="{{ env('APP_ENV') === 'local' ? route('yandex.kassa.pay.test') : yandex_kassa_form_action()}}" method="{{yandex_kassa_form_method()}}" class="form-horizontal">
    @if (env('APP_ENV') === 'local')
        {{ csrf_field() }}
    @endif
    <input name="scId" type="hidden" value="{{yandex_kassa_sc_id()}}">
    <input name="shopId" type="hidden" value="{{yandex_kassa_shop_id()}}">
    <input name="customerNumber" type="hidden" value="{{ $user->id }}">
    <input name="paymentType" value="AC" type="hidden">
    <input type="hidden" name="orderNumber" value="{{ $order }}">
    <input type="hidden" name="cps_email" value="{{ $user->email }}">
    @if ($user->telephone)
        <input type="hidden" name="cps_phone" value="{{ $user->telephone }}">
    @endif
    @if (isset($url))
        <input type="hidden" name="shopDefaultUrl" value="{{ $url['default'] }}">
        @if (isset($url['success']))
            <input type="hidden" name="shopSuccessURL" value="{{ $url['success'] }}">
        @endif
        @if (isset($url['fail']))
            <input type="hidden" name="shopFailURL" value="{{ $url['fail'] }}">
        @endif
    @endif
    <div class="form-group">
        <label class="control-label col-sm-3">{{trans('yandex_kassa::form.label.payment_type')}}</label>
        <div class="col-sm-9">
            @foreach(yandex_kassa_payment_types() as $key => $paymentTypeCode)
            <div class="radio">
                <label>
                    <input type="radio" name="paymentType" value="{{$paymentTypeCode}}"@if ($key == 0)
                        checked @endif>
                    {{trans('yandex_kassa::payment_types.' . $paymentTypeCode)}}
                </label>
            </div>
            @endforeach
        </div>
    </div>
    <div class="form-group">
        <label for="payValue" class="control-label col-sm-3">@lang('pay.pay_value')</label>
        <div class="col-xs-10 col-sm-3">
            <div class="input-group">
            @if (is_array($pay['value']))
                <select name="sum" id="payValue" class="form-control input-lg" required>
                    @foreach ($pay['value'] as $pay)
                        <option value="{{ round(float, 2) }}">{{ $pay }}</option>
                    @endforeach
                </select>
                <span class="input-group-addon"><span class="glyphicon glyphicon-rub toggle-tooltip" aria-hidden="true" title="@lang('app.ruble')"></span></span>
            @else
                    <input name="sum" type="number" id="payValue" class="form-control input-lg" @if ($pay['readonly']) readonly @endif value="{{ round($pay['value'], 2) }}" step="10" required>
                    <span class="input-group-addon"><span class="glyphicon glyphicon-rub toggle-tooltip" aria-hidden="true" title="@lang('app.ruble')"></span></span>
            @endif
            </div>
        </div>
        <div class="col-xs-12 col-sm-4">
            <button type="submit" class="btn btn-primary btn-lg">{{trans('pay.pay_service.' . $type)}}</button>
        </div>
    </div>
</form>