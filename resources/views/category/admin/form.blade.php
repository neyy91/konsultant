{{-- 
    Форма добавления/обновления категории права
--}}
@include('form.fields')
<form action="{{ route("category.{$route[0]}.admin", $route[1]) }}" method="POST" role="form">
    {{ csrf_field() }}
    @if ($route[0] == 'update')
        {{ method_field('PUT') }}
    @endif
    <legend>{{ trans("category.form.legend.{$route[0]}") }} @if ($categoryLaw) <a href="{{ route('category.view', ['category' => $categoryLaw]) }}" target="_blank" class="small pull-right"><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span> @lang('app.view')</a> @endif</legend>

    <div class="form-group">
        <div class="row">
            <div class="col-xs-12 col-sm-10">
                @macros(input, 'name', $categoryLaw, ['form' => 'category', 'required' => true, 'group' => false])
            </div>
            <div class="col-xs-12 col-sm-2">
                @macros(select, 'status', $categoryLaw, ['form' => 'category', 'items' => $formVars['statuses'], 'group' => false, 'value' => $categoryLaw ? '' : App\Models\CategoryLaw::PUBLISHED])
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="row">
            <div class="col-xs-2">
                <label for="categoryAutoslug"> </label>
                @macros(select, 'autoslug', $categoryLaw, ['form' => 'category', 'type' => 'checkbox', 'group' => false, 'value' => $categoryLaw ? '' : 1])
            </div>
            <div class="col-xs-10">
                @macros(input, 'slug', $categoryLaw, ['form' => 'category', 'group' => false])
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="row">
            <div class="col-xs-12 col-sm-9">
                @macros(select, 'parent_id', $categoryLaw, ['form' => 'category', 'items' => $formVars['parent_list'], 'except' => isset($categoryLaw) ? [$categoryLaw->id] : null, 'group' => false])
            </div>
            <div class="col-xs-12 col-sm-3">
                @macros(input, 'sort', $categoryLaw, ['form' => 'category', 'type' => 'number', 'group' => false, 'value' => !$categoryLaw && isset($formVars['sort']) ? $formVars['sort'] : ''])
            </div>
        </div>
    </div>

    @macros(textarea, 'description', $categoryLaw, ['form' => 'category', 'rows' => 3])

    @macros(textarea, 'text', $categoryLaw, ['form' => 'category', 'rows' => 10])

    <div class="form-group">
        <span class="glyphicon glyphicon-asterisk text-danger required-field" aria-hidden="true"></span> - {{ trans('app._required_fields') }}
    </div>

    <div class="form-group form-actions">
        @include('form.actions', ['action' => $route[0], 'deleteUrl' => $categoryLaw ? route('category.delete.form.admin', ['id' => $categoryLaw->id]) : null, 'cancelUrl' => route('categories.admin')])
    </div>

</form>
