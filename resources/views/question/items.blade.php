{{-- Вопросы --}}
@include('admin.dropdown')
@include('bookmark.macros')
@include('common.status')

@php
    $user = Auth::user();
    $bookmarkShow = isset($bookmarkShow) ? $bookmarkShow : false;
    $statusShow = isset($statusShow) ? $statusShow : true;
    $adminShow = isset($adminShow) ? $adminShow : true;
    $from = isset($from) ? $from : 'questions';
@endphp

@forelse ($questions as $num => $question)
    @if ($num > 0)
        <hr>
    @endif
    <article class="clearfix article question">
        @if ($statusShow)
            <div class="statuses">
                @macros(status, $question)
            </div>
        @endif
        <h3 class="title title-question">
        @can('admin', App\Models\User::class)
            @if ($adminShow && Gate::allows('admin', App\Models\User::class))
                @macros(admin_dropdown, ['type' => 'question', 'model' => $question, 'dropdownClass' => 'pull-right', 'btnClass' => 'btn-default btn-xs'])
            @endif
        @endcan
        @if ($bookmarkShow && Gate::allows('bookmark', [App\Models\Bookmark::class, $question]))
            @macros(bookmark_question, $user->lawyer->bookmarks, $question, $from)
        @endif
        <a href="{{ route('question.view', ['question' => $question]) }}" class="title-link">{{ $question->title }}</a></h3>
        <div class="description description-question">{{ $question->description }}</div>
        <div class="row">
            <div class="col-xs-12 col-sm-5">
                <time pubdate datetime="{{ $question->created_at->toIso8601String() }}">{{ $question->created_at->format(config('site.date.long', 'd.m.Y H:i')) }}</time>, <span class="number"><span class="number-label">{{ trans('question.label_number', ['number' => $question->id]) }}</span></span>
            </div>
            <div class="col-xs-12 col-sm-4">
                @if ($question->user)
                    <span class="user user-question author">{{ $question->user->firstname }}</span>
                @endif
                @if ($question->city)
                    <span class="city"><span class="city-label">@lang('city.from_city')</span> <span class="city-value toggle-tooltip" title="{{ $question->city->region->name }}">{{ $question->city->name }}</span></span>
                @endif
            </div>
            <div class="col-xs-12 col-sm-3">
                @php
                    $count = $question->answers->count();
                @endphp
                <a href="{{ route('question.view', ['question' => $question]) }}#answers" class="pull-right count"><span class="glyphicon glyphicon-comment" aria-hidden="true"></span> <span class="count">{{ trans_choice('question.count_answers', $count, ['count' => $count]) }}</span></a>
            </div>
        </div>
        @if (isset($categoryLawShow) && $categoryLawShow)
            <div class="category"><span class="category-label">{{ trans('category.title') }}:</span> <a href="{{ route('questions.category', ['category' => $question->categoryLaw]) }}">{{ $question->categoryLaw->name }}</a></div>
        @endif
    </article>
@empty
    <div class="empty">{{ isset($empty) ? $empty : trans('question.not_found') }}</div>
@endforelse