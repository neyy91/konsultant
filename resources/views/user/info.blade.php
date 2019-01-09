{{-- 
    Инфомация о пользователе(клиенте).
--}}
@unlessmacros(user_photo)
    @include('user.photo')
@endif

<div class="row">
    @if ($user->photo)
        <div class="col-xs-1">
            @macros(user_photo, ['user' => $user])
        </div>
    @endif
    <div class="{{ $user->photo ? 'col-xs-11' : 'col-xs-12' }}">
        <div class="registered"><span class="glyphicon glyphicon-user" aria-hidden="true"></span> {{ trans('user.registered') }}: {{ $user->created_at->format(config('site.date.format.short', 'd.m.Y')) }}</div>
        <div class="counts">
            @php
                $count = [
                    'questions' => $user->questions->count(),
                    'documents' => $user->documents->count(),
                    'calls' => $user->calls->count(),
                ];
            @endphp
            @if ($count['questions'] > 0)
                <div class="count-questions"><a href="{{ route('questions.user', ['id' => $user->id]) }}"><span class="glyphicon glyphicon-question-sign" aria-hidden="true"></span> {{ trans_choice('question.count_questions', $count['documents'], ['count' => $count['documents']]) }}</a></div>
            @endif
            @if ($count['documents'])
                <div class="count-documents"><a href="{{ route('documents.user', ['id' => $user->id]) }}"><span class="glyphicon glyphicon-file" aria-hidden="true"></span> {{ trans_choice('document.count_documents', $count['documents'], ['count' => $count['documents']]) }}</a></div>
            @endif
            @if ($count['calls'])
                <div class="count-calls"><a href="{{ route('calls.user', ['id' => $user->id]) }}"><span class="glyphicon glyphicon-earphone" aria-hidden="true"></span> {{ trans_choice('call.count_calls', $count['calls'], ['count' => $count['calls']]) }}</a></div>
            @endif
        </div>
    </div>
</div>
