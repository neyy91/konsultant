{{-- 
    Фото профиля пользователя.
 --}}
@if ($user->photo) <img src="{{ $user->photo->url }}" alt="{{ $user->display_name }}" width="60" class="pull-left profile-photo"> @else <img src="{{ default_user_photo($user) }}" alt="{{ $user->display_name }}" width="60" class="pull-left profile-photo"> @endif