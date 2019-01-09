{{-- 
    Форма обновления звонков админом.
--}}
@include('form.fields')
<form action="{{ route('call.update', ['id' => $call->id]) }}" enctype="multipart/form-data" method="POST" role="form" id="formCall">
    {{ csrf_field() }}
    {{ method_field('PUT') }}

    <legend>{{ trans('call.form.legend.update') }} <a href="{{ route('call.view', ['call' => $call]) }}" target="_blank" class="small pull-right toggle-tooltip" title="{{ trans('form.action.view') }}" data-container="body" data-placement="left"><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span></a></legend>

    <div class="form-group">
        <div class="row">
            <div class="col-xs-12 col-sm-3">
                @macros(select, 'status', $call, ['form' => 'call', 'items' => $formVars['statuses'], 'group' => false])
            </div>
            <div class="col-xs-12 col-sm-3">
                <label> &nbsp; </label>
                <div>
                    <a href="#collapseCallFields" class="script-action toggle-collapse collapsed" data-toggle="collapse" aria-expanded="false" aria-controls="collapseCallFields"><span class="glyphicon glyphicon-list" aria-hidden="true"></span> <span class="show-text">
                         {{ trans('form.show_all_fields') }}</span><span class="hide-text">{{ trans('form.hide_fields') }}</span></a>
                </div>
            </div>
        </div>
    </div>

    <div class="collapse" id="collapseCallFields">
        <hr>
        <div class="row">
            <div class="col-xs-12 col-sm-9">
                @macros(input, 'title', $call, ['form' => 'call', 'required' => true])
            </div>
        </div>

        @macros(select, 'city_id', $call, ['form' => 'call', 'items' => $formVars['cities'], 'required' => true])

        @macros(input, 'telephone', $call, ['form' => 'call', 'required' => true])

        <div class="form-group">
            <div class="row">
                <div class="col-xs-4 col-sm-3">
                    <br>
                    @if ($call->file)
                        <a href="{{ route('file', ['file' => $call->file, 'name' => $call->file->basename]) }}" class="file-link file-type-{{ pathinfo($call->file->basename, PATHINFO_EXTENSION) }}" target="_blank">{{ trans('form.file_uploaded') }}</a>
                    @else
                        {{ trans('form.file_not_uploaded') }}
                    @endif
                </div>
                <div class="col-xs-8 col-sm-3">
                    @macros(input, 'file', $call, ['form' => 'call', 'type' => 'file', 'label' => trans('form.file_new'), 'group' => false])
                    <div class="help-block">{{ trans('form.max_file_size', [ 'size' => config('site.call.file.max_size', 500)]) }}</div>
                </div>
            </div>
        </div>

        @macros(textarea, 'description', $call, ['form' => 'call', 'rows' => 20, 'required' => false])

    </div>

    <div class="form-group">
        <span class="glyphicon glyphicon-asterisk text-danger required-field" aria-hidden="true"></span> - {{ trans('app._required_fields') }}
    </div>

    <div class="form-group form-actions">
        @include('form.actions', ['action' => 'update', 'deleteUrl' => route('call.delete.form', ['id' => $call->id]), 'cancelUrl' => route('calls.admin')])
    </div>

</form>
