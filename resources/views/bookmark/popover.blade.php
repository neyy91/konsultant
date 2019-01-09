{{-- Категории закладок --}}
@php
    $from = isset($from) ? $from : 'questions';
@endphp
<div class="title">@lang('bookmark.title')</div>
<div class="small subtitle">
    @lang('bookmark.select_bookmark_category')
</div>
<div class="list-group bookmark-categories">
    @include('bookmark.popover_categories', ['categories' => $categories, 'bookmark' => $bookmark, 'from' => $from])
</div>
@include('bookmark.form', ['type' => 'popover', 'question' => $question, 'bookmark' => $bookmark])
@if ($from != 'bookmarks')
    <div class="management-link">
        <a href="{{ route('user.bookmarks', ['id' => null, 'show' => 'management']) }}" target="_blank">@lang('bookmark.management_bookmark_categories') <span class="glyphicon glyphicon-new-window" aria-hidden="true"></span></a>
    </div>
@endif
