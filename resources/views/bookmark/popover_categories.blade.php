{{-- Категории закладок --}}
@foreach ($categories as $category)
    @php
        $is = $bookmark && $category->id == $bookmark->category->id;
        $from = isset($from) ? $from : 'questions';
        $data = ['category' => $category->id, 'question' => $question->id, 'from' => $from];
        if ($bookmark) {
            $data['id'] = $bookmark->id;
        }
        if ($is) {
            $route = route('bookmark.delete', ['bookmark' => $bookmark]);
            $method = 'DELETE';
        }
        else {
            $route = route('bookmark.create');
            $method = 'POST';
        }
    @endphp
    <a href="#" class="list-group-item bookmark-action @if ($is) active @endif ajax" data-on="click" data-ajax-url="{{ $route }}" data-ajax-method="{{ $method }}" data-ajax-context='{"parents": ".popover:first"}' data-ajax-data='{{ json_encode($data) }}' data-ajax-data-type="json" data-ajax-success="App.bookmarkQuestion" data-ajax-error="App.messageOnError">{{ $category->name }}</a>
@endforeach