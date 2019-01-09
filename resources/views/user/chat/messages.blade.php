{{-- Список сообщений --}}
@php
    $prevType = isset($prevType) ? $prevType : null;
    $day = isset($day) ? $day : null;
@endphp
@foreach ($chatList as $chat)
    @continue($chat->is !== $chat::IS_MESSAGE)
    @php
        $type = $chat->getMessageType($me);
    @endphp
    @if (!$day || $day != $chat->created_at->day)
        <div class="clearfix"></div>
        <div class="col-xs-12">
            <hr class="chat-separator">
            <div class="chat-day">
                {{ $chat->created_short }}
            </div>
        </div>
    @endif
    <div class="col-xs-10 @if ($type == 'outgoing') col-xs-offset-2 @endif chat-message-item">
        <div class="popover chat-message @if ($type == 'incoming') right message-incoming {{ $chat->viewed_at === null ? 'message-unviewed' : 'message-viewed' }} @else left message-outgoing @endif @if ($type == $prevType) message-continue @else message-break @endif" id="chatMessage{{ $chat->id }}" data-id="{{ $chat->id }}">
            @if ($type != $prevType)
                <div class="arrow"></div>
            @endif
            <div class="clearfix popover-content message-content">
                <div class="pull-left message-text">{{ $chat->message }}</div>
                <div class="pull-right small chat-date">{{ $chat->created_at->format('H:i') }}</div>
            </div>
        </div>
    </div>
    @php
        $prevType = $type;
        $day = $chat->created_at->day;
    @endphp
@endforeach