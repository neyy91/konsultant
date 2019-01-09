{{-- Поля для регистрации --}}
@php
    $col = isset($col) ? $col : 4;
@endphp
<div class="form-group">
    <div class="row">
        <div class="col-xs-12 col-sm-{{ $col }}">
            <label for="userFirstname" class="control-label control-label-firstname">{{ trans('user.form.firstname') }} <span class="glyphicon glyphicon-asterisk text-danger required-field" aria-hidden="true"></span></label>
        </div>
        <div class="col-xs-12 col-sm-{{ 12 - $col }}">
            @macros(input, 'firstname', null, ['form' => 'user', 'required' => true, 'label' => false, 'group' => false, 'value' => old('firstname')])
        </div>
    </div>
</div>

<div class="form-group">
    <div class="row">
        <div class="col-xs-12 col-sm-{{ $col }}">
            <label for="userEmail" class="control-label control-label-email">{{ trans('user.form.email') }} <span class="glyphicon glyphicon-asterisk text-danger required-field" aria-hidden="true"></span></label>
        </div>
        <div class="col-xs-12 col-sm-{{ 12 - $col }}">
            @macros(input, 'email', null, ['form' => 'user', 'required' => true, 'label' => false, 'group' => false, 'value' => old('email')])
        </div>
    </div>
</div>