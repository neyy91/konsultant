{{-- 
    Содержание iframe. Спиок ответов и сообщения.
 --}}

@include('like.thumbs')
@include('answer.replies')
@include('common.status')
@php
    $ucfType = ucfirst($type);

    $showDefault = ['cost' => false, 'likes' => false, 'clarifies' => false];
    $show = isset($show) ? array_merge($showDefault, $show) : $showDefault;

@endphp
@if ($model->answers && ($count = $model->answers->count()) > 0)
    <div id="source">
        @if ($isAnswer = ($type == 'answer'))
            @macros(answer_replies, $model->answers, $model)
        @else
             {{-- @include('answer.list', ['type' => $type, 'route' => $route,  'answers' => $model->answers]) --}}
             @include('answer.list', ['type' => $type, 'route' => $route,  'model' => $model, 'cost' => $show['cost'], 'likes' => $show['likes'], 'clarifies' => $show['clarifies']])
        @endif
    </div>
    @if ($status = isset($model->status))
        <div id="statuses">
            @macros(status, $model)
        </div>
    @endif
    <script>
        var App = window.top.window.App;
        delete App.iframeRegistry[window.name];
        var $top = window.top.window.$;
        document.addEventListener('DOMContentLoaded', function() {
            var source = document.getElementById('source').innerHTML;
            @if ($isAnswer)
                $top('#answer{{ $model->id }}').find('.answer-replies').html(source);
                $top('#formReply').trigger('form.submited');
            @else
                $top('#answers').find('.answer-items').html(source).end().find('.count:first').html('{{ $count }}');
                @if ($status)
                    $top('.statuses').html(document.getElementById('statuses').innerHTML);
                @endif
                $top('#answer{{ $ucfType }}Panel, .action-go-form-answer').remove();
                $top('#formAnswer{{ $ucfType }}').trigger('form.submited');
            @endif
       });
    </script>
@endif

@include('common.messages_iframe', ['messages' => $messages])
