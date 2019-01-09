{{-- Форма для фильтрации страниц. --}}
@include('form.fields')

<form action="{{ route("pages.admin") }}" method="GET" role="form" id="formPageFilter" class="form form-horizontal form-filter form-page-filter">

    <div class="form-group form-group-sm">
            <div class="col-xs-2 col-xs-1">
                @macros(input, 'id', $request, ['form' => 'page', 'group' => false, 'label' => trans('app.id')])
            </div>
            <div class="col-xs-12 col-sm-3">
                @macros(input, 'title', $request, ['form' => 'page', 'group' => false])
            </div>
            <div class="col-xs-12 col-sm-2">
                @macros(select, 'status', $request, ['form' => 'page', 'items' => $formVars['statuses'], 'group' => false])
            </div>
            <div class="col-xs-12 col-sm-2">
                @macros(select, 'layout', $request, ['form' => 'page', 'group' => false, 'items' => $formVars['layouts']])
            </div>
            <div class="col-xs-12 col-sm-2">
                @macros(select, 'page_layout', $request, ['form' => 'page', 'group' => false, 'items' => $formVars['page_layouts']])
            </div>
            <div class="col-xs-12 col-sm-2">
                <label>&nbsp;</label>
                <div>
                    <div class="btn-group pull-right">
                        <button type="submit" class="btn btn-default btn-sm"><span class="glyphicon glyphicon-search" aria-hidden="true"></span> @lang('form.action.search')</button>
                        <a class="btn btn-default btn-sm toggle-tooltip" href="{{ route('pages.admin') }}" title="@lang('form.action.reset')" data-container="body" data-placement="top"><span class="glyphicon glyphicon-remove text-danger" aria-hidden="true"></span></a>
                    </div>
                </div>
            </div>
    </div>

</form>
