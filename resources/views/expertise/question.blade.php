@unlessmacros(user_photo)
    @include('user.photo')
@endif
@php
    $expertiseExperts = $question->expertiseExperts;
@endphp
{{-- Экспертиза вопроса --}}
<div class="panel panel-warning expertises-panel">
    <div class="panel-heading">
        <h3 class="panel-title"><strong>@lang('expertise.title')</strong>@if ($expertiseExperts->count() > 0).
            <span class="experts">
                @lang('expertise.experts'):
                <span class="expert-items">
                    @foreach ($expertiseExperts as $exp)<a href="{{ route('lawyer', ['lawyer' => $exp->lawyer]) }}" class="expert-item" target="_blank">{{ $exp->lawyer->user->display_name }}</a>@if (!$loop->last), @endif @endforeach
                </span>
            </span>
        @endif</h3>
    </div>
    <div class="panel-body">
        
        <div class="expertise-items">
            @include('expertise.items', ['expertises' => $expertises])
        </div>
    </div>
    @can('message', [App\Models\Expertise::class, $question])
        <div class="panel-footer">
                @include('expertise.form', ['question' => $question])
        </div>
    @endcan
</div>