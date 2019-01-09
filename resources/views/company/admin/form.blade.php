{{-- 
    Форма добавления/обновления компании.
--}}
@include('form.fields')
<form action="{{ route("company.{$route[0]}.admin", $route[1]) }}" method="POST" role="form" enctype="multipart/form-data">
    {{ csrf_field() }}
    @if ($company)
        {{ method_field('PUT') }}
    @endif
    <legend>@if ($company)
        @lang('company.action.edit_company', ['company' => $company->name])
    @else
        @lang('company.action.create')
    @endif</legend>
    
    <div class="form-group">
        <div class="row">
            <div class="col-xs-10 col-sm-3">
                @macros(select, 'status', $company, ['form' => 'company', 'items' => $formVars['statuses'], 'group' => false, 'value' => $company ? null : App\Models\Company::PUBLISHED])
            </div>
        </div>
    </div>

    <div class="form-group">
        <a href="#CollapseFields" class="script-action toggle-collapse collapsed" data-toggle="collapse" aria-expanded="false" aria-controls="CollapseFields">
            <span class="glyphicon glyphicon-list" aria-hidden="true"></span>
            <span class="show-text"> @lang('form.show_all_fields')</span><span class="hide-text">@lang('form.hide_fields')</span>
        </a>

    </div>
    <div class="collapse" id="CollapseFields">
        <div class="row">
            <div class="col-xs-12 col-sm-6">
                @macros(input, 'name', $company, ['form' => 'company', 'required' => true])
                @macros(textarea, 'description', $company, ['form' => 'company', 'rows' => 3])
            </div>
            <div class="col-xs-12 col-sm-6">
                @macros(input, 'logo', $company, ['form' => 'company', 'type' => 'file' ])
                @if ($company->logo)
                    <img src="{{ $company->logo->url }}" alt="{{ $company->name }}" class="image" width="70">
                @endif
            </div>
        </div>

        @macros(textarea, 'text', $company, ['form' => 'company', 'rows' => 20])

        <div class="form-group">
            <span class="glyphicon glyphicon-asterisk text-danger required-field" aria-hidden="true"></span> - @lang('app._required_fields')
        </div>
    </div>

    <div class="form-group form-actions">
        @include('form.actions', ['action' => $route[0], 'deleteUrl' => $company ? route('company.delete.form.admin', ['id' => $company->id]) : null, 'cancelUrl' => route('companies.admin')])
    </div>

</form>
