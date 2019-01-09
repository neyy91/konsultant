{{-- Модальная форма авторизации --}}

<div class="modal fade login-modal" id="login">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="pull-right close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">@lang('user.authorization')</h4>
            </div>
            <div class="clearfix modal-body">
                @include('user.login_form')
            </div>
        </div>
    </div>
</div>
