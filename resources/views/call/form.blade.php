{{-- Форма добавления заказа звонка. --}}

@include('form.fields')

@php
    $user = Auth::user();
@endphp

<form action="{{ route("call.create") }}" enctype="multipart/form-data" method="POST" role="form" id="formCall">
    {{ csrf_field() }}

    <legend>{{ trans('call.form.legend.create') }}</legend>

    <div class="form-group">
        <div class="row">
            <div class="col-xs-12 col-sm-2">
                <label for="callTitle" class="control-label control-label-title">{{ trans('call.form.title_create') }} <span class="glyphicon glyphicon-asterisk text-danger required-field" aria-hidden="true"></span></label>
            </div>
            <div class="col-xs-12 col-sm-10">
                @macros(input, 'title', $call, ['form' => 'call', 'required' => true, 'label' => false, 'group' => false])
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="row">
            <div class="col-xs-12 col-sm-2"><label for="callDescription" class="control-label control-label-description">{{ trans('call.form.description') }}</label></div>
            <div class="col-xs-12 col-sm-10">
                @macros(textarea, 'description', $call, ['form' => 'call', 'rows' => 20, 'required' => false, 'label' => false, 'group' => false])
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="row">
            <div class="col-xs-12 col-sm-2">
                <label for="callFile">{{ trans('call.form.file_create') }}</label>
            </div>
            <div class="col-xs-12 col-sm-3">
                @macros(input, 'file', $call, ['form' => 'call', 'type' => 'file', 'label' => false, 'group' => false])
                <div class="help-block">{{ trans('form.max_file_size', [ 'size' => config('site.call.file.max_size', 500)]) }}</div>
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="row">
            <div class="col-xs-12 col-sm-2">
                <label for="callCityId" class="control-label control-label-city-id">{{ trans('call.form.city_id_create') }} <span class="glyphicon glyphicon-asterisk text-danger required-field" aria-hidden="true"></span></label>
            </div>
            <div class="col-xs-12 col-sm-10">
                @macros(select, 'city_id', $call, ['form' => 'call', 'items' => $formVars['cities'], 'required' => true, 'label' => false, 'group' => false, 'default_value' => $user && $user->city ? $user->city->id : ''])
            </div>
        </div>
    </div>
    
    @if (!$user)
        @include('common.create_reg', ['col' => 2])
    @endif

    <div class="form-group">
        <div class="row">
            <div class="col-xs-12 col-sm-2">
                <label for="userTelephone" class="control-label control-label-telephone">{{ trans('call.form.telephone') }} <span class="glyphicon glyphicon-asterisk text-danger required-field" aria-hidden="true"></span></label>
            </div>
            <div class="col-xs-12 col-sm-10">
                @macros(input, 'telephone', $user ? $user : null, ['form' => 'user', 'label' => false, 'group' => false, 'placeholder' => trans('user.form.telephone_example'), 'attributes' => ['pattern' => '^\d{11}$']])
                <small class="help-block">@lang('user.form.telephone_format')</small>
            </div>
        </div>
    </div>

    <div class="form-group">
        @include('pay.call', ['call' => $call])
    </div>

    <div class="form-group">
        <span class="glyphicon glyphicon-asterisk text-danger required-field" aria-hidden="true"></span> - {{ trans('app._required_fields') }}
    </div>

    <div class="form-group form-actions">
        <button type="submit" class="btn btn-primary btn-lg"><span class="glyphicon glyphicon-earphone" aria-hidden="true"></span> {{ trans('call.request_consultation_by_phone') }}</button>
    </div>

</form>
