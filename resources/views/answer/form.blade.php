{{-- 
    Форма для ответа
--}}
@php
    $ucfType = ucfirst($type);
    $panel = isset($panel) ? $panel : 'default';
    $icon = isset($icon) ? $icon : 'ok-sign';
    $method = isset($method) ? $method : 'POST';
    $action = isset($action) ? $action : route('answer.create.' . $type, ['id' => $to->id]);
    $button_text = isset($button_text) ? $button_text : trans('answer.form.action.' . $type);
    $show_remove = isset($show_remove) && $show_remove;
    $pay = isset($pay) ? $pay : false;
@endphp
<div class="panel panel-{{ $panel }} answer-panel-form" id="answer{{ $ucfType }}Panel">
    <div class="panel-heading">
        <h3 class="panel-title">{{ trans('answer.form.legend.answer.' . $type) }} @if ($show_remove) <a href="#answer{{ $ucfType }}Panel" class="pull-right script-action answer-form-remove">@lang('form.action.cancel')</a> @endif</h3>
    </div>
    <div class="panel-body">
        <form action="{{ $action }}" enctype="multipart/form-data" method="POST" role="form" id="formAnswer{{ $ucfType }}" class="form {{ $type }}-answer form-iframe">
            {{ csrf_field() }}
            {{ method_field($method)}}

            @macros(textarea, 'text', $to, ['form' => 'answer', 'idplus' => $type, 'rows' => 10, 'required' => true])

            <div class="form-group">
                <div class="row">
                    <div class="col-xs-12 col-sm-2">
                        <label for="answer{{ $ucfType }}File" class="control-label control-label-file">{{ trans('answer.form.file') }}</label>
                    </div>
                    <div class="col-xs-12 col-sm-3">
                        @macros(input, 'file', null, ['form' => 'answer', 'idplus' => $type, 'type' => 'file', 'label' => false, 'group' => false])
                    </div>
                    @if ($pay)
                        <div class="col-xs-12 col-sm-2">
                            <label for="answer{{ $ucfType }}Cost" class="control-label control-label-file">{{ trans('answer.form.cost') }} <span class="glyphicon glyphicon-asterisk text-danger required-field" aria-hidden="true"></span></label>
                        </div>
                        <div class="col-xs-12 col-sm-2">
                            @macros(input, 'cost', null, ['form' => 'answer', 'idplus' => $type, 'label' => false, 'group' => false, 'required' => true, 'default_value' => $pay, 'placeholder' => trans('answer.form.cost_placeholder')])
                        </div>
                    @endif
                    <div class="col-xs-12 @if ($pay) col-sm-3 @else col-sm-7 @endif">
                        @if ($show_remove) <a href="#answer{{ $ucfType }}Panel" class="pull-right btn btn-link answer-form-remove">@lang('form.action.cancel')</a>@endif
                        <button type="submit" class="btn btn-primary pull-right"><span class="glyphicon glyphicon-{{ $icon }}" aria-hidden="true"></span> {{ $button_text }}</button>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <span class="glyphicon glyphicon-asterisk text-danger required-field" aria-hidden="true"></span> - {{ trans('app._required_fields') }}
            </div>
        </form>
    </div>
</div>

