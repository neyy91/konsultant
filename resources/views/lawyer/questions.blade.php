{{-- Все вопросы с ответами юриста --}}
@extends('layouts.app')
@extends('layouts.page.one')

@section('breadcrumb')
    @parent
    <li><a href="{{ route('lawyers') }}">{{ trans('lawyer.lawyers') }}</a></li>
    <li><a href="{{ route('lawyer', ['lawyer' => $lawyer]) }}">{{ $user->display_name }}</a></li>
    <li class="active">@lang('question.latest_consultations')</li>
@stop

@section('end')
    @parent
    <script src="{{ asset2('assets/scripts/user.js') }}"></script>
@stop

@section('content')
    @include('lawyer.info', ['lawyer' => $lawyer, 'liked' => $liked, 'link' => route('lawyer', $lawyer)])
    <div class="profile-lawyer profile-lawyer-questions-page">
        <h1 class="title">@lang('question.last_cunsultations_total_count', ['total' => $total])</h1>
        <div class="questions">
            @include('question.items', ['questions' => $questions, 'categoryLawShow' => true])
        </div>
        {{ $questions->links() }}
    </div>
@stop
