{{-- 
    Форма для фильтрации жалоб для админа.
--}}
@include('form.fields')
<form action="{{ route('complaints.admin') }}" method="GET" role="form" id="formComplaintFilter" class="form form-horizontal form-filter form-complaint-filter">

    <div class="form-group form-group-sm">
        {{-- <div class="row"> --}}
            <div class="col-xs-6 col-xs-1">
                @macros(input, 'id', $request, ['form' => 'complaint', 'group' => false])
            </div>
            <div class="col-xs-6 col-sm-2">
                @macros(select, 'against', $request, ['form' => 'complaint', 'items' => $formVars['againsts'], 'group' => false])
            </div>
            <div class="col-xs-12 col-sm-3">
                @macros(select, 'type', $request, ['form' => 'complaint', 'items' => $formVars['types'], 'group' => false])
            </div>
            <div class="col-xs-6 col-sm-2">
                @macros(input, 'date_from', $request, ['form' => 'complaint', 'group' => false, 'type' => 'date'])
            </div>
            <div class="col-xs-6 col-sm-2">
                @macros(input, 'date_to', $request, ['form' => 'complaint', 'group' => false, 'type' => 'date'])
            </div>

            <div class="col-xs-12 col-sm-2">
                <label>&nbsp;</label>
                <div>
                    <div class="btn-group pull-right">
                        <button type="submit" class="btn btn-default btn-sm"><span class="glyphicon glyphicon-search" aria-hidden="true"></span> {{ trans("form.action.search") }}</button>
                        <a class="btn btn-default btn-sm toggle-tooltip" href="{{ route('complaints.admin') }}" title="{{ trans('form.action.reset') }}" data-container="body" data-placement="top"><span class="glyphicon glyphicon-remove text-danger" aria-hidden="true"></span></a>
                    </div>
                </div>
            </div>
        {{-- </div> --}}
    </div>

</form>
