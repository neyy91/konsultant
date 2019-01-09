{{-- 
    Форма редактирования дополнительных параметров.
--}}
@php
    $route = route("user.edit.advanced");
    $lawyer = $user->lawyer;
@endphp
@include('form.fields')
@section('form_required') @stop

<form action="{{ $route }}" method="POST" role="form" id="formEditAdvanced" class="form form-vertical form-label-block form-edit-advanced ajax" data-on="submit" data-ajax-method="PUT" data-ajax-url="{{ $route }}" data-ajax-data-type="json" data-ajax-data="App.serializeToObject" data-ajax-before-send="App.disableForm" data-ajax-context="this" data-ajax-complete="App.enableForm" data-ajax-success="App.formSuccess" data-ajax-error="App.messageOnError">
    {{ csrf_field() }}
    {{ method_field('PUT') }}

    <div class="form-group">
        <div class="row">
            <div class="col-xs-12 col-sm-4">
                <label for="advancedCostcall" class="control-label control-label-costcall">{{ trans('user.form.costcall') }}</label>
            </div>
            <div class="col-xs-12 col-sm-3">
                <div class="input-group">
                    @macros(input, 'costcall', $lawyer, ['form' => 'advanced', 'group' => false, 'label' => false])
                </div>
            </div>
            <div class="col-xs-12">
                <div class="small help-block">{{ trans('user.form.costcall_description', ['price' => config('site.call.min_price', 500)]) }}</div>
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="row">
            <div class="col-xs-12 col-sm-4">
                <label for="advancedCostchat" class="control-label control-label-costchat">{{ trans('user.form.costchat') }}</label>
            </div>
            <div class="col-xs-12 col-sm-3">
                <div class="input-group">
                    @macros(input, 'costchat', $lawyer, ['form' => 'advanced', 'group' => false, 'label' => false])
                </div>
            </div>
            <div class="col-xs-12">
                <div class="small help-block">{{ trans('user.form.costchat_description', ['price' => config('site.chat.min_price', 500)]) }}</div>
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="row">
            <div class="col-xs-12 col-sm-4">
                <label for="advancedCostdocument" class="control-label control-label-costdocument">{{ trans('user.form.costdocument') }}</label>
            </div>
            <div class="col-xs-12 col-sm-3">
                <div class="input-group">
                    @macros(input, 'costdocument', $lawyer, ['form' => 'advanced', 'group' => false, 'label' => false])
                </div>
            </div>
            <div class="col-xs-12">
                <div class="small help-block">{{ trans('user.form.costdocument_description', ['price' => config('site.document.min_price', 800)]) }}</div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-7">
            <div class="form-group">
                @macros(textarea, 'cost', $lawyer, ['form' => 'advanced', 'rows' => 4, 'group' => false, 'label' => trans('user.form.cost')])
                <div class="small help-block">{{ trans('user.form.cost_description') }}</div>
            </div>
        </div>
        <div class="clearfix"></div>
        <div class="col-xs-7">
            <div class="form-group">
                @macros(textarea, 'aboutmyself', $lawyer, ['form' => 'advanced', 'rows' => 4, 'group' => false, 'label' => trans('user.form.aboutmyself')])
                <div class="small help-block">{{ trans('user.form.aboutmyself_description', ['count' => config('site.user.aboutmyself.max_string', 150)]) }}</div>
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="row">
            <div class="clearfix col-xs-12 col-sm-offset-3 col-sm-4">
                <button type="submit" class="pull-right btn btn-block btn-success"><span class="glyphicon glyphicon-floppy-disk" aria-hidden="true"></span> {{ trans('form.action.save_data') }}</button>
            </div>
        </div>
    </div>
</form>
