{{-- категории на главной --}}

<div class="panel panel-info home-categories">
    <div class="panel-heading">
        <h2 class="panel-title">@lang('category.categories_themes')</h2>
    </div>
    <div class="panel-body">
        @php
            $from = 0;
        @endphp
        @foreach ($categories as $num => $category)
            @if ($from != $category->from)
                @php
                    $from = $category->from;
                @endphp
            <div class="col-xs-12 from"><h3 class="from-title">{{ $category->fromLabel }}</h3></div>
            @endif
            <div class="col-xs-12 col-sm-6 col-md-4 item item-category">
                <h4 class="item-title item-category-title"><a href="{{ route('questions.category', ['category' => $category]) }}" class="item-link item-category-link">{{ $category->name }}</a></h4>
            @if ($category->childs->count() > 0)
                <ul class="items items-childs">
                    @foreach ($category->childs as $child)
                        <div class="item item-child"><h5 class="item-child item-child-title"><a href="{{ route('questions.category', ['category' => $child]) }}" class="item-link item-child-link">{{ $child->name }}</a></h5></div>
                        @if ($child->themes)
                            <ul class="items items-themes">
                                @foreach ($child->themes as $theme)
                                    <div class="small item item-theme"><a href="{{ route('questions.theme', ['theme' => $theme]) }}" class="item-link item-theme-link">{{ $theme->name }}</a></div>
                                @endforeach
                            </ul>
                        @endif
                    @endforeach
                </ul>
            @endif
            </div>
            @if ($num !==0 && ($num + 1)%2 == 0)
                <div class="clearfix visible-sm"></div>
            @endif
            @if (($num + 1)%3 == 0)
                <div class="clearfix visible-md"></div>
            @endif
        @endforeach
    </div>
</div>