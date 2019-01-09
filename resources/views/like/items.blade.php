{{-- Отзывы юриста --}}
@include('like.item')
@if ($likes && $likes->count() > 0)
    <div class="row">
        <div class="col-xs-12 col-sm-6">
        @foreach ($likes->nth(2, 0) as $num => $like)
            @macros(like_item, $like)
        @endforeach
        </div>
        <div class="col-xs-12 col-sm-6">
        @foreach ($likes->nth(2, 1) as $num => $like)
            @macros(like_item, $like)
        @endforeach
        </div>
    </div>
@endif
