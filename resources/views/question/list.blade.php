{{--
    Список вопросов
--}}
@foreach ($questions as $question)
    <article class="question">
        <h3 class="title"><a href="{{ route('question.view', ['question' => $question]) }}" class="title-link">{{ $question->title }}</a></h3>
        <div class="description">{{ $question->description }}</div>
    </article>
@endforeach
