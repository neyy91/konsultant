{{-- Форма добавления документа. --}}

@include('form.fields')

@php
    $user = Auth::user();
@endphp

<form action="{{ route("document.create") }}" enctype="multipart/form-data" method="POST" role="form" id="formDocument">
    {{ csrf_field() }}

    <legend>{{ trans('document.form.legend.create') }}</legend>

    <div class="form-group">
        <div class="row">
            <div class="col-xs-12 col-sm-3">
                <label for="documentDocumentTypeId" class="control-label control-label-category-law-id">{{ trans('document.form.document_type_id_create') }} <span class="glyphicon glyphicon-asterisk text-danger required-field" aria-hidden="true"></span></label>
            </div>
            <div class="col-xs-12 col-sm-9">
                @macros(select, 'document_type_id', $document, ['form' => 'document', 'items' => $formVars['document_types'], 'required' => true, 'label' => false, 'group' => false])
            </div>
        </div>
    </div>

    @php
        $min_price = config('site.document.min_price', 800);
    @endphp
    <div class="form-group">
        <div class="row">
            <div class="col-xs-12 col-sm-3">
                <label for="documentCost" class="control-label control-label-cost-law-id">{{ trans('document.form.cost') }} <span class="glyphicon glyphicon-asterisk text-danger required-field" aria-hidden="true"></span></label>
            </div>
            <div class="col-xs-12 col-sm-2">
                <div class="checkbox">
                    <label>
                        <input type="checkbox" value="" class="disable-checked" data-disable-target="#documentCost"> <span class="label-text checkbox-label-text">По договренности</span>
                    </label>
                </div>
            </div>
            <div class="col-xs-12 col-sm-2">
                @macros(input, 'cost', $document, ['form' => 'document', 'required' => true, 'label' => false, 'group' => false, 'placeholder' => trans('document.form.cost_placeholder'), 'default_value' => $min_price])
            </div>
            <div class="col-xs-12 col-sm-5"><div class="help-block">@lang('document.form.cost_min', ['cost' => $min_price])</div></div>
        </div>
    </div>

    <div class="form-group">
        <div class="row">
            <div class="col-xs-12 col-sm-3">
                <label for="documentTitle" class="control-label control-label-title">{{ trans('document.form.title_create') }} <span class="glyphicon glyphicon-asterisk text-danger required-field" aria-hidden="true"></span></label>
            </div>
            <div class="col-xs-12 col-sm-9">
                @macros(input, 'title', $document, ['form' => 'document', 'required' => true, 'label' => false, 'group' => false])
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="row">
            <div class="col-xs-12 col-sm-3"><label for="documentDescription" class="control-label control-label-description">{{ trans('document.form.description') }} <span class="glyphicon glyphicon-asterisk text-danger required-field" aria-hidden="true"></span></label></div>
            <div class="col-xs-12 col-sm-9">
                @macros(textarea, 'description', $document, ['form' => 'document', 'rows' => 20, 'required' => true, 'label' => false, 'group' => false])
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="row">
            <div class="col-xs-12 col-sm-3">
                <label for="documentFile">{{ trans('document.form.file') }}</label>
            </div>
            <div class="col-xs-12 col-sm-3">
                @macros(input, 'file', $document, ['form' => 'document', 'type' => 'file', 'label' => false, 'group' => false])
                <div class="help-block">{{ trans('form.max_file_size', [ 'size' => config('site.document.file.max_size', 500)]) }}</div>
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="row">
            <div class="col-xs-12 col-sm-3">
                <label for="documentCityId" class="control-label control-label-city-id">{{ trans('document.form.city_id') }} <span class="glyphicon glyphicon-asterisk text-danger required-field" aria-hidden="true"></span></label>
            </div>
            <div class="col-xs-12 col-sm-9">
                @macros(select, 'city_id', $document, ['form' => 'document', 'items' => $formVars['cities'], 'required' => true, 'label' => false, 'group' => false, 'default_value' => $user && $user->city ? $user->city->id : ''])
            </div>
        </div>
    </div>

    @if (!$user)
        @include('common.create_reg', ['col' => 3])
    @endif

    <div class="form-group">
        <div class="row">
            <div class="col-xs-12 col-sm-3">
                <label for="documentTelephone" class="control-label control-label-telephone">{{ trans('document.form.telephone') }} <span class="glyphicon glyphicon-asterisk text-danger required-field" aria-hidden="true"></span></label>
            </div>
            <div class="col-xs-12 col-sm-9">
                @macros(input, 'telephone', $document, ['form' => 'document', 'required' => true, 'label' => false, 'group' => false, 'default_value' => $user && $user->telephone ? $user->telephone : ''])
            </div>
        </div>
    </div>



    <div class="form-group">
        <span class="glyphicon glyphicon-asterisk text-danger required-field" aria-hidden="true"></span> - {{ trans('app._required_fields') }}
    </div>

    <div class="form-group form-actions">
        <button type="submit" class="btn btn-primary btn-lg"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span> {{ trans('document.form.action.order') }}</button>
    </div>

</form>
