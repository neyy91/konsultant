{{-- Форма обновления юриста --}}

<form action="{{ route("lawyer.update.admin", ['lawyer' => $lawyer]) }}" method="POST" role="form">
    {{ csrf_field() }}
    {{ method_field('PUT') }}

    @macros(textarea, 'companyname', $lawyer, ['form' => 'lawyer', 'rows' => 3, 'label' => trans('user.form.companyname')])

    @macros(select, 'companyowner', $lawyer, ['form' => 'lawyer', 'type' => 'checkbox', 'label' => trans('user.form.companyowner')])

    @macros(select, 'expert', $lawyer, ['form' => 'lawyer', 'type' => 'checkbox', 'label' => trans('user.form.expert')])
    
    @macros(input, 'password', null, ['form' => 'user', 'type' => 'password', 'required' => true, 'label' => trans('user.form.your_password')])

    <div class="form-group form-actions">
        @include('form.actions', ['action' => 'update', 'deleteUrl' => route('lawyer.delete.form.admin', ['lawyer' => $lawyer]), 'cancelUrl' => route('users.admin')])
    </div>

</form>
