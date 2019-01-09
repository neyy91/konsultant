{{-- 
    Форма для фильтрации ответов.
--}}
@include('form.fields')
<form action="{{ route("answers.admin", ['type' => $type]) }}" method="GET" role="form">

    <div class="form-group">
        <div class="row">
            <div class="col-xs-12 col-sm-3">
                <label>&nbsp;</label>
                @macros(select, 'is', $request, ['form' => 'answer', 'group' => false, 'type' => 'checkbox', 'label' => trans("answer.is_answer.{$type}")])
            </div>
            <div class="col-xs-12 col-sm-2">
                <label>&nbsp;</label>
                <div>
                    <div class="btn-group pull-right">
                        <button type="submit" class="btn btn-default"><span class="glyphicon glyphicon-search" aria-hidden="true"></span> {{ trans("form.action.search") }}</button>
                        <a class="btn btn-default toggle-tooltip" href="{{ route('answers.admin', ['type' => $type]) }}" title="{{ trans('form.action.reset') }}" data-container="body" data-placement="top"><span class="glyphicon glyphicon-remove text-danger" aria-hidden="true"></span></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
