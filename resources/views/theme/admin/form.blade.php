{{-- 
    Форма добавления/обновления категории права
--}}
@include('form.fields')
<form action="{{ route("theme.{$route[0]}.admin", $route[1]) }}" method="POST" role="form">
    {{ csrf_field() }}
    @if ($route[0] == 'update')
        {{ method_field('PUT') }}
    @endif
    <legend>{{ trans("theme.form.legend.{$route[0]}") }} @if ($theme) <a href="{{ route('theme.view', ['theme' => $theme]) }}" target="_blank" class="small pull-right toggle-tooltip" title="{{ trans('form.action.view') }}" data-container="body" data-placement="left"><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span></a> @endif</legend>

    <div class="form-group">
        <div class="row">
            <div class="col-xs-12 col-sm-10">
                @macros(input, 'name', $theme, ['form' => 'theme', 'required' => true, 'group' => false])
            </div>
            <div class="col-xs-12 col-sm-2">
                @macros(select, 'status', $theme, ['form' => 'theme', 'items' => $formVars['statuses'], 'group' => false])
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="row">
            <div class="col-xs-2">
                <label for="themeAutoslug"> </label>
                @macros(select, 'autoslug', $theme, ['form' => 'theme', 'type' => 'checkbox', 'group' => false])
            </div>
            <div class="col-xs-10">
                @macros(input, 'slug', $theme, ['form' => 'theme', 'group' => false])
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="row">
            <div class="col-xs-12 col-sm-9">
                @macros(select, 'category_law_id', $theme, ['form' => 'theme', 'items' => $formVars['category_law_list'], 'except' => isset($theme) ? [$theme->id] : null, 'group' => false])
            </div>
            <div class="col-xs-12 col-sm-3">
                @macros(input, 'sort', $theme, ['form' => 'theme', 'type' => 'number', 'group' => false, 'value' => isset($theme) ? null : 100])
            </div>
        </div>
    </div>

    @macros(textarea, 'description', $theme, ['form' => 'theme', 'rows' => 3])

    @macros(textarea, 'text', $theme, ['form' => 'theme', 'rows' => 10])

    <div class="form-group">
        <span class="glyphicon glyphicon-asterisk text-danger required-field" aria-hidden="true"></span> - {{ trans('app._required_fields') }}
    </div>

    <div class="form-group form-actions">
        @include('form.actions', ['action' => $route[0], 'deleteUrl' => $theme ? route('theme.delete.form.admin', ['id' => $theme->id]) : null, 'cancelUrl' => route('themes.admin')])
    </div>

</form>
