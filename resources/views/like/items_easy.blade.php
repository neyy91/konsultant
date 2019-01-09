{{-- простой способ отображения --}}
@unlessmacros(user_photo)
    @include('user.photo')
@endif

@forelse ($likes as $like)
    @php
        $user = $like->user;
    @endphp
    <div class="media like-media like-media-easy">
        @macros(user_photo, ['user' => $user, 'size' => 'small', 'attributes' => ['class' => 'pull-left media-object']])
        <div class="media-body">
            <h4 class="media-heading">{{ $user->display_name }}</h4>
            <p>{{ $like->display_text }}</p>
        </div>
    </div>  
@empty
    <div class="empty-text like-empty-text">@lang('like.no_liked')</div>
@endforelse
{{-- <div class="clearfix">
    <a href="{{ route('lawyer.liked', ['lawyer' => $lawyer]) }}" class="pull-right small more likes-more">@lang('like.view_all_liked_lawyer')</a>
</div>
 --}}