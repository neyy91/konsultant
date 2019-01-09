{{-- 
    Форма для фильтрации вопросов пользователя.
--}}
@include('form.fields')
<form action="{{ route("user.calls") }}" method="GET" role="form" id="formCallFilter" class="form form-horizontal form-filter form-call-filter">

    <div class="form-group">
        {{-- <div class="row"> --}}
            <div class="col-xs-12 col-sm-3">
                <label for="callCity" class="control-label control-label-city">{{ trans('call.form.city_id') }}</label>
            </div>
            <div class="col-xs-12 col-sm-4">
                @macros(select, 'city', $request, ['form' => 'call', 'items' => $formVars['cities'], 'group' => false, 'label' => false, 'label_first' => trans('call.form.city_id_first')])
            </div>
    </div>
        {{-- </div> --}}
    <div class="form-group">
        {{-- <div class="row"> --}}
            <div class="col-xs-6 col-sm-3">
                <label for="callPerpage" class="control-label control-label-perpage">{{ trans('form.records_per_page') }}</label>
            </div>
            <div class="col-xs-6 col-sm-4">
                @macros(select, 'perpage', $request, ['form' => 'call', 'items' => $formVars['perpages'], 'group' => false, 'label' => false, 'label_first' => false])
            </div>
            <div class="col-xs-12 col-sm-5">
                <div class="btn-group">
                    <button type="submit" class="btn btn-default"><span class="glyphicon glyphicon-search" aria-hidden="true"></span> {{ trans('form.action.show_resultat') }}</button>
                    <a class="btn btn-default toggle-tooltip" href="{{ route('user.calls') }}" title="{{ trans('form.action.reset') }}" data-container="body" data-placement="top"><span class="glyphicon glyphicon-remove text-danger" aria-hidden="true"></span></a>
                </div>
            </div>
        {{-- </div> --}}
    </div>
</form>
