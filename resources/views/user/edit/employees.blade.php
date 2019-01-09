{{-- 
    Форма добавления/удаления сотрудников.
--}}

@include('form.fields')
@section('form_required') @stop

@php
    $route = route("user.edit.employees.add");
@endphp
<form action="{{ $route }}" method="POST" role="form" id="formEmployees" class="form form-vertical form-label-block form-edit-employees ajax" data-on="submit" data-ajax-method="POST" data-ajax-url="{{ $route }}" data-ajax-data-type="json" data-ajax-data="App.serializeToObject" data-ajax-before-send="App.disableForm" data-ajax-context="this" data-ajax-complete="App.enableForm" data-ajax-error="App.messageOnError" data-ajax-success="App.employeesModifi">
    {{ csrf_field() }}

    <div class="employees user-edit-employees">
        <div class="table-responsive">
            <table class="table table-hover table-condensed table-bordered">
                <thead>
                    <tr>
                        <th>@lang('user.employee')</th>
                        <th class="action" width="1"> </th>
                    </tr>
                </thead>
                <tbody class="employee-list">
                    @include('user.edit.employee_list', ['employees' => $employees])
                </tbody>
            </table>
        </div>
        
    </div>

    <legend>{{ trans('user.add_employee') }}</legend>
    <div class="alert alert-info">
        @lang('user.employees_add_info')
    </div>
    <div class="form-group">
        <div class="row">
            <div class="col-xs-12 col-sm-4">
                <label for="employeesId" class="control-label control-label-id">{{ trans('user.form.employee_id') }}</label>
            </div>
            <div class="col-xs-8 col-sm-3">
                @macros(input, 'id', null, ['form' => 'employees', 'group' => false, 'label' => false, 'required' => true])
            </div>
            <div class="col-xs-4 col-sm-2">
                <button type="submit" class="pull-right btn btn-primary"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span> {{ trans('form.action.add') }}</button>
            </div>
        </div>
    </div>
</form>
