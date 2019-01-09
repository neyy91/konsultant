;(function($, window) {

    if (!Array.isArray) {
        Array.isArray = function(arg) {
            return Object.prototype.toString.call(arg) === '[object Array]';
        };
    }
    
    App.disableForm = function() {
        var $form = this.nodeName == 'FORM' ? $(this) : $(this).find('form:first');
        $form.addClass('form-disabled').find('input,textarea,select,button').filter(':not(:disabled):not(input:hidden)').addClass('disabled-script').prop('disabled', true).end().end().find('.disabled-set').removeClass('disabled-set').addClass('disabled disabled-unset');
    };
    App.enableForm = function() {
        var $form = this.nodeName == 'FORM' ? $(this) : $(this).find('form:first');
        $form.removeClass('form-disabled').find('.disabled-script').prop('disabled', false).removeClass('disabled-script').end().find('.disabled-unset').removeClass('disabled disabled-unset').addClass('disabled-set');
    };
    App.defaultFields = function() {
        $form = this.nodeName == 'FORM' ? $(this) : $(this).find('form:first');
        $form.find('textarea, input:not([type="hidden"])').each(function() {
            $(this).val(this.defaultValue);
        });
    };
    App.serializeToObject = function(element) {
        return $(element).serializeObject();
    };
    App.delegateEvents = function(parent) {
        $(parent).find('.toggle-tooltip').tooltip('destroy').end().find('.toggle-popover-ajax').popover('destroy');
    };
    App.scrollTo = function(element) {
        var $element = $(element);
        if ($element.length == 1) {
            var offset = $element.offset();
            $('html, body').stop().animate({
                scrollTop: offset.top
            }, 'normal');
        }
    };
    App.messageOnError = function(jqXHR, textStatus, errorThrown) {
        var error = App.messages.error;
        if (jqXHR.status == 422 && jqXHR.responseJSON) {
            var $this = $(this), errors = [];
            $this.find('.has-error').removeClass('has-error');
            for(var k in jqXHR.responseJSON) {
                $this.find('.form-field-' + k).parents('.form-group:first').addClass('has-error');
                errors.push(jqXHR.responseJSON[k]);
            }
            error = AJAXMessage.getMessagesHtml(errors);
        }
        else if (jqXHR.status == 403 && jqXHR.responseText) {
            var html = $(jqXHR.responseText).find('h1').html();
            if (html) {
                error = html;
            }
        }
        AJAXMessage.showMessage('danger', error);
    };
    App.messageOnErrorForm = function(jqXHR, textStatus, errorThrown) {
        App.messageOnError(jqXHR, textStatus, errorThrown);
        App.enableForm.apply(this);
    };


    $(function() {
        $('body').tooltip({
            container: 'body',
            selector: '.toggle-tooltip'
        });
        $(document).on('click', '.scroll-to', function() {
            App.scrollTo(this.getAttribute('href'));
            return false;
        }).on('click', '.add-more', function() {
            var $this = $(this), $element = $($this.attr('data-element')), $container = $($this.attr('href'));
            if ($container.length > 0 && $element.length > 0) {
                $container.append($element.html());
            }
            return false;
        }).on('click', '.remove-tags', function() {
            var $this = $(this), remove = $this.attr('data-remove'), parent = $this.attr('data-parent'), $tags;
            $tags = parent ? $this.parents(parent) : $this;
            if (remove) {
                $tags = $tags.find(remove);
            }
            $tags.remove();
            return false;
        }).on('click', '.toggle-disabled-fields', function() {
            var $this = $(this), href = $this.attr('href'); $elements = $(href ? href : $this.attr('data-targets'));
            if ($elements.length > 0) {
                $elements.prop('disabled', !$elements.prop('disabled'));
                var $element = $elements.first();
                if ($this.attr('data-focus') && !$element.prop('disabled')) {
                    $element.focus().get(0).select();
                }
            }
            return false;
        }).on('change', '.checked-status', function() {
            var $this = $(this);
            var $element = $this.parents('.checked-element:first'), classes = null;
            $this.parents('.checked-container:first').find('.checked-element').each(function() {
                var $this = $(this),
                    dataClass = $(this).attr('data-class'),
                    classes = dataClass ? dataClass.split('=') : null;
                $this.removeClass('input-checked')
                if (classes) {
                    $this.removeClass(classes[1]).addClass(classes[0])
                }
            });

            if ($element.attr('data-class')) {
                classes = $element.attr('data-class').split('=');
            }
            if ($this.prop('checked')) {
                $element.addClass('input-checked');
                if (classes) {
                    $element.removeClass(classes[0]).addClass(classes[1]);
                }
            } else {
                if (classes) {
                    $element.removeClass(classes[1]).addClass(classes[0]);
                }
            }
        });
    })
})(jQuery, window);