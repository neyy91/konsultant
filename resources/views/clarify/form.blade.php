{{-- 
    Форма уточнения.
--}}
@php
    $ucfType = ucfirst($type);
@endphp
<form action="{{ $to !== null ? route('clarify.create.' . $type, ['id' => $to->id]) : '' }}" enctype="multipart/form-data" method="POST" role="form" id="formClarify{{ $ucfType }}" class="form {{ $type }}-clarify form-iframe">
    {{ csrf_field() }}
    @if (isset($legend) && $legend == true)
        <legend>{{ trans('clarify.form.legend.create.' . $type) }}</legend>
    @endif

    @macros(textarea, 'text', $to, ['form' => 'clarify', 'idplus' => $type, 'rows' => 5, 'required' => true])

    <div class="form-group">
        <div class="row">
            <div class="col-xs-12 col-sm-3">
                <label for="clarifyFile" class="control-label control-label-file">{{ trans('clarify.form.file') }}</label>
            </div>
            <div class="col-xs-12 col-sm-3">
                @macros(input, 'file', $to, ['form' => 'clarify', 'idplus' => $type, 'type' => 'file', 'label' => false, 'group' => false])
            </div>
            <div class="col-xs-12 col-sm-6">
                <button type="submit" class="btn btn-primary pull-right"><span class="glyphicon glyphicon-ok-sign" aria-hidden="true"></span> {{ trans('clarify.form.action.create.' . $type) }}</button>
            </div>
        </div>
    </div>

    <div class="form-group">
        <span class="glyphicon glyphicon-asterisk text-danger small" aria-hidden="true"></span> - {{ trans('app._required_fields') }}
    </div>
</form>


