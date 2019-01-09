{{-- 
    Список типов оплаты за вопрос
    @var Question $question
--}}

<div class="row pay-row pay-service checked-container">
    <div class="col-xs-12 col-sm-4">
        <label for="questionPayVip" class="panel panel-primary pay-item checked-element" data-class="panel-success=panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title">@macros(select, 'pay', $question, ['type' => 'radio', 'form' => 'question', 'group' => false, 'wrap' => false, 'label' => false, 'items' => ['vip' => 'vip'], 'value' => App\Models\Question::PAY_DEFAULT, 'class' => 'checked-status']) @lang('question.pays.vip')</h3>
            </div>
            <div class="panel-body">
                Плати и будь VIP!
            </div>
            <div class="panel-footer">
                @php
                    $price = config('site.question.pay.vip');
                    $priceOld = config('site.question.pay_old.vip');
                @endphp
                Стоимость от @if ($priceOld) <span class="price-old">{{ $priceOld }}</span> <span class="price-new">{{ $price }}</span> @else {{ $price }} @endif <span class="glyphicon-rub price-volume"></span>
            </div>
        </label>
    </div>
    <div class="col-xs-12 col-sm-4">
        <label for="questionPayStandart" class="panel panel-default pay-item checked-element" data-class="panel-default=panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title">@macros(select, 'pay', $question, ['type' => 'radio', 'form' => 'question', 'group' => false, 'wrap' => false, 'label' => false, 'items' => ['standart' => 'standart'], 'class' => 'checked-status']) @lang('question.pays.standart')</h3>
            </div>
            <div class="panel-body">
                Всё стандартно!
            </div>
            <div class="panel-footer">
                @php
                    $price = config('site.question.pay.standart');
                    $priceOld = config('site.question.pay_old.standart');
                @endphp
                Стоимость @if ($priceOld) <span class="price-old">{{ $priceOld }}</span> <span class="price-new">{{ $price }}</span> @else {{ $price }} @endif <span class="glyphicon-rub price-volume"></span>
            </div>
        </label>
    </div>
    <div class="col-xs-12 col-sm-4">
        <label for="questionPayFree" class="panel panel-default pay-item checked-element" data-class="panel-default=panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title">@macros(select, 'pay', $question, ['type' => 'radio', 'form' => 'question', 'group' => false, 'wrap' => false, 'label' => false, 'items' => ['free' => 'free'], 'class' => 'checked-status']) @lang('question.pays.free')</h3>
            </div>
            <div class="panel-body">
                Бесплатно без регистрации и СМС!
            </div>
        </label>
    </div>
</div>