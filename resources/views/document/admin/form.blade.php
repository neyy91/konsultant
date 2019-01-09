{{-- 
    Форма обновления вопроса админом.
--}}
@include('form.fields')
<form action="{{ route('document.update', ['id' => $document->id]) }}" enctype="multipart/form-data" method="POST" role="form" id="formDocument">
    {{ csrf_field() }}
    {{ method_field('PUT') }}

    <legend>{{ trans('document.form.legend.update') }} <a href="{{ route('document.view', ['document' => $document]) }}" target="_blank" class="small pull-right toggle-tooltip" title="{{ trans('form.action.view') }}" data-container="body" data-placement="left"><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span></a>. <span class="small">{{ $document->title }}</span></legend>

    <div class="form-group">
        <div class="row">
            <div class="col-xs-12 col-sm-3">
                @macros(select, 'status', $document, ['form' => 'document', 'items' => $formVars['statuses'], 'group' => false])
            </div>
            <div class="col-xs-12 col-sm-3">
                <label> &nbsp; </label>
                <div><a href="#collapseDocumentFields" class="script-action toggle-collapse collapsed" data-toggle="collapse" aria-expanded="false" aria-controls="collapseDocumentFields"><span class="glyphicon glyphicon-list" aria-hidden="true"></span> <span class="show-text">
                     {{ trans('form.show_all_fields') }}</span><span class="hide-text">{{ trans('form.hide_fields') }}</span></a></div>
            </div>
        </div>
    </div>

    <div class="collapse" id="collapseDocumentFields">
        <hr>
        <div class="row">
            <div class="col-xs-12 col-sm-3">
                @macros(select, 'document_type_id', $document, ['form' => 'document', 'items' => $formVars['document_types'], 'required' => true])
            </div>
            <div class="col-xs-12 col-sm-9">
                @macros(input, 'title', $document, ['form' => 'document', 'required' => true])
            </div>
        </div>

        @macros(select, 'city_id', $document, ['form' => 'document', 'items' => $formVars['cities'], 'required' => true])

        @macros(input, 'telephone', $document, ['form' => 'document', 'required' => true])

        <div class="form-group">
            <div class="row">
                <div class="col-xs-4 col-sm-3">
                    <br>
                    @if ($document->file)
                        <a href="{{ route('file', ['file' => $document->file, 'name' => $document->file->basename]) }}" class="file-link file-type-{{ pathinfo($document->file->basename, PATHINFO_EXTENSION) }}" target="_blank">{{ trans('form.file_uploaded') }}</a>
                    @else
                        {{ trans('form.file_not_uploaded') }}
                    @endif
                </div>
                <div class="col-xs-8 col-sm-3">
                    @macros(input, 'file', $document, ['form' => 'document', 'type' => 'file', 'label' => trans('form.file_new'), 'group' => false])
                    <div class="help-block">{{ trans('form.max_file_size', [ 'size' => config('site.document.file.max_size', 500)]) }}</div>
                </div>
            </div>
        </div>

        @macros(textarea, 'description', $document, ['form' => 'document', 'rows' => 20, 'required' => true])

    </div>

    <div class="form-group">
        <span class="glyphicon glyphicon-asterisk text-danger required-field" aria-hidden="true"></span> - {{ trans('app._required_fields') }}
    </div>

    <div class="form-group form-actions">
        @include('form.actions', ['action' => 'update', 'deleteUrl' => route('document.delete.form', ['id' => $document->id]), 'cancelUrl' => route('documents.admin')])
    </div>

</form>
