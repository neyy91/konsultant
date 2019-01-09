{{-- Цена ответа --}}

@macros(answer_cost($answer))
    @if ($toPay = Gate::allows('pay', $answer->to))
        <a href="#pay" class="bg-info answer-cost toggle-tooltip" title="@lang('pay.pay_service.' . $answer->to::MORPH_NAME)">
    @else
        <span class="{{ $answer->is && Gate::denies('answer-is', $answer) ? 'bg-success' : 'bg-info' }} answer-cost">
    @endif
        {{ $answer->cost }} <span class="small glyphicon glyphicon-rub" aria-hidden="true"></span>
    @if ($toPay)
        </a>
    @else
        </span>
    @endif
@endmacros

