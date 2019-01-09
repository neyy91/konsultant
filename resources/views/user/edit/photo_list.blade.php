{{-- 
    Список фотографии разного размера.
 --}}
@php
    $sizes = config('site.user.photo.sizes');
@endphp
@if ($user->photo)
    <img src="{{ $user->photo->url }}" alt="{{ $user->fullname }}" width="{{ $sizes['large'][0] }}">
    <img src="{{ $user->photo->url }}" alt="{{ $user->fullname }}" width="{{ $sizes['small'][0] }}">
@else
    @php
        $src = default_user_photo($user);
    @endphp
    <img src="{{ $src }}" alt="{{ $user->fullname }}" width="{{ $sizes['large'][0] }}">
    <img src="{{ $src }}" alt="{{ $user->fullname }}" width="{{ $sizes['small'][0] }}">
@endif