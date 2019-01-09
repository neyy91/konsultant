{{-- Статусы --}}
@php
    $show = isset($show) ? $show : false;
@endphp

@macros(status($model))
    @php
        $statuses = [
            $model::STATUS_BLOCKED => ['bg' => 'bg-default', 'icon' => 'glyphicon-remove-sign text-danger'],
            $model::STATUS_UNPUBLISHED => ['bg' => 'bg-default', 'icon' => 'glyphicon-minus-sign text-warning'],
            $model::STATUS_IN_WORK => ['bg' => 'bg-info', 'icon' => 'glyphicon-briefcase'],
            $model::STATUS_LAWYER_SELECTED => ['bg' => 'bg-success', 'icon' => 'glyphicon-ok'],
            $model::STATUS_COMPLETED => ['bg' => 'bg-success', 'icon' => 'glyphicon-ok-sign'],
        ];
        if (defined(get_class($model) . '::STATUS_EXPERTISE')) {
            $statuses[$model::STATUS_EXPERTISE] = ['bg' => 'bg-warning', 'icon' => 'glyphicon-cog'];
        }
        $status = isset($statuses[$model->status]) ? $statuses[$model->status] : $statuses[$model::STATUS_DEFAULT];
        $cost = $model->payCost;
    @endphp
    @if (isset($model->sticky) && $model->sticky)
        <span class="bg-primary text-danger status status-sticky toggle-tooltip" title="@lang('statuses.sticked.' . $model::MORPH_NAME)"><span class="glyphicon glyphicon-paperclip icon-sticky" aria-hidden="true"></span></span>
    @endif
    @if ($cost > 0 || $cost === null)
        <span class="{{ $model->isPayed ? 'bg-success' : 'bg-info' }} status status-pay toggle-tooltip" title="{{trans_choice('pay.titles.' . $model::MORPH_NAME, $cost, ['cost' => $cost]) }}">@if ($cost)<span class="price">{{ $cost }}</span> @endif<span class="small glyphicon glyphicon-rub" aria-hidden="true"></span></span>
    @endif
    @if ($model->isPayed)
        <span class="bg-success status status-pay toggle-tooltip"><span class="glyphicon glyphicon-ok" aria-hidden="true"></span> <span class="text">@lang('pay.statuses.payed') </span>
        </span>
    @endif
    <span class="{{ $status['bg'] }} status status-{{ $model::MORPH_NAME }}"><span class="glyphicon {{ $status['icon'] }}" aria-hidden="true"></span> {{ $model->statusLabel }}</span>
@endmacros

@if ($show)
    @macros(status, $model)
@endif