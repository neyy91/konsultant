{{-- макросы заметок --}}
@macros(bookmark_question($bookmarks, $question, $from))
    @php
        $bookmark = $bookmarks ? $bookmarks->where('question_id', $question->id)->first() : null;
        $params = ['id' => $bookmark ? $bookmark->id : 0, 'question' => $question->id, 'from' => $from];
    @endphp
    <a href="#" class="pull-right text-warning select-bookmark-categories toggle-tooltip-loaded toggle-popover-ajax click" data-data='{{ json_encode($params) }}' data-url="{{ route('bookmark.ajax.categories.popover') }}" data-placement="left" data-popover-blur="destroy"><span class="glyphicon glyphicon-{{ $bookmark ? 'star' : 'star-empty' }}" aria-hidden="true"></span></a>
@endmacros