{{-- 
    Форма редактирования уведомлений.
--}}
@php
    $route = route("user.edit.notifications");
    $lawyer = $user->lawyer;
    $notifications = $user->notifications->groupBy('type')->toArray();
    $ncount = count($formVars['notifications']);
@endphp
@include('form.fields')
@section('form_required')@stop
<form action="{{ $route }}" method="POST" role="form" id="formEditNotifications" class="form form-vertical form-label-block form-edit-notifications ajax" data-on="submit" data-ajax-method="PUT" data-ajax-url="{{ $route }}" data-ajax-data-type="json" data-ajax-data="App.serializeToObject" data-ajax-before-send="App.disableForm" data-ajax-context="this" data-ajax-complete="App.enableForm" data-ajax-success="App.formSuccess" data-ajax-error="App.messageOnError">
    {{ csrf_field() }}
    {{ method_field('PUT') }}

    <div class="row">
    @foreach ($formVars['notifications'] as $keynotifi => $notifies)
        <div class="col-xs-12">
            @php
                $class = str_replace('_', '-', $keynotifi);
            @endphp
            <div class="checkbox">
                <label class="control-label"><input type="checkbox" class="form-control-checkbox form-field-{{ $class }} change-check-all" data-fields=".field-checked-{{ $class }}"> <b class="label-text checkbox-label-text label-text">@lang('notification.groups.' . $keynotifi)</b></label>
            </div>
        </div>
        <div class="clearfix"></div>
        <div class="col-xs-12 col-xs-offset-1">
        @foreach ($notifies as $notifiId)
            @macros(select, 'notifications', null, ['form' => 'user', 'type' => 'checkbox', 'items' => [$notifiId => $formVars['notifi_types'][$notifiId]], 'key' => $notifiId, 'value' => isset($notifications[$notifiId]) ? $notifiId : false, 'class' => 'field-checked-' . $class])
        @endforeach 
        </div>
    @endforeach
    </div>

    <div class="form-group">
        <div class="row">
            <div class="clearfix col-xs-12 col-sm-4">
                <button type="submit" class="btn btn-block btn-success"><span class="glyphicon glyphicon-floppy-disk" aria-hidden="true"></span> {{ trans('form.action.save_data') }}</button>
            </div>
        </div>
    </div>
</form>
