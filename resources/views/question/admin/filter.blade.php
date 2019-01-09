{{-- 
    Форма для фильтрации вопросов для админа
--}}
@include('form.fields')
<form action="{{ route("questions.admin") }}" method="GET" role="form" id="formQuestionFilter" class="form form-horizontal form-filter form-question-filter">

    <div class="form-group form-group-sm">
        {{-- <div class="row"> --}}
            <div class="col-xs-2 col-xs-1">
                @macros(input, 'id', $request, ['form' => 'question', 'group' => false])
            </div>
            <div class="col-xs-10 col-sm-3">
                @macros(input, 'title', $request, ['form' => 'question', 'group' => false])
            </div>
            <div class="col-xs-12 col-sm-2">
                @macros(select, 'status', $request, ['form' => 'question', 'items' => $formVars['statuses'], 'group' => false])
            </div>
            <div class="col-xs-12 col-sm-1 input-sm">
                <br>
                @macros(select, 'sticky', $request, ['form' => 'question', 'type' => 'checkbox', 'group' => false, 'label' => trans('question.sticked')])
            </div>
            <div class="col-xs-12 col-sm-2">
                @macros(select, 'city_id', $request, ['form' => 'question', 'items' => $formVars['cities'], 'group' => false])
            </div>
            <div class="col-xs-12 col-sm-2">
                @macros(select, 'category_law_id', $request, ['form' => 'question', 'group' => false, 'items' => $formVars['categories']])
            </div>
            <div class="col-xs-12 col-sm-1">
                <label>&nbsp;</label>
                <div>
                    <div class="btn-group pull-right">
                        <button type="submit" class="btn btn-default btn-sm toggle-tooltip" title="{{ trans('form.action.search') }}" data-toggle="tooltip" data-container="body" data-placement="top"><span class="glyphicon glyphicon-search" aria-hidden="true"></span></button>
                        <a class="btn btn-default btn-sm toggle-tooltip" href="{{ route('questions.admin') }}" title="{{ trans('form.action.reset') }}" data-container="body" data-placement="top"><span class="glyphicon glyphicon-remove text-danger" aria-hidden="true"></span></a>
                    </div>
                </div>
            {{-- </div> --}}
        </div>
    </div>
</form>
