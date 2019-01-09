{{-- Форма добавления --}}
@php
    $route = route('bookmark.category.create');
@endphp
<form action="{{ $route }}" method="POST" role="form" class="form-categories-create ajax" data-on="submit" data-ajax-url="{{ $route }}" data-ajax-method="POST" data-ajax-context='{"parents" : ".{{ isset($type) ? $type : 'modal' }}:first"}' data-ajax-data-type="json" data-ajax-data="App.serializeToObject" data-ajax-before-send="App.disableForm" data-ajax-success="App.bookmarkCategoryCreate" data-ajax-error="App.messageOnError">
    <input type="hidden" name="type" value="{{ isset($type) ? $type : 'modal' }}">
    @if (isset($question) && $question)
        <input type="hidden" name="question" value="{{ $question->id }}">
    @endif
    @if (isset($bookmark) && $bookmark)
        <input type="hidden" name="bookmark" value="{{ $bookmark->id }}">
    @endif
    <div class="input-group">
        <input type="text" class="form-control" id="categoryName" name="name" placeholder="@lang('bookmark.enter_bookmark_category_name')">
        <span class="input-group-btn">
            <button type="submit" class="btn btn-primary"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span></button>
        </span>
    </div>
</form>