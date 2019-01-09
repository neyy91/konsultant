{{-- Email feedback --}}
<p>
    Сообщение от 
    @if ($user)
        @if ($user->lawyer)
            юриста
        @endif
        <b>{{ $user->display_name }}</b>, ID {{ $user->id }}.
    @else
        анонимного пользователя.
    @endif
</p>
<ul>
    <li><b>Email:</b> {{ $data['contact'] }}</li>
    <li><b>Причина для связи:</b> @lang('feedback.themes.' . $data['theme'])</li>
    <li><b>Сообщение:</b><br>
        {{ $data['text'] }}
    </li>
</ul>
