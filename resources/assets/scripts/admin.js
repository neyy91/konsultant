/**
 * Admin javascript.
 */

;(function($, window, document) {
    App.iframeUpdateHeight = function(iframe) {
        window.parent.jQuery(iframe).height($(document).height());
    }
    // document ready
    $(function() {
        //
    });
    // window load
    $(window).on('load', function() {
        if (window.parent) {
            var $parent = window.parent.jQuery;
            $parent('#modalAdminLabel').html(document.title);
            App.iframeUpdateHeight('#iframeAdmin');
            // add updating page for collapse
            $('.collapse').on('shown.bs.collapse hidden.bs.collapse', function() {
                App.iframeUpdateHeight('#iframeAdmin');
            })

        }
    });
})(jQuery, window, document);
