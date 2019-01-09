{{-- Форма для запроса контакат. --}}

<div class="panel panel-primary answer-panel-form" id="answerCallPanel">
    <div class="panel-heading">
        <h3 class="panel-title">{{ trans('answer.form.legend.answer.call') }}</h3>
    </div>
    <form action="{{ route('answer.create.call', ['id' => $call->id]) }}" enctype="multipart/form-data" method="POST" role="form" id="formAnswerCall" class="form call-answer form-iframe">
        <div class="panel-body">
            {{ csrf_field() }}
            <div class="form-group description description-form-call">
                {{ trans('call.form.#description') }}
            </div>
            <div class="collapse other-fields" id="answerCallFields">
                @macros(textarea, 'text', $to, ['form' => 'answer', 'idplus' => 'call', 'rows' => 7, 'required' => false, 'label' => trans('call.request_text')])

                <div class="form-group">
                    <div class="row">
                        <div class="col-xs-12 col-sm-3">
                            <label for="answerCallFile" class="control-label control-label-file">{{ trans('answer.form.file') }}</label>
                        </div>
                        <div class="col-xs-12 col-sm-3">
                            @macros(input, 'file', $to, ['form' => 'answer', 'idplus' => 'call', 'type' => 'file', 'label' => false, 'group' => false])
                        </div>
                        
                    </div>
                </div>
            </div>
            <div class="text-center">
                <a href="#answerCallFields" class="script-action toggle-collapse collapsed" data-toggle="collapse" aria-controls="answerCallFields" aria-expanded="false"><span class="show-text"><span class="glyphicon glyphicon-chevron-down" aria-hidden="true"></span> {{ trans('form.show_additional_fields') }}</span><span class="hide-text"><span class="glyphicon glyphicon-chevron-up" aria-hidden="true"></span> {{ trans('form.hide_fields') }}</span></a>

            </div>
        </div>
        <div class="panel-footer">
            <div class="row row-submit">
                <div class="col-xs-4 col-xs-offset-4 text-center col-submit">
                    <button type="submit" class="btn btn-primary btn-lg"><span class="glyphicon glyphicon-earphone" aria-hidden="true"></span> {{ trans('answer.form.action.call') }}</button>
                </div>
            </div>
        </div>
    </form>
</div>

