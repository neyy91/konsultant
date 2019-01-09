{{-- Пункты в выпадающем меню на странице закладки. --}}
<li class="dropdown-header">@lang('bookmark.select_bookmark_category')</li>
@if ($category)
    <li class="item bookmark-count-set" data-id="{{ $category->id }}"><a href="{{ route('user.bookmarks') }}"><span class="badge right">{{ $lawyer->bookmarks->count() }}</span>@lang('bookmark.all_bookmarks')</a></li>
@endif
@foreach ($categories as $cat)
    <li class="item bookmark-count-set @if ($category && $cat->id == $category->id) active @endif" data-id="{{ $cat->id }}">
    @if ($category && $category->id == $cat->id)
        <a href="#" class="noop">
    @else
        <a href="{{ route('user.bookmarks', ['category' => $cat->id]) }}">
    @endif
    <span class="badge right count">{{ $userRepository->bookmarksCount($lawyer, $cat->id) }}</span>{{ $cat->name }}</a></li>
@endforeach