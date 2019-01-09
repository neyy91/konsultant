{{-- 
    Сообщения в ifrmae.
 --}}
@if (isset($messages) && !empty($messages))
    <script>
        @foreach ($messages as $messageType => $message)
            @if ($message)
                window.top.window.AJAXMessage.showMessage('{{ $messageType }}', {!! json_encode($message, JSON_HEX_TAG) !!});
            @endif
        @endforeach
    </script>
@endif