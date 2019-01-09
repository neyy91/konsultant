{{--
    Список вопросов
--}}
@foreach ($documents as $document)
    <article class="document">
        <h3 class="title"><a href="{{ route('document.view', ['document' => $document]) }}" class="title-link">{{ $document->title }}</a></h3>
        <div class="description">{{ $document->description }}</div>
    </article>
@endforeach
