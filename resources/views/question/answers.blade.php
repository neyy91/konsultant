@php
    $count = $question->answers->count();
@endphp
<h2>
    <span class="title-text">{{ trans('answer.title_law') }}</span>
    <span class="small">(<span class="count count-title">{{ trans_choice('question.count_answers', $count, ['count' => $count]) }}</span>)</span>
</h2>
@if (Gate::allows('answers', $question))
    <div class="answer-items">
        @include('answer.list', ['type' => 'question', 'route' => ['name' => 'question.answer', 'params' => ['question' => $question]],  'model' => $question, 'clarifies' => true])
    </div>
@elseif(Gate::allows('pay', $question))
    <div class="row">
        <div class="col-xs-12 col-sm-8 col-sm-offset-2">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">@lang('question.pay_need')</h3>
                </div>
                <div class="panel-body">
                    @include('pay.form', [
                        'user' => $question->user,
                        'order' => $question->payment->id,
                        'url' => [
                            'default' => route('question.view', ['question' => $question]),
                            'success' => route('question.view', ['question' => $question, 'success' => 1]),
                            'fail' => route('question.view', ['question' => $question, 'fail' => 1]),
                        ],
                        'type' => 'question',
                        'pay' => [
                            'readonly' => $question->pay === $question::PAY_VIP ? false : true,
                            'value' => $question->payCost,
                        ],
                    ])
                </div>
                @if ($question->pay === $question::PAY_VIP)
                    <div class="panel-footer">
                        @lang('question.pay_vip')
                    </div>
                @endif
            </div>
        </div>
    </div>
@else
    <div class="alert alert-danger">
        <strong>@lang('app.no_access')</strong>
    </div>
@endif