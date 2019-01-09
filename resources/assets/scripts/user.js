/**
 * Скрипт для всех страниц пользователя, юриста, компании.
 */
(function($, window) {
    // user edit
    App.emailPassword = function(response) {
        App.showMessages(response.messages);
        if (response.password_empty) {
            $(this).find('input[type=password]').val('');
        }
    };
    App.honorDelete = function(response) {
        var $this = $(this);
        App.showMessages(response.messages);
        $this.addClass('user-edit-honor-empty').find('.honor-item').css('background-image', '').end().appendTo('.user-edit-honor-items').end().find('.honor-delete').data('data-ajax-url', '');
        $('#formEditHonors').removeClass('hidden');
    };
    App.educationSave = function(response) {
        var $this = $(this);
        App.showMessages(response.messages);
        $this.find('.btn-submit .text').text(response.action_text).end().find('.btn-submit .icon').removeClass('.glyphicon-plus-sign').addClass('.glyphicon-refresh');
    };
    App.employeesModifi = function(response) {
        App.showMessages(response.messages);
        $(this).find('.employee-list').html(response.html);
    };
    
})(jQuery, window);