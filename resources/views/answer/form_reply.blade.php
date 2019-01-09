{{-- 
    Форма для ответа пользователя.
--}}
@php
@endphp
<div class="panel panel-default answer-panel-form" id="replyPanel">
    <div class="panel-heading">
        <h3 class="panel-title">{{ trans('answer.form.legend.answer.reply') }}</h3>
    </div>
    <div class="panel-body">
        <form action="#" enctype="multipart/form-data" method="POST" role="form" id="formReply" class="form form-reply form-iframe">
            {{ csrf_field() }}

            @macros(textarea, 'text', null, ['form' => 'reply', 'rows' => 5, 'required' => true, 'label' => trans('answer.form.text')])

            <div class="form-group">
                <div class="row">
                    <div class="col-xs-12 col-sm-3">
                        <label for="replyFile" class="control-label control-label-file">{{ trans('answer.form.file') }}</label>
                    </div>
                    <div class="col-xs-12 col-sm-3">
                        @macros(input, 'file', null, ['form' => 'reply', 'type' => 'file', 'label' => false, 'group' => false])
                    </div>
                    <div class="col-xs-12 col-sm-6">
                        <button type="submit" class="btn btn-primary pull-right"><span class="glyphicon glyphicon-comment" aria-hidden="true"></span> {{ trans('answer.form.action.reply') }}</button>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <span class="glyphicon glyphicon-asterisk text-danger required-field" aria-hidden="true"></span> - {{ trans('app._required_fields') }}
            </div>
        </form>
    </div>
</div>

