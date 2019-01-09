{{-- 
    Форма редактирования персональных(основных) данных.
--}}
@php
    $route = route("user.edit.personal");
@endphp
@include('form.fields')
@section('form_required') @stop

<form action="{{ $route }}" method="POST" role="form" id="formEditPersonal" class="form form-vertical form-label-block form-edit-personal ajax" data-on="submit" data-ajax-method="PUT" data-ajax-url="{{ $route }}" data-ajax-data-type="json" data-ajax-data="App.serializeToObject" data-ajax-before-send="App.disableForm" data-ajax-context="this" data-ajax-success="App.formSuccess" data-ajax-complete="App.enableForm" data-ajax-error="App.messageOnError">
    {{-- <legend>{{ trans('user.form.legend.personal') }}</legend> --}}

    <div class="form-group">
        <div class="row">
            <div class="col-xs-12 col-sm-3">
                <label for="personalLastname" class="control-label control-label-lastname">{{ trans('user.form.lastname') }}</label>
            </div>
            <div class="col-xs-12 col-sm-4">
                @macros(input, 'lastname', $user, ['form' => 'personal', 'group' => false, 'label' => false, 'disabled' => $user->lastname ? true : false])
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="row">
            <div class="col-xs-12 col-sm-3">
                <label for="personalFirstname" class="control-label control-label-firstname">{{ trans('user.form.firstname') }}</label>
            </div>
            <div class="col-xs-12 col-sm-4">
                @macros(input, 'firstname', $user, ['form' => 'personal', 'group' => false, 'label' => false, 'disabled' => $user->firstname ? true : false])
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="row">
            <div class="col-xs-12 col-sm-3">
                <label for="personalMiddlename" class="control-label control-label-middlename">{{ trans('user.form.middlename') }}</label>
            </div>
            <div class="col-xs-12 col-sm-4">
                @macros(input, 'middlename', $user, ['form' => 'personal', 'group' => false, 'label' => false, 'disabled' => $user->middlename ? true : false])
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="row">
            <div class="col-xs-12">
                <div class="alert alert-info">
                    @lang('user.change_names_help', ['url' => route('feedback', ['theme' => 'change_name'])])
                </div>
            </div>
        </div>
    </div>

    @if ($user->isUser)
        <div class="form-group">
            <div class="row">
                <div class="col-xs-12 col-sm-3">
                    <label for="personalTelephone" class="control-label control-label-telephone">{{ trans('user.form.telephone') }}</label>
                </div>
                <div class="col-xs-12 col-sm-4">
                    @macros(input, 'telephone', $user, ['form' => 'personal', 'group' => false, 'label' => false])
                </div>
            </div>
        </div>
    @endif


    <div class="form-group">
        <div class="row">
            <div class="col-xs-12 col-sm-3">
                <label for="personalCityId" class="control-label control-label-city-id">{{ trans('user.form.city_id') }}</label>
            </div>
            <div class="col-xs-12 col-sm-4">
                @macros(select, 'city_id', $user, ['form' => 'personal', 'items' => $formVars['cities'], 'group' => false, 'label' => false, 'label_first' => trans('user.form.city_id_first')])
            </div>
        </div>
    </div>

    @if ($user->isLawyer)
        <div class="form-group">
            <div class="row">
                <div class="col-xs-12 col-sm-3">
                    <label for="personalStatus" class="control-label control-label-status">{{ trans('user.form.status') }}</label>
                </div>
                <div class="col-xs-12 col-sm-4">
                    @macros(select, 'status', $user->lawyer, ['form' => 'personal', 'items' => $formVars['statuses'], 'group' => false, 'label' => false, 'label_first' => trans('user.form.status_first')])
                </div>
            </div>
        </div>
    @endif

    <div class="form-group">
        <div class="row">
            <div class="col-xs-12 col-sm-3">
                <label for="personalBirthday" class="control-label control-label-birthday">{{ trans('user.form.birthday') }}</label>
            </div>
            <div class="col-xs-12 col-sm-4">
                @macros(input, 'birthday', $user, ['form' => 'personal', 'type' => 'date', 'group' => false, 'label' => false])
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="row">
            <div class="col-xs-12 col-sm-3">
                <label for="personalGender" class="control-label control-label-gender">{{ trans('user.form.gender') }}</label>
            </div>
            <div class="col-xs-12 col-sm-4">
                @macros(select, 'gender', $user, ['form' => 'personal', 'type' => 'radio', 'items' => $formVars['genders'], 'group' => false])
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
