@php
    $messages = [];
    foreach (['success', 'info', 'warning', 'danger'] as $type) {
        $message = session($type);
        if ($message) {
            $messages[$type] = is_array($message) ? $message : [$message];
        }
    }
    $errors_exists = isset($errors) && count($errors) > 0;
    $messages_exists = !empty($messages);
@endphp
@if ($messages_exists || $errors_exists)
    <div class="container container-messages">
        <div class="page-messages">
            @if ($messages_exists)
                @include('common.messages')
            @endif
            @if ($errors_exists)
                @include('common.errors')
            @endif
        </div>
    </div>
@else
    <div class="container-fluid messages-container"><div class="messages-fixed"><div class="row messages-row"><div class="col-xs-12 col-sm-4 col-sm-offset-8 messages-col"><div class="messages-page"></div></div></div></div></div>
@endif