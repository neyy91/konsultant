{{-- 
    Содержание iframe при при добавлении награды.
 --}}

<script>
    var App = window.parent.App;
    delete App.iframeRegistry[window.name];
    var $top = window.parent.$;
    document.addEventListener('DOMContentLoaded', function() {
        $top('.user-edit-education-file-alert').removeClass('hidden');
        $top('.control-label-file .btn-text').html('@lang('user.replace_image_diploma')');
        @if ($file)
            $top('.user-edit-education-file-link').attr('href', '{{ route('file', ['file' => $file, 'name' => $file->basename]) }}')
        @endif
        $top('#formEditEducationFile').trigger('form.submited');
   });
</script>

@include('common.messages_iframe', ['messages' => $messages])
