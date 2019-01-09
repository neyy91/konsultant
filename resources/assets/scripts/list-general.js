;(function($, window) {
    /**
     * Bookmarks
     */
    App.bookmarkCategoryCreate = function(response) {
        App.showMessages(response.messages);
        var selector, $this = $(this);
        if (response.type == 'popover') {
            selector = '.bookmark-categories';
        } else {
            selector = '.categories-content';
        }
        $this.find(selector).html(response.html)
        var form = $this.find('.form-categories-create:first').get(0);
        $('#manageCategories').data('reload-hide', true);
        App.defaultFields.call(form);
        App.enableForm.call(form);
    };

    App.bookmarkCategoryModifi = function(response) {
        App.showMessages(response.messages);
        $('.categories-content').html(response.html);
        $('#manageCategories').data('reload-hide', true);
        App.enableForm.call(this);
    };

    App.bookmarkQuestion = function(response) {
        var $this = $(this), popover = $this.data('bs.popover');

        if (popover && popover.$element) {
            var $element = popover.$element.attr('data-data', response.targetData);
            if (response.type == 'create') {
                $element.find('.glyphicon-star-empty').removeClass('glyphicon-star-empty').addClass('glyphicon-star');
            }
            else {
                $element.find('.glyphicon-star').removeClass('glyphicon-star').addClass('glyphicon-star-empty');
            }
        }

        // установка кол-ва закладок в категориях
        $sets = $('.bookmark-count-set');
        if ($sets.length > 0 && response.count) {
            $sets.each(function(){
                var $this = $(this), id = $this.attr('data-id');
                $this.find('.count').html(response.count[id]);
            });
        }

        App.showMessages(response.messages);
        $(this).find('.bookmark-categories').html(response.html);
    };

})(jQuery, window)