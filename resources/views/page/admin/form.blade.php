{{-- Форма добавления/обновления страницы. --}}
@include('form.fields')

<form action="{{ route("page.{$route[0]}.admin", $route[1]) }}" method="POST" role="form">
    {{ csrf_field() }}
    @if ($route[0] == 'update')
        {{ method_field('PUT') }}
    @endif
    <legend>@lang($route[0] == 'create' ? 'page.adding_page' : 'page.updating_page')</legend>

    <div class="form-group">
        <div class="row">
            <div class="col-xs-12 col-sm-10">
                @macros(input, 'title', $page, ['form' => 'page', 'required' => true, 'group' => false])
            </div>
            <div class="col-xs-12 col-sm-2">
                @macros(select, 'status', $page, ['form' => 'page', 'items' => $formVars['statuses'], 'group' => false, 'value' => $page ? '' : App\Models\Page::PUBLISHED])
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="row">
            <div class="col-xs-2">
                <label for="pageAutoslug"> </label>
                @macros(select, 'autoslug', $page, ['form' => 'page', 'type' => 'checkbox', 'group' => false, 'value' => $page ? '' : 1])
            </div>
            <div class="col-xs-10">
                @macros(input, 'slug', $page, ['form' => 'page', 'group' => false])
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="row">
            <div class="col-xs-12 col-sm-3">
                @macros(select, 'layout', $page, ['form' => 'page', 'required' => true, 'items' => $formVars['layouts'], 'group' => false])
            </div>
            <div class="cool-xs-12 col-sm-3">
                @macros(select, 'page_layout', $page, ['form' => 'page', 'required' => true, 'items' => $formVars['page_layouts'], 'group' => false])
            </div>
        </div>
    </div>

    @macros(textarea, 'description', $page, ['form' => 'page', 'rows' => 3])

    @macros(textarea, 'text', $page, ['form' => 'page', 'rows' => 10])

    <div class="form-group">
        <span class="glyphicon glyphicon-asterisk text-danger required-field" aria-hidden="true"></span> - {{ trans('app._required_fields') }}
    </div>

    <div class="form-group form-actions">
        @include('form.actions', ['action' => $route[0], 'deleteUrl' => $page ? route('page.delete.form.admin', ['id' => $page->id]) : null, 'cancelUrl' => route('pages.admin')])
    </div>

</form>
