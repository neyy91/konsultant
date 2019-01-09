{{-- 
    Форма для фильтрации городов.
--}}
@include('form.fields')
<form action="{{ route("cities.admin") }}" method="GET" role="form" id="formCityFilter" class="form form-horizontal form-filter form-company-filter">

    <div class="form-group form-group-sm">
        {{-- <div class="row"> --}}
            <div class="col-xs-12 col-sm-1">
                @macros(input, 'id', $request, ['form' => 'city', 'group' => false])
            </div>
            <div class="col-xs-12 col-sm-5">
                @macros(input, 'name', $request, ['form' => 'city', 'group' => false])
            </div>
            <div class="col-xs-12 col-sm-2">
                @macros(select, 'status', $request, ['form' => 'city', 'items' => $formVars['statuses'], 'group' => false])
            </div>
            <div class="col-xs-12 col-sm-2">
                @macros(select, 'region_id', $request, ['form' => 'city', 'group' => false, 'items' => $formVars['regions']])
            </div>
            <div class="col-xs-12 col-sm-2">
                <label>&nbsp;</label>
                <div>
                    <div class="btn-group pull-right">
                        <button type="submit" class="btn btn-default btn-sm"><span class="glyphicon glyphicon-search" aria-hidden="true"></span> {{ trans("form.action.search") }}</button>
                        <a class="btn btn-default btn-sm toggle-tooltip" href="{{ route('cities.admin') }}" title="@lang('form.action.reset')" data-container="body" data-placement="top"><span class="glyphicon glyphicon-remove text-danger" aria-hidden="true"></span></a>
                    </div>
                </div>
            </div>
        {{-- </div> --}}
    </div>

</form>
