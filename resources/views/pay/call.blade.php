{{-- 
    Список типов оплаты за звонок.
    @var App\Models\Call $call
--}}
@php
    $prices = config('site.call.pay');
    $pricesOld = config('site.call.pay_old');
@endphp

@macros(price($type, $prices, $pricesOld))
    @if (isset($pricesOld[$type])) <span class="price-old">{{ $pricesOld[$type] }}</span> <span class="price-new">{{ $prices[$type] }}</span> @else {{ $prices[$type] }} @endif <span class="glyphicon-rub price-volume"></span>
@endmacros

<div class="row pay-row pay-service checked-container pay-service-call">
    <div class="col-xs-12 col-sm-4">
        <label for="callPaySimple" class="panel panel-default pay-item checked-element" data-class="panel-default=panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title">@macros(select, 'pay', $call, ['type' => 'radio', 'form' => 'call', 'group' => false, 'wrap' => false, 'label' => false, 'items' => ['simple' => 'simple'], 'class' => 'checked-status']) @lang('call.pays.simple.label')</h3>
            </div>
            <div class="panel-body">
                @lang('call.pays.simple.description')
            </div>
            <div class="panel-footer">
                @macros(price, 'simple', $prices, $pricesOld)
            </div>
        </label>
    </div>
    <div class="col-xs-12 col-sm-4">
        <label for="callPayStandart" class="panel panel-primary pay-item checked-element" data-class="panel-success=panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title">@macros(select, 'pay', $call, ['type' => 'radio', 'form' => 'call', 'group' => false, 'wrap' => false, 'label' => false, 'items' => ['standart' => 'standart'], 'class' => 'checked-status']) @lang('call.pays.standart.label')</h3>
            </div>
            <div class="panel-body">
                @lang('call.pays.standart.description')
            </div>
            <div class="panel-footer">
                @macros(price, 'standart', $prices, $pricesOld)
            </div>
        </label>
    </div>
    <div class="col-xs-12 col-sm-4">
        <label for="callPayVip" class="panel panel-default pay-item checked-element" data-class="panel-default=panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title">@macros(select, 'pay', $call, ['type' => 'radio', 'form' => 'call', 'group' => false, 'wrap' => false, 'label' => false, 'items' => ['vip' => 'vip'], 'class' => 'checked-status']) @lang('call.pays.vip.label')</h3>
            </div>
            <div class="panel-body">
                @lang('call.pays.vip.description')
            </div>
            <div class="panel-footer">
                @macros(price, 'vip', $prices, $pricesOld)
            </div>
        </label>
    </div>
    <div class="col-xs-12 free-col">
        @macros(select, 'pay', $call, ['type' => 'radio', 'form' => 'call', 'group' => false, 'items' => ['free' => trans('call.pays.free.label')], 'class' => 'checked-status'])
        <small class="help-block">@lang('call.pays.free.description')</small>
    </div>
</div>