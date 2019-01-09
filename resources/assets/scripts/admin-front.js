/**
 * Admin for front javascript.
 */

;(function($, window) {
    
    $(function() {
        $('.admin-links .link, .link-admin').on('click', function() {
            $(this.getAttribute('data-target')).modal('show');
        });
        $('#modalAdmin').on('hide.bs.modal', function() {
            if (App.pageUpdating) {
                document.location.reload();
            }
        })
        $('.close-modal-admin').on('click', function() {
            if (App.pageUpdating) {
                $(this).parents('.modal:first').addClass('updating');
                document.location.reload();
            }
            else {
                $('#modalAdmin').modal('hide');
            }
        });
    });

})(jQuery, window);
