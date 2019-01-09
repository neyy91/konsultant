<div class="panel panel-default home-questions">
    <div class="panel-heading">
        <h2 class="panel-title">{!! trans_choice('question.ask_questions', $count, ['count' => $count]) !!}</h2>
    </div>
    <div class="panel-body">
        @include('question.list', ['questions' => $questions])
    </div>
    <div class="panel-footer">
        <a href="{{ route('questions') }}" class="btn btn-info btn-block more more-question">@lang('question.all_questions')</a>
    </div>
</div>
