{{-- 
    Форма для фильтрации типов документа.
--}}
@include('form.fields')
<form action="{{ route("document_types.admin") }}" method="GET" role="form" id="formDocumentTypeFilter" class="form form-horizontal form-filter form-document-type-filter">

    <div class="form-group form-group-sm">
        {{-- <div class="row"> --}}
            <div class="col-xs-2 col-xs-1">
                @macros(input, 'id', $request, ['form' => 'document_type', 'group' => false])
            </div>
            <div class="col-xs-12 col-sm-5">
                @macros(input, 'name', $request, ['form' => 'document_type', 'group' => false])
            </div>
            <div class="col-xs-12 col-sm-2">
                @macros(select, 'status', $request, ['form' => 'document_type', 'items' => $formVars['statuses'], 'group' => false])
            </div>
            <div class="col-xs-12 col-sm-2">
                @macros(select, 'parent_id', $request, ['form' => 'document_type', 'group' => false, 'items' => $formVars['parent_list']])
            </div>
            <div class="col-xs-12 col-sm-2">
                <label>&nbsp;</label>
                <div>
                    <div class="btn-group pull-right">
                        <button type="submit" class="btn btn-default btn-sm"><span class="glyphicon glyphicon-search" aria-hidden="true"></span> {{ trans("form.action.search") }}</button>
                        <a class="btn btn-default btn-sm toggle-tooltip" href="{{ route('document_types.admin') }}" title="{{ trans('form.action.reset') }}" data-container="body" data-placement="top"><span class="glyphicon glyphicon-remove text-danger" aria-hidden="true"></span></a>
                    </div>
                </div>
            </div>
        {{-- </div> --}}
    </div>

</form>
