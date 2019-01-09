{{-- Форма обновления пользователя --}}

<form action="{{ route("user.update.admin", ['user' => $user]) }}" method="POST" role="form">
    {{ csrf_field() }}
    {{ method_field('PUT') }}

    @macros(select, 'status', $user, ['form' => 'user', 'items' => $formVars['statuses'], 'label' => trans('app.status'), 'required' => true])

    @macros(input, 'firstname', $user, ['form' => 'user', 'required' => true])

    @macros(input, 'lastname', $user, ['form' => 'user'])

    @macros(input, 'middlename', $user, ['form' => 'user'])

    @macros(input, 'email', $user, ['form' => 'user', 'type' => 'email', 'required' => true])

    @macros(input, 'new_password', null, ['form' => 'user', 'type' => 'password'])

    @macros(input, 'password', null, ['form' => 'user', 'type' => 'password', 'required' => true, 'label' => trans('user.form.your_password')])

    <div class="form-group form-actions">
        @include('form.actions', ['action' => 'update', 'deleteUrl' => route('user.delete.form.admin', ['user' => $user]), 'cancelUrl' => route('users.admin')])
    </div>

</form>
