@include('user.chat.layout.macros')

@foreach ($dialogs as $dialog)
    @macros(chat_user, $dialog->to, $counts)
@endforeach
<div class="empty chat-user-empty @if (count($dialogs) > 0) hidden @endif">@lang('chat.not_found')</div>
