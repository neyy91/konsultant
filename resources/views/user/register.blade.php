@extends('layouts.app')
@extends('layouts.page.one')

@section('end')
    @parent
    <script src="{{ asset2('assets/scripts/user.js') }}"></script>
    <script>
        $(function() {
            $('#setUrlUser').html('<input type="text" name="url" value="url" class="form-control form-field-url" id="registrationUrl">');
        });
    </script>
@stop

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <h1 class="panel-heading">@lang('user.registration')</h1>
                <div class="panel-body">
                    @php
                        $as = old('as');
                        $as = $as ? $as : App\Models\User::TYPE_USER;
                        $route = route('register');
                    @endphp
                    <form class="form-horizontal form-registration ajax" role="form" method="POST" action="{{ $route }}" data-on="submit" data-ajax-method="POST" data-ajax-url="{{ $route }}" data-ajax-data-type="json" data-ajax-data="App.serializeToObject" data-ajax-before-send="App.disableForm" data-ajax-context="this" data-ajax-complete="App.enableForm" data-ajax-error="App.messageOnError" data-ajax-success="App.redirect">
                        {{ csrf_field() }}

                        <div class="form-group">
                            <label class="col-sm-12 col-md-4 control-label">@lang('user.form.registration_as')</label>
                            <div class="row">
                                <div class="col-xs-12 col-sm-2">
                                    <div class="radio">
                                        <label class="visible-control disable-control" data-parent=".form-registration:first" data-show=".client-show,.client-description" data-hide=".as-hide" data-disable="#registrationLastname,#registrationCompany">
                                            <input type="radio" name="as" value="{{ App\Models\User::TYPE_USER }}" @if ($as == App\Models\User::TYPE_USER) checked @endif>
                                            @lang('user.client')
                                        </label>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-2">
                                    <div class="radio">
                                        <label class="visible-control disable-control" data-parent=".form-registration:first" data-show=".lawyer-description,.lawyer-show" data-hide=".as-hide" data-disable="#registrationCompany" data-enable="#registrationLastname">
                                            <input type="radio" name="as" value="{{ App\Models\User::TYPE_LAWYER }}" @if ($as == App\Models\User::TYPE_LAWYER) checked @endif>
                                            @lang('user.lawyer')
                                        </label>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-2">
                                    <div class="radio">
                                        <label class="visible-control disable-control" data-parent=".form-registration:first" data-show=".company-description,.company-show" data-hide=".as-hide" data-enable="#registrationLastname,#registrationCompany">
                                            <input type="radio" name="as" value="{{ App\Models\User::TYPE_COMPANY }}" @if ($as == App\Models\User::TYPE_COMPANY) checked @endif>
                                            @lang('user.company')
                                        </label>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                                <div class="col-xs-12 col-sm-10 col-sm-offset-1">
                                    <div class="alert alert-info as-description as-hide client-description">
                                        @lang('user.form.client_description')
                                    </div>
                                    <div class="alert alert-info as-description as-hide lawyer-description">
                                        @lang('user.form.lawyer_description')
                                    </div>
                                    <div class="alert alert-info as-description as-hide company-description">
                                        @lang('user.form.company_description')
                                    </div>
                                </div>
                            </div>
                            
                        </div>

                        <div class="form-group{{ $errors->has('firstname') ? ' has-error' : '' }}">
                            <label for="registrationFirstname" class="col-sm-12 col-md-4 control-label">@lang('user.form.firstname')</label>

                            <div class="col-sm-12 col-md-6">
                                <input id="registrationFirstname" type="text" class="form-control form-field-firstname" name="firstname" value="{{ old('firstname') }}">

                                @if ($errors->has('firstname'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('firstname') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="hide-easy as-hide lawyer-show company-show form-group{{ $errors->has('lastname') ? ' has-error' : '' }}">
                            <label for="registrationLastname" class="col-sm-12 col-md-4 control-label">@lang('user.form.lastname')</label>

                            <div class="col-sm-12 col-md-6">
                                <input id="registrationLastname" type="text" class="form-control form-field-lastname" name="lastname" value="{{ old('lastname') }}" disabled >

                                @if ($errors->has('lastname'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('lastname') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="hide-easy as-hide company-show form-group{{ $errors->has('company') ? ' has-error' : '' }}">
                            <label for="registrationCompany" class="col-sm-12 col-md-4 control-label">@lang('user.form.company')</label>

                            <div class="col-sm-12 col-md-6">
                                <input id="registrationCompany" type="text" class="form-control form-field-company" name="company" value="{{ old('company') }}" disabled >

                                @if ($errors->has('company'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('company') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="registrationEmail" class="col-sm-12 col-md-4 control-label">@lang('user.form.email')</label>

                            <div class="col-sm-12 col-md-6">
                                <input id="registrationEmail" type="email" class="form-control form-field-email" name="email" value="{{ old('email') }}">

                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <label for="registrationPassword" class="col-md-4 control-label">@lang('user.form.password')</label>

                            <div class="col-md-6">
                                <input id="registrationPassword" type="password" class="form-control form-field-password" name="password">

                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                            <label for="registrationPasswordConfirm" class="col-md-4 control-label">@lang('user.form.password_confirmation')</label>

                            <div class="col-md-6">
                                <input id="registrationPasswordConfirm" type="password" class="form-control form-field-password-confirmation" name="password_confirmation">

                                @if ($errors->has('password_confirmation'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password_confirmation') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div id="setUrlUser" class="hidden"></div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    <span class="glyphicon glyphicon-ok" aria-hidden="true"></span> &nbsp; 
                                    <span class="text">@lang('user.to_register')</span>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
