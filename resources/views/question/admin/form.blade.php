{{-- 
    Форма обновления вопроса для администратора.
--}}
@include('form.fields')

<form action="{{ route('question.update.admin', ['id' => $question->id]) }}" enctype="multipart/form-data" method="POST" role="form" id="formQuestion">
    {{ csrf_field() }}
    {{ method_field('PUT') }}
    <legend>{{ trans('question.form.legend.update') }} <a href="{{ route('question.view', ['question' => $question]) }}" target="_blank" class="small pull-right toggle-tooltip" title="{{ trans('form.action.view') }}" data-container="body" data-placement="left"><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span></a></legend>

    <div class="form-group">
        <div class="row">
            <div class="col-xs-12 col-sm-3">
                <label>{{ trans('question.form.status_sticky') }}</label>
            </div>
            <div class="col-xs-12 col-sm-3">
                @macros(select, 'status', $question, ['form' => 'question', 'items' => $formVars['statuses'], 'group' => false, 'label' => false, 'value' => $question->status])
            </div>
            <div class="col-xs-12 col-sm-3">
                @macros(select, 'sticky', $question, ['form' => 'question', 'type' => 'checkbox', 'group' => false, 'value' => $question->sticky])
            </div>
            <div class="col-xs-12 col-sm-3">
                <a href="#collapseQuestionFields" class="script-action toggle-collapse collapsed" data-toggle="collapse" aria-expanded="false" aria-controls="collapseQuestionFields"><span class="glyphicon glyphicon-list" aria-hidden="true"></span> <span class="show-text">
                     {{ trans('form.show_all_fields') }}</span><span class="hide-text">{{ trans('form.hide_fields') }}</span></a>
            </div>
        </div>
    </div>

    <div class="collapse" id="collapseQuestionFields">
        <hr>
        <div class="row">
            <div class="col-xs-12 col-sm-6">
                @macros(select, 'category_law_id', $question, ['form' => 'question', 'items' => $formVars['categories'], 'required' => true])
            </div>
            <div class="col-xs-12 col-sm-6">
                @macros(select, 'city_id', $question, ['form' => 'question', 'items' => $formVars['cities'], 'required' => true])
            </div>
        </div>


        @macros(input, 'title', $question, ['form' => 'question', 'required' => true])

        @macros(textarea, 'description', $question, ['form' => 'question', 'rows' => 20, 'required' => true])

        <div class="form-group">
            <div class="row">
                <div class="col-xs-4 col-sm-3">
                    <br>
                    @if ($question->file)
                        <a href="{{ route('file', ['file' => $question->file, 'name' => $question->file->basename]) }}" class="file-link file-type-{{ pathinfo($question->file->basename, PATHINFO_EXTENSION) }}" target="_blank">{{ trans('form.file_uploaded') }}</a>
                    @else
                        {{ trans('form.file_not_uploaded') }}
                    @endif
                </div>
                <div class="col-xs-8 col-sm-3">
                    @macros(input, 'file', $question, ['form' => 'question', 'type' => 'file', 'label' => trans('form.file_new'), 'group' => false])
                    <div class="help-block">{{ trans('form.max_file_size', [ 'size' => config('site.question.file.max_size', 500)]) }}</div>
                </div>
            </div>
        </div>

    </div>

    <div class="form-group">
        <span class="glyphicon glyphicon-asterisk text-danger required-field" aria-hidden="true"></span> - {{ trans('app._required_fields') }}
    </div>

    <div class="form-group form-actions">
        @include('form.actions', ['action' => 'update', 'deleteUrl' => route('question.delete.form.admin', ['id' => $question->id]), 'cancelUrl' => route('questions.admin')])
    </div>

</form>