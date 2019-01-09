{{-- AJAX чат пользователей --}}

<div id="chat" class="panel-group chat-panel-group chat chat-fixed" role="tablist" aria-multiselectable="false">
    <div class="panel panel-default chat-panel">
        <div class="panel-heading" role="tab" id="chatTab">
            <h3 class="panel-title">
                <a role="button" class="chat-collapse" data-toggle="collapse" data-parent="#chat" href="#chatList" aria-expanded="true" aria-controls="chatList">
                    <span class="glyphicon glyphicon-envelope icon-message" aria-hidden="true"></span> @lang('chat.private_messages') <span class="badge chat-messages-count">{{ $counts['all'] > 0 ? $counts['all'] : '' }}</span>
                </a>
            </h3>
        </div>
        <div id="chatList" class="collapse panel-collapse" role="tabpanel" aria-labelledby="chatTab">

            <div class="list-group chat-users">
                @include('user.chat.layout.dialog_items', ['dialogs' => $dialogs])
            </div>

        </div>
    </div>
</div>


<div class="modal fade dialog-user-modal" id="dialogUser" data-user-id="">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
            <a href="#" class="pull-right btn btn-danger btn-xs toggle-tooltip dialog-close ajax" data-on="click" data-ajax-url="" data-ajax-method="DELETE" data-ajax-context="#dialogUser" data-ajax-data-type="json" data-ajax-success="App.Chat.removeDialogSuccess" data-ajax-error="App.messageOnError" title="@lang('chat.close_dialog')"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></a>
            <a href="" class="pull-right btn btn-default btn-xs toggle-tooltip dialog-to-chat-page" target="_blank" title="@lang('chat.to_chat_page')" data-message="@lang('chat.chat_user_in_page')"><span class="glyphicon glyphicon-new-window" aria-hidden="true"></span></a>
            <button type="button" class="pull-right btn btn-default btn-xs toggle-tooltip dialog-hide" title="@lang('chat.hide_dialog')"><span class="glyphicon glyphicon-minus" aria-hidden="true"></span></button>
                <div class="clearfix modal-title dialog-title">
                    <img src="/storage/default/photo_none.jpg" alt="User avatar" class="pull-left dialog-user-avatar" height="50">
                    <div class="dialog-user-name">User name</div>
                </div>
            </div>
            <div class="modal-body">
                <div class="dialog-messages-wrap">
                    <div class="row dialog-messages">
                        Messages
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <form class="chat-form dialog-message-form ajax" role="form" method="POST" action="" data-on="submit" data-ajax-method="POST" data-ajax-url="" data-ajax-data-type="json" data-ajax-data="App.serializeToObject" data-ajax-before-send="App.disableForm" data-ajax-context="this" data-ajax-complete="App.enableForm" data-ajax-error="App.messageOnError" data-ajax-success="App.Chat.messageSendLayout">
                    <input type="hidden" name="last" value="" class="chat-form-last">
                    <textarea rows="3" name="message" class="form-control input-sm dialog-message-text chat-message-text-enter" placeholder="@lang('chat.form.message')" required></textarea>
                    <button type="submit" class="pull-right btn btn-primary btn-sm dialog-message-send">
                        <span class="glyphicon glyphicon-send" aria-hidden="true"></span> &nbsp; 
                        <span class="text">@lang('form.action.send')</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>