@include('user.photo')

@macros(chat_user($user, $counts))
    <a href="#dialogUser" class="list-group-item chat-user" data-id="{{ $user->id }}" id="chatUser{{ $user->id }}">
    @macros(user_photo, ['user' => $user, 'size' => 'xsmall', 'attributes' => ['class' => 'chat-user-image']])
    <span class="chat-user-name">{{ $user->display_name }}</span>
    <span class="badge chat-user-message-count">{{ isset($counts[$user->id]) ? $counts[$user->id] : '' }}</span>
    </a>
@endmacros