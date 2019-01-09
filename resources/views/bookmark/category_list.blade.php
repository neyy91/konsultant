{{-- Список категории --}}
<ul class="list-group items categories-management-items">
    @foreach ($categories as $category)
        @php
            $canModify = Gate::allows('category-modify', [App\Models\Bookmark::class, $category]);
        @endphp
    <li class="list-group-item item category-item bookmark-count-set" data-id="{{ $category->id }}">
        <span class="pull-right right-offset">
        @if ($canModify)
            <a href="#" class="delete small text-danger script-action ajax" data-on="click" data-ajax-url="{{ route('bookmark.category.delete', ['category' => $category]) }}" data-ajax-method="DELETE" data-ajax-context='{"parents" : ".modal:first"}' data-ajax-data-type="json" data-ajax-success="App.bookmarkCategoryModifi" data-ajax-error="App.messageOnError">@lang('form.action._delete')</a>
        @else
            &nbsp;
        @endif
        </span>
        <span class="badge count">{{ $userRepository->bookmarksCount($lawyer, $category->id) }}</span>
        <a class="category-link" href="{{ route('user.bookmarks', ['category' => $category]) }}">{{ $category->name }}</a>
        @if ($canModify)
            <a href="#" class="change-start visible-control" data-hide=".category-link, .change-start" data-show=".form-categories-update" data-parent=".category-item:first"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span></a>
            @php
                $route = route('bookmark.category.update', ['category' => $category]);
            @endphp
            <form action="{{ $route }}" method="POST" role="form" class="form-categories-update ajax" data-on="submit" data-ajax-url="{{ $route }}" data-ajax-method="PUT" data-ajax-context='{"parents" : ".modal:first"}' data-ajax-data-type="json" data-ajax-data="App.serializeToObject" data-ajax-before-send="App.disableForm" data-ajax-success="App.bookmarkCategoryModifi" data-ajax-error="App.messageOnError">
                <div class="input-group">
                    <input type="text" class="form-control input-xs" id="categoryName" name="name">
                    <span class="input-group-btn">
                        <button type="submit" class="btn btn-success btn-xs"><span class="glyphicon glyphicon-floppy-saved" aria-hidden="true"></span></button>
                        <a href="#" class="form-cancel script-action visible-control" data-show=".category-link, .change-start" data-hide=".form-categories-update" data-parent=".category-item:first">@lang('form.action.cancel')</a>
                    </span>
                </div>
            </form>
        @endif
    </li>
    @endforeach
</ul>