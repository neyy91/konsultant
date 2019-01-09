{{-- 
    Форма для редактирования настройки чата.
 --}}

@php
    $route = route("user.edit.chat");
    $lawyer = $user->lawyer;
@endphp
@include('form.fields')
@section('form_required') @stop
<form action="{{ $route }}" method="POST" role="form" id="formEditChat" class="form form-vertical form-label-block form-edit-chat ajax" data-on="submit" data-ajax-method="PUT" data-ajax-url="{{ $route }}" data-ajax-data-type="json" data-ajax-data="App.serializeToObject" data-ajax-before-send="App.disableForm" data-ajax-context="this" data-ajax-complete="App.enableForm" data-ajax-error="App.messageOnError" data-ajax-success="App.formSuccess">
    {{ csrf_field() }}
    {{ method_field('PUT') }}
    @foreach (['callavailable', 'weekdays', 'weekend'] as $name)
        <input type="hidden" name="{{ $name }}" value="0">
    @endforeach

    @if ($lawyer)
        <div class="form-group">
            <div class="row">
                <div class="col-xs-12">
                    @macros(select, 'callavailable', $lawyer, ['form' => 'user', 'type' => 'checkbox', 'group' => false])
                    <div class="small help-block">{{ trans('user.form.callavailable_help') }}</div>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="row">
                <div class="col-xs-12 col-sm-4">
                    <label for="userTelephone" class="control-label control-label-telephone">{{ trans('user.form.telephone') }}</label>
                </div>
                <div class="col-xs-12 col-sm-6">
                    @macros(input, 'telephone', $lawyer->user, ['form' => 'user', 'group' => false, 'label' => false])
                </div>
                <div class="col-xs-12">
                    <div class="small help-block">{{ trans('user.form.callavailable_help') }}</div>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="row">
                <div class="col-xs-12 col-sm-4">
                    <label for="userTimezone" class="control-label control-label-timezone">{{ trans('user.form.timezone') }}</label>
                </div>
                <div class="col-xs-12 col-sm-6">
                    @macros(select, 'timezone', $lawyer, ['form' => 'user', 'group' => false, 'label' => false, 'items' => $formVars['timezones'], 'label_first' => false])
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="row">
                <div class="col-xs-12">
                    <label class="control-label">{{ trans('user.form.schedule') }}</label>
                </div>
                <div class="col-xs-12">
                    <div class="row row-weekdays">
                        <div class="col-xs-12 col-sm-4">
                            @macros(select, 'weekdays', $lawyer, ['form' => 'user', 'group' => false, 'type' => 'checkbox'])
                        </div>
                        <div class="col-xs-12 col-sm-6">
                            <div class="row">
                                <div class="col-0 col-xs-1 col-from">
                                    {{ trans('app.from1') }}
                                </div>
                                <div class="col-psm col-xs-5">
                                    @macros(select, 'weekdaysfrom', $lawyer, ['from' => 'user', 'group' => false, 'label' => false, 'items' => $formVars['times'], 'class' => 'input-sm', 'label_first' => false])
                                </div>
                                <div class="col-0 col-xs-1 col-to">
                                    {{ trans('app.to1') }}
                                </div>
                                <div class="col-psm col-xs-5">
                                    @macros(select, 'weekdaysto', $lawyer, ['from' => 'user', 'group' => false, 'label' => false, 'items' => $formVars['times'], 'class' => 'input-sm', 'label_first' => false])
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row row-weekend">
                        <div class="col-xs-12 col-sm-4">
                            @macros(select, 'weekend', $lawyer, ['form' => 'user', 'group' => false, 'type' => 'checkbox'])
                        </div>
                        <div class="col-xs-12 col-sm-6">
                            <div class="row">
                                <div class="col-0 col-xs-1 col-from">
                                    {{ trans('app.from1') }}
                                </div>
                                <div class="col-psm col-xs-5">
                                    @macros(select, 'weekendfrom', $lawyer, ['from' => 'user', 'group' => false, 'label' => false, 'items' => $formVars['times'], 'class' => 'input-sm', 'label_first' => false])
                                </div>
                                <div class="col-0 col-xs-1 col-to">
                                    {{ trans('app.to1') }}
                                </div>
                                <div class="col-psm col-xs-5">
                                    @macros(select, 'weekendto', $lawyer, ['from' => 'user', 'group' => false, 'label' => false, 'items' => $formVars['times'], 'class' => 'input-sm', 'label_first' => false])
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    @endif

    <div class="form-group">
        <div class="row">
            <div class="col-xs-12 col-sm-4">
                <label for="userLinebreak" class="control-label control-label-year">{{ trans('user.form.linebreak') }}</label>
            </div>
            <div class="col-xs-12 col-sm-6">
                @macros(select, 'linebreak', $user, ['form' => 'user', 'type' => 'radio', 'group' => false, 'items' => $formVars['linebreaks']])
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="row">
            <div class="clearfix col-xs-12 col-sm-8">
                <button type="submit" class="pull-right btn btn-success btn-submit"><span class="glyphicon glyphicon-floppy-disk" aria-hidden="true"></span> {{ trans('form.action.save_data') }}</button>
            </div>
        </div>
    </div>
</form>
