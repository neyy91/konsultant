{{-- 
    Список уточнений для обновления на стророне клиента.
 --}}
@include('clarify.macros')
@php
    $ucfType = ucfirst($type);
@endphp

@if ($model && ($count = $model->clarifies->count()) > 0)
    <div id="source">
        @macros(clarify_list, ['type' => $type, 'clarifies' => $model->clarifies])
    </div>
    <script>
        var App = window.top.window.App;
        delete App.iframeRegistry[window.name];
        var $top = window.top.window.$;
        document.addEventListener('DOMContentLoaded', function() {
            var $clarifies = $top('#clarifies{{ $ucfType . $model->id }}').html(document.getElementById('source').innerHTML).parents('.clarifies:first');
            @if ($count == 1)
                $clarifies.removeClass('hide');
            @endif ;
            $clarifies.find('.title-clarifies:first').html("{{ $title }}");

            $top('#formClarify{{ $ucfType }}').trigger('form.submited');

            $top('.exists-clarifies-{{ $type . $model->id }}').show();
        });
    </script>
@endif

@include('common.messages_iframe', ['messages' => $messages])
