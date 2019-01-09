{{-- 
    Форма для фильтрации вопросов пользователя.
--}}
@include('form.fields')
<form action="{{ route("user.questions") }}" method="GET" role="form" id="formQuestionFilter" class="form form-horizontal form-filter form-question-filter">

    <div class="form-group">
        {{-- <div class="row"> --}}
            <div class="col-xs-12 col-sm-3">
                <label for="questionCity" class="control-label control-label-city">{{ trans('question.form.city_id') }}</label>
            </div>
            <div class="col-xs-12 col-sm-4">
                @macros(select, 'city', $request, ['form' => 'question', 'items' => $formVars['cities'], 'group' => false, 'label' => false, 'label_first' => trans('question.form.city_id_first')])
            </div>
    </div>
        {{-- </div> --}}
    <div class="form-group">
        {{-- <div class="row"> --}}
            <div class="col-xs-6 col-sm-3">
                <label for="questionPerpage" class="control-label control-label-perpage">{{ trans('form.records_per_page') }}</label>
            </div>
            <div class="col-xs-6 col-sm-4">
                @macros(select, 'perpage', $request, ['form' => 'question', 'items' => $formVars['perpages'], 'group' => false, 'label' => false, 'label_first' => false])
            </div>
            <div class="col-xs-12 col-sm-5">
                <div class="btn-group">
                    <button type="submit" class="btn btn-default"><span class="glyphicon glyphicon-search" aria-hidden="true"></span> {{ trans('form.action.show_resultat') }}</button>
                    <a class="btn btn-default toggle-tooltip" href="{{ route('user.questions') }}" title="{{ trans('form.action.reset') }}" data-container="body" data-placement="top"><span class="glyphicon glyphicon-remove text-danger" aria-hidden="true"></span></a>
                </div>
            </div>
        {{-- </div> --}}
    </div>
</form>
