{{-- 
    Форма добавления/обновления типа документа.
--}}
@include('form.fields')
<form action="{{ route("document_type.{$route[0]}.admin", $route[1]) }}" method="POST" role="form">
    {{ csrf_field() }}
    @if ($route[0] == 'update')
        {{ method_field('PUT') }}
    @endif
    <legend>{{ trans("document_type.form.legend.{$route[0]}") }}</legend>

    <div class="form-group">
        <div class="row">
            <div class="col-xs-12 col-sm-10">
                @macros(input, 'name', $documentType, ['form' => 'document_type', 'required' => true, 'group' => false])
            </div>
            <div class="col-xs-12 col-sm-2">
                @macros(select, 'status', $documentType, ['form' => 'document_type', 'items' => $formVars['statuses'], 'group' => false, 'value' => $documentType ? '' : App\Models\DocumentType::PUBLISHED])
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="row">
            <div class="col-xs-2">
                <label for="documentTypeAutoslug"> </label>
                @macros(select, 'autoslug', $documentType, ['form' => 'document_type', 'type' => 'checkbox', 'group' => false, 'value' => $documentType ? '' : 1])
            </div>
            <div class="col-xs-10">
                @macros(input, 'slug', $documentType, ['form' => 'document_type', 'group' => false])
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="row">
            <div class="col-xs-12 col-sm-9">
                @macros(select, 'parent_id', $documentType, ['form' => 'document_type', 'items' => $formVars['parent_list'], 'except' => isset($documentType) ? [$documentType->id] : null, 'group' => false])
            </div>
            <div class="col-xs-12 col-sm-3">
                @macros(input, 'sort', $documentType, ['form' => 'document_type', 'type' => 'number', 'group' => false, 'value' => !$documentType && isset($formVars['sort']) ? $formVars['sort'] : ''])
            </div>
        </div>
    </div>

    @macros(textarea, 'description', $documentType, ['form' => 'document_type', 'rows' => 3])

    @macros(textarea, 'text', $documentType, ['form' => 'document_type', 'rows' => 10])

    <div class="form-group">
        <span class="glyphicon glyphicon-asterisk text-danger required-field" aria-hidden="true"></span> - {{ trans('app._required_fields') }}
    </div>

    <div class="form-group form-actions">
        @include('form.actions', ['action' => $route[0], 'deleteUrl' => $documentType ? route('document_type.delete.form.admin', ['id' => $documentType->id]) : null, 'cancelUrl' => route('document_types.admin')])
    </div>

</form>
