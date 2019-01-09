{{-- 
    Форма для фильтрации файлов.
--}}
@include('form.fields')
<form action="{{ route('files') }}" method="GET" role="form" id="formFileFilter" class="form form-horizontal form-filter form-file-filter">

    <div class="form-group form-group-sm">
        {{-- <div class="row"> --}}
            <div class="col-xs-2 col-xs-1">
                @macros(input, 'id', $request, ['form' => 'file', 'group' => false])
            </div>
            <div class="col-xs-12 col-sm-4">
                @macros(input, 'basename', $request, ['form' => 'file', 'group' => false])
            </div>
            <div class="col-xs-12 col-sm-2">
                @macros(select, 'mime_type', $request, ['form' => 'file', 'items' => $formVars['mime_types'], 'group' => false])
            </div>
            <div class="col-xs-12 col-sm-2">
                @macros(select, 'owner_type', $request, ['form' => 'file', 'group' => false, 'items' => $formVars['owner_types']])
            </div>
            <div class="col-xs-12 col-sm-1">
                @macros(input, 'owner_id', $request, ['form' => 'file', 'group' => false])
            </div>
            <div class="col-xs-12 col-sm-2">
                <label>&nbsp;</label>
                <div>
                    <div class="btn-group pull-right">
                        <button type="submit" class="btn btn-default btn-sm"><span class="glyphicon glyphicon-search" aria-hidden="true"></span> {{ trans("form.action.search") }}</button>
                        <a class="btn btn-default btn-sm toggle-tooltip" href="{{ route('files') }}" title="{{ trans('form.action.reset') }}" data-container="body" data-placement="top"><span class="glyphicon glyphicon-remove text-danger" aria-hidden="true"></span></a>
                    </div>
                </div>
            </div>
        {{-- </div> --}}
    </div>

</form>
