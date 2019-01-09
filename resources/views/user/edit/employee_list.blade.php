@forelse ($employees as $employee)
    <tr>
        <td><a href="{{ route('lawyer', $employee) }}" class="link-employee" target="_blank">{{ $employee->user->fullname }}</a></td>
        <td nowrap><a href="#" class="text-danger link-delete ajax" data-on="click" data-ajax-method="DELETE" data-ajax-url="{{ route('user.edit.employees.delete', ['lawyer' => $employee->user->lawyer]) }}" data-ajax-data-type="json" data-ajax-context='.form-edit-employees' data-ajax-error="App.messageOnError" data-ajax-success="App.employeesModifi"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span> @lang('form.action.delete')</a></td>
    </tr>
@empty
    <tr>
        <td colspan="2"><em class="text-muted empty">@lang('user.employees_not_found')</em></td>
    </tr>
@endforelse