{{-- 
    Кнопки нравится/не_нравится
 --}}
@macros(like_thumbs(array $like))
@php
    $likesURL = route('likes.' . $like['type'], [$like['type'] => $like['model']]);
    $likeURL = route('like.' . $like['type'], [$like['type'] => $like['model']]);
    $count = [
        'like' => $like['model']->likes->where('rating', App\Models\Like::RATING_LIKE)->count(),
        'dontlike' => $like['model']->likes->where('rating', App\Models\Like::RATING_DONT_LIKE)->count(),
    ];
    $suffix = ucfirst($like['type']);
    // if ($user = Auth::user()) {
        // $hasLiked = $like['model']->likes->where('from_id', $user->lawyer ? $user->lawyer->id : $user->id)->where('from_type', $user->lawyer ? App\Models\Lawyer::MORPH_NAME : App\Models\User::MORPH_NAME)->count();
    // }
    // else {
        // $hasLiked = 0;
    // }
@endphp
<div class="likes likes-{{ $like['type'] }}">
    <span class="title">{{ trans('like.useful.' . $like['type']) }}</span>
    <span class="text-success like toggle-popover-ajax hover" data-url="{{ $likesURL }}" data-data='{"rating": {{ App\Models\Like::RATING_LIKE }} }' data-method="GET" data-popover-blur="hide" data-popover-active="show" data-placement="top" data-popover-class="like-popover">
    @php
        $can = Gate::allows('like-' . $like['model']::MORPH_NAME, [App\Models\Like::class, $like['model']]);
    @endphp
    @if($can)
        <a href="#" class="text-success link like-link ajax" data-on="click" data-ajax-url="{{ $likeURL }}" data-ajax-method="POST" data-ajax-context="this" data-ajax-data-type="json" data-ajax-data='{"rating" : {{ App\Models\Like::RATING_LIKE }} }' data-ajax-success="App.createLikeFor{{ $suffix }}" data-ajax-error="App.messageOnError"><span class="glyphicon glyphicon-thumbs-up" aria-hidden="true"></span></a>
    @else
        <span class="glyphicon glyphicon-thumbs-up" aria-hidden="true"></span>
    @endif
        <span class="count like-popover-fixed">{{ $count['like'] }}</span>
    </span> &nbsp; 
    <span class="text-danger dont-like toggle-popover-ajax hover" data-url="{{ $likesURL }}" data-data='{"rating" : {{ App\Models\Like::RATING_DONT_LIKE }} }' data-method="GET" data-popover-blur="hide" data-popover-active="show" data-placement="top" data-popover-class="like-popover">
    @if($can)
        <a href="#" class="text-danger link dont-like-link ajax" data-on="click" data-ajax-url="{{ $likeURL }}" data-ajax-method="POST" data-ajax-context="this" data-ajax-data-type="json" data-ajax-data='{"rating" : {{ App\Models\Like::RATING_DONT_LIKE }} }' data-ajax-success="App.createLikeFor{{ $suffix }}" data-ajax-error="App.messageOnError"><span class="glyphicon glyphicon-thumbs-down" aria-hidden="true"></span></a>
    @else
        <span class="glyphicon glyphicon-thumbs-down" aria-hidden="true"></span>
    @endif
        <span class="count like-popover-fixed">{{ $count['dontlike'] }}</span>
    </span>
</div>
@endmacros
