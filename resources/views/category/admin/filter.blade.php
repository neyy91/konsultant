{{-- 
    Форма для фильтрации категории
--}}
@include('form.fields')
<form action="{{ route("categories.admin") }}" method="GET" role="form" id="formCategoryLawFilter" class="form form-horizontal form-filter form-category-law-filter">

    <div class="form-group form-group-sm">
        {{-- <div class="row"> --}}
            <div class="col-xs-12 col-sm-1">
                @macros(input, 'id', $request, ['form' => 'category', 'group' => false])
            </div>
            <div class="col-xs-12 col-sm-4">
                @macros(input, 'name', $request, ['form' => 'category', 'group' => false])
            </div>
            <div class="col-xs-12 col-sm-3">
                @macros(select, 'parent_id', $request, ['form' => 'category', 'group' => false, 'items' => $formVars['parent_list']])
            </div>
            <div class="col-xs-12 col-sm-2">
                @macros(select, 'status', $request, ['form' => 'category', 'items' => $formVars['statuses'], 'group' => false])
            </div>
            <div class="col-xs-12 col-sm-2">
                <label>&nbsp;</label>
                <div>
                    <div class="btn-group pull-right">
                        <button type="submit" class="btn btn-default btn-sm"><span class="glyphicon glyphicon-search" aria-hidden="true"></span> {{ trans("form.action.search") }}</button>
                        <a class="btn btn-default btn-sm toggle-tooltip" href="{{ route('categories.admin') }}" title="{{ trans('form.action.reset') }}" data-container="body" data-placement="top"><span class="glyphicon glyphicon-remove text-danger" aria-hidden="true"></span></a>
                    </div>
                </div>
            </div>
        {{-- </div> --}}
    </div>
</form>
