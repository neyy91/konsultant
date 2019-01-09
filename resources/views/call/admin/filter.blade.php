{{-- 
    Форма для фильтрации звонков для админа.
--}}
@include('form.fields')
<form action="{{ route("calls.admin") }}" method="GET" role="form" id="formCallFilter" class="form form-horizontal form-filter form-call-filter">

    <div class="form-group form-group-sm">
        {{-- <div class="row"> --}}
            <div class="col-xs-2 col-xs-1">
                @macros(input, 'id', $request, ['form' => 'call', 'group' => false])
            </div>
            <div class="col-xs-12 col-sm-5">
                @macros(input, 'title', $request, ['form' => 'call', 'group' => false])
            </div>
            <div class="col-xs-6 col-sm-2">
                @macros(select, 'status', $request, ['form' => 'call', 'items' => $formVars['statuses'], 'group' => false])
            </div>
            <div class="col-xs-6 col-sm-2">
                @macros(select, 'city_id', $request, ['form' => 'call', 'items' => $formVars['cities'], 'group' => false])
            </div>
            <div class="col-xs-12 col-sm-2">
                <label>&nbsp;</label>
                <div>
                    <div class="btn-group pull-right">
                        <button type="submit" class="btn btn-default btn-sm"><span class="glyphicon glyphicon-search" aria-hidden="true"></span> {{ trans("form.action.search") }}</button>
                        <a class="btn btn-default btn-sm toggle-tooltip" href="{{ route('calls.admin') }}" title="{{ trans('form.action.reset') }}" data-container="body" data-placement="top"><span class="glyphicon glyphicon-remove text-danger" aria-hidden="true"></span></a>
                    </div>
                </div>
            </div>
        {{-- </div> --}}
    </div>
    
</form>
