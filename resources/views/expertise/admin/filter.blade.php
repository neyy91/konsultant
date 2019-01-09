{{-- Форма фильтр экспертиз. --}}

@include('form.fields')

<form action="{{ route("expertises.admin") }}" method="GET" role="form" id="formExpertiseFilter" class="form form-horizontal form-filter form-expertise-filter">

    <div class="form-group form-group-sm">
            <div class="col-xs-2 col-xs-2">
                @macros(input, 'qid', $request, ['form' => 'expertise', 'group' => false])
            </div>
            <div class="col-xs-12 col-sm-2">
                @macros(input, 'lid', $request, ['form' => 'expertise', 'group' => false])
            </div>
            <div class="col-xs-12 col-sm-2">
                @macros(select, 'type', $request, ['form' => 'expertise', 'group' => false, 'items' => $formVars['types']])
            </div>
            <div class="col-xs-12 col-sm-2">
                <label>&nbsp;</label>
                <div>
                    <div class="btn-group pull-right">
                        <button type="submit" class="btn btn-default btn-sm"><span class="glyphicon glyphicon-search" aria-hidden="true"></span> {{ trans("form.action.search") }}</button>
                        <a class="btn btn-default btn-sm toggle-tooltip" href="{{ route('expertises.admin') }}" title="@lang('form.action.reset')" data-container="body" data-placement="top"><span class="glyphicon glyphicon-remove text-danger" aria-hidden="true"></span></a>
                    </div>
                </div>
            </div>
    </div>

</form>
