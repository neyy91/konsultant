{{-- 
    Форма редактирования email и смены пароля.
--}}
@php
    $route = route("user.edit.email_password");
@endphp
@include('form.fields')
<form action="{{ $route }}" method="POST" role="form" id="formEditEmailPassword" class="form form-vertical form-label-block form-edit-email-password ajax" data-on="submit" data-ajax-method="PUT" data-ajax-url="{{ $route }}" data-ajax-data-type="json" data-ajax-data="App.serializeToObject" data-ajax-before-send="App.disableForm" data-ajax-context="this" data-ajax-complete="App.enableForm" data-ajax-success="App.emailPassword" data-ajax-error="App.messageOnError">
    {{ csrf_field() }}
    {{ method_field('PUT') }}

    <div class="form-group">
        <div class="row">
            <div class="col-xs-12 col-sm-3">
                <label for="emailPasswordEmail" class="control-label control-label-email">{{ trans('user.form.email') }}</label>
            </div>
            <div class="col-xs-12 col-sm-5">
                <div class="input-group">
                    @macros(input, 'email', $user, ['form' => 'email_password', 'group' => false, 'label' => false, 'disabled' => true])
                    <span class="input-group-addon"><a href="#emailPasswordEmail" class="toggle-disabled-fields toggle-tooltip" data-focus="1" data-container="body" title="{{ trans('user.edit_email') }}"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span></a></span>
                </div>
            </div>
            <div class="col-xs-12">
                <div class="small help-block">{{ trans('user.form.email_description') }}</div>
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="row">
            <div class="col-xs-12 col-sm-3">
                <label for="emailPasswordNewPassword" class="control-label control-label-new-password">{{ trans('user.form.new_password') }}</label>
            </div>
            <div class="col-xs-12 col-sm-4">
                @macros(input, 'new_password', $user, ['form' => 'email_password', 'type' => 'password', 'group' => false, 'label' => false])
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="row">
            <div class="col-xs-12 col-sm-3">
                <label for="emailPasswordNewPasswordConfirmation" class="control-label control-label-new-password-confirmation">{{ trans('user.form.new_password_confirmation') }}</label>
            </div>
            <div class="col-xs-12 col-sm-4">
                @macros(input, 'new_password_confirmation', $user, ['form' => 'email_password', 'type' => 'password', 'group' => false, 'label' => false])
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="row">
            <div class="col-xs-12 col-sm-3">
                <label for="emailPasswordPassword" class="control-label control-label-current-password">{{ trans('user.form.current_password') }} <span class="glyphicon glyphicon-asterisk text-danger required-field" aria-hidden="true"></span></label>
            </div>
            <div class="col-xs-12 col-sm-4">
                @macros(input, 'current_password', null, ['form' => 'email_password', 'type' => 'password', 'group' => false, 'label' => false, 'required' => true])
            </div>
            <div class="col-xs-12">
                <div class="small help-block">{{ trans('user.form.current_password_description') }}</div>
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
