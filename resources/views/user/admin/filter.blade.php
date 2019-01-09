{{-- Фильтр пользователей --}}
@include('form.fields')

<form action="{{ route("users.admin") }}" method="GET" role="form" id="formUserFilter" class="form form-horizontal form-filter form-user-filter">

    <div class="form-group form-group-sm">
        {{-- <div class="row"> --}}
            <div class="col-xs-2 col-sm-1">
                @macros(input, 'id', $request, ['form' => 'user', 'group' => false])
            </div>

            <div class="col-xs-5 col-sm-2">
                @macros(input, 'lastname', $request, ['form' => 'user', 'group' => false])
            </div>

            <div class="col-xs-5 col-sm-2">
                @macros(input, 'firstname', $request, ['form' => 'user', 'group' => false])
            </div>

            <div class="col-xs-12 col-sm-5">
                <div class="row">
                    <div class="col-xs-4">
                        @macros(select, 'status', $request, ['form' => 'user', 'items' => $formVars['statuses'], 'group' => false, 'label_first' => trans('app.select_first')])
                    </div>

                    <div class="col-xs-4">
                        @macros(select, 'type', $request, ['form' => 'user', 'items' => $formVars['types'], 'group' => false, 'label_first' => trans('app.select_first')])
                    </div>

                    <div class="col-xs-4">
                        @macros(select, 'city', $request, ['form' => 'user', 'items' => $formVars['cities'], 'group' => false, 'label_first' => trans('app.select_first')])
                    </div>
                </div>
            </div>

            <div class="col-xs-12 col-sm-2">
                <label>&nbsp;</label>
                <div>
                    <div class="btn-group pull-right">
                        <button type="submit" class="btn btn-default btn-sm"><span class="glyphicon glyphicon-search" aria-hidden="true"></span> {{ trans("form.action.search") }}</button>
                        <a class="btn btn-default btn-sm toggle-tooltip" href="{{ route('users.admin') }}" title="{{ trans('form.action.reset') }}" data-container="body" data-placement="top"><span class="glyphicon glyphicon-remove text-danger" aria-hidden="true"></span></a>
                    </div>
                </div>
            </div>
        {{-- </div> --}}
    </div>

</form>
