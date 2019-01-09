<li><a href="{{ route('questions') }}">{{ trans('question.title.questions') }}</a></li>
@if ($question->categoryLaw->parent->parent_id)
    <li><a href="{{ route('questions.category', ['category' => $question->categoryLaw->parent]) }}">{{ $question->categoryLaw->parent->name }}</a></li>
@endif
