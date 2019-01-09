@include('user.photo')

@macros(user_photo, ['user' => $user, 'size' => 'xsmall', 'attributes' => ['class' => 'pull-left dialog-user-avatar']])
<div class="dialog-user-name">
    @if ($user->lawyer)
        <a href="{{ route('lawyer', ['lawyer' => $user->lawyer]) }}" class="dialog-user-name-link" target="_blank">{{ $user->display_name }}</a>
    @else
        {{ $user->display_name }}
    @endif
</div>