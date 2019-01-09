{{-- 
    Содержание iframe при при добавлении награды.
 --}}

<script>
    var App = window.parent.App;
    delete App.iframeRegistry[window.name];
    var $top = window.parent.$;
    document.addEventListener('DOMContentLoaded', function() {
        $top('.user-edit-honor-empty:first').removeClass('user-edit-honor-empty').find('.honor-item').css('background-image', 'url({{ $honor->url }})').find('.honor-delete').attr('data-ajax-url', '{{ route('user.edit.honor.delete', ['honor' => $honor]) }}');
        var $formEditHonors = $top('#formEditHonors');
        $formEditHonors.trigger('form.submited');
        @if ($count >= config('site.user.honor.count', 4))
            $formEditHonors.addClass('hidden');
        @endif

   });
</script>

@include('common.messages_iframe', ['messages' => $messages])
