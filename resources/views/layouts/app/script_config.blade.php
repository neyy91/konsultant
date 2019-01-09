<script>
    App = {
        messages: {
            error: '@lang('app.message.error')',
            loaded: '@lang('app.message.loaded_data')'
        },
        config: {
            ajax: {
                url: '{{ isset($ajaxUrl) ? $ajaxUrl : route('ajax.layout') }}',
                success: function() {
                    return App.AJAXLoadSuccess;
                }
            },
            chat: {
                url: {
                    view: '{{ route('user.chat.view.layout') }}',
                    incoming: '{{ route('user.chat.incoming.layout') }}',
                    viewed: '{{ route('user.chat.viewed') }}'
                },
                show: true,
                linebreak: '{{ $user ? $user->linebreak : \App\Models\User::LINEBREAK_DEFAULT }}',
                comet: {
                    ssl: {{ env('PUSHSTREAM_SSL') ? 'true' : 'false' }},
                    host: {!! ($url = env('PUSHSTREAM_HOST', null)) ? "'{$url}'" : 'document.location.href' !!},
                    port: {{ ($port = env('PUSHSTREAM_PORT', null)) ?: 'document.location.port' }}
                },
                // channel: 'chat'
                channel: '{{ $user ? App\Events\Chat\ChatMessage::CHANNEL_PREFIX . $user->comet_key : null }}'
            },
            route: @if (\Route::current()) '{{ \Route::current()->getName() }}' @else null @endif
        },
    };
</script>
