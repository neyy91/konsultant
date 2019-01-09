{{-- 
    Содержание iframe при сохранении фото пользователя.
 --}}

<div id="source">
   @include('user.edit.photo_list') 
</div>
<div id="profilePhoto">
   @include('user.profile_photo') 
</div>
<script>
    var App = window.parent.App;
    delete App.iframeRegistry[window.name];
    var $top = window.parent.$;
    document.addEventListener('DOMContentLoaded', function() {
        $top('.profile-photo').get(0).outerHTML = document.getElementById('profilePhoto').innerHTML;
        $top('#formEditPhoto').find('.user-photos').html(document.getElementById('source').innerHTML).end().trigger('form.submited');
   });
</script>

@include('common.messages_iframe', ['messages' => $messages])
