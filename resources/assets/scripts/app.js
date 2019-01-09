;(function($, window){
    // AJAX setting
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN' :  $('meta[name="csrf-token"]').attr('content')
        },
    });
    App.jsContainer = '#jsContainer';
    App.templates = {
    'ajaxpopover' : '<div class="popover ajax-popover" role="tooltip"><div class="arrow"></div><button type="button" class="close">&times;</button><h3 class="popover-title"></h3><div class="popover-content bookmark-popover-content"></div></div>',
        'typeaheadpopover' : '<div class="popover typeahead" role="tooltip"><div class="arrow"></div><h3 class="popover-title"></h3><div class="popover-content"></div></div>'
    };
    App.formSuccess = function(response) {
        App.showMessages(response.messages);
        $(this).find('.has-error').removeClass('has-error');
    }
    App.formSuccessDefault = function(response) {
        App.showMessages(response.messages);
        $(this).find('.has-error').removeClass('has-error');
        App.defaultFields.apply(this);
    }
    App.iframeRegistry = {};
    App.createIFrame = function(name, src, debug) {
        src = src || 'javascript:false'; // пустой src
        var tmpElem = document.createElement('div');
        tmpElem.innerHTML = '<iframe name="' + name + '" id="' + name + '" src="' + src + '">';
        var iframe = tmpElem.firstChild;
        if (!debug) {
            iframe.style.display = 'none';
        }
        document.body.appendChild(iframe);
        return iframe;
    };
    App.iframeForm = function(form) {
        var iframeName = 'iframe' + Math.random();
        var $iframe = $(App.createIFrame(iframeName));
        var $form = $(form)
        $form.attr('target', iframeName);
        App.iframeRegistry[iframeName] = true;
        $iframe.on('load', function() {
            var iframe = this;
            setTimeout(function() {
                window.top.window.$('#' + iframe.id).remove();
            }, 1000);
            if (App.iframeRegistry[iframeName]) {
                var $error = $(iframe.contentWindow.document.body).find('.page-messages ul');
                if ($error.length > 0) {
                    var error = $error.get(0).outerHtml;
                    AJAXMessage.showMessage('danger', error ? error : App.messages.error);
                    $form = $('form[target="' + iframe.id + '"]').trigger('iframe.error');
                    App.enableForm.apply($form.get(0));
                }
            }
        });
    };
    App.showMessages = function(messages) {
        for(type in messages) {
            AJAXMessage.showMessage(type, messages[type]);
        }
    };
    App.showMessagesOnSuccess = function(response) {
        App.showMessages(response.messages);
    };
    App.redirect = function(response) {
        if (response.redirect) {
            window.location.href = response.redirect;
        }
    };

    App.AJAXLoadSuccess = function(response) {
        var $body = $('body');
        if (response.chat && App.config.chat.show) {
            $body.append(response.chat);
            App.Chat.layoutInit(App.config.chat);
        }
        if (response.login) {
            $body.append(response.login);
        }
    };

    // document ready
    $(document).ready(function(){
        // DataAJAX
        if ('DataAJAX' in window) {
            DataAJAX.init();
        }
        // ifrmae form
        $(document).on('submit', '.form-iframe', function() {
            App.iframeForm(this);
            var form = this;
            // $(this).find('button').prop('disabled', true);
            setTimeout(function () {
                App.disableForm.apply(form);
            }, 200);
            return true;
        }).on('form.submited', '.form-iframe', function() {
            $(this).find('button').prop('disabled', false).end().parents('.collapse.in:first').collapse('hide');
            App.enableForm.apply(this);
            this.reset();
        }).on('iframe.error', '.form-iframe', function() {
            $(this).find('button').prop('disabled', false);
        }).on('change', '.form-submit-onchange', function() {
            $(this).parents('form:first').trigger('submit');
        }).on('change', '.change-check-all', function(value) {
            var $this = $(this), $fields = $($this.attr('data-fields'));
            if ($fields.length > 0) {
                $fields.prop('checked', this.checked);
            }
        }).on('click', '.popover .close', function() {
            var $element = $(this).parents('.popover:first').data('bs.popover').$element, blur = $element.attr('data-popover-blur');
            $element.popover(blur && blur != 'nothing' ? blur : 'destroy');
            return false;
        }).on('click', '.noop', function(argument) {
            return false;
        }).on('click', '.visible-control', function() {
            var $this = $(this), parent = $this.attr('data-parent'), $parent, $show, $hide;
            if (parent) {
                $parent = $this.parents(parent);
            } else {
                $parent = $this;
            }
            $show = $parent.find($this.attr('data-show'));
            $hide = $parent.find($this.attr('data-hide'));
            if ($hide.length > 0 ) {
                $hide.hide();
            }
            if ($show.length > 0) {
                $show.show();
            }
            return $this.attr('data-prevent-default') ? false : true;
        }).on('click', '.disable-control', function() {
            var $this = $(this), $disable = $($this.attr('data-disable')), $enable = $($this.attr('data-enable'));
            $disable.prop('disabled', true);
            $enable.prop('disabled', false);
            return $this.attr('data-prevent-default') ? false : true;
        }).on('change', '.disable-checked', function() {
            var $this = $(this),
                $target = $($this.data('disableTarget')),
                inverse = $this.data('disableInverse');
            if ($target.length > 0) {
                var disable = inverse ? !this.checked : this.checked;
                $target.prop('disabled', disable);
                if (disable && this.hasAttribute('value')) {
                    $target.data('prevValue', $target.val());
                    $target.val(this.getAttribute('value'));
                } else if (!disable && $target.data('prevValue')) {
                    $target.val($target.data('prevValue'));
                }
            }
        });

        $('.change-check-all').each(function() {
            var $this = $(this), $fields = $($this.attr('data-fields'));
            if ($fields.length > 0 && $fields.length == $fields.filter(':checked').length) {
                $this.prop('checked', true);
            }
        });

        /*
         * Popover.
         */
        var popoverAjax = function() {
            var $this = $(this), url = $this.attr('data-url'), data = $this.attr('data-data'), popover = $this.data('bs.popover'), blur = $this.attr('data-popover-blur'), active = $this.attr('data-popover-active'), method = $this.attr('data-method');
            blur = blur ? blur : 'destroy';
            active = active ? active : 'destroy';
            // destroy others popovers
            $('.popover').each(function() {
                var blur = $this.attr('data-popover-blur');
                if (blur != 'nothing') {
                    $(this).data('bs.popover').$element.popover(blur ? blur : 'destroy');
                }
            })
            if(popover) {
                if (active != 'nothing') {
                    popover.$element.popover(active);
                }
            }
            else {
                $this.tooltip({container: App.jsContainer, title: App.messages.loaded}).tooltip('show');
                $.ajax({
                    url: url,
                    type: method ? method : 'POST',
                    data: $.parseJSON(data),
                    context: this,
                    success: function(responseText, status) {
                        if (status == 'success' && responseText) {
                            var $this = $(this);
                            $this.tooltip('destroy').popover({
                                template: App.templates.ajaxpopover,
                                container : App.jsContainer,
                                // title: $this.attr('data-title'),
                                content: responseText,
                                toggle: 'popover',
                                trigger: 'manual',
                                html: true,
                            }).popover('show');
                        }
                    }
                });
            }
            return false;
        };
        var popoverAjaxBlur = function () {
            var $this = $(this), popover = $this.data('bs.popover'), blur = $this.attr('data-popover-blur');
            if (popover && popover.$element) {
                if (blur != 'nothing') {
                    popover.$element.popover(blur ? blur : 'destroy');
                }
            }
        }

        /**
         * Toggle ajax.
         */
        $(document).on('click', '.toggle-popover-ajax.click', popoverAjax)
            .on('mouseenter', '.toggle-popover-ajax.hover', popoverAjax).on('mouseleave', '.toggle-popover-ajax.hover', popoverAjaxBlur)
            .on('show.bs.popover', '.toggle-popover-ajax', function () {
                var $this = $(this);
                $this.data('bs.popover').tip().addClass($this.attr('data-popover-class'))
            });

        /**
         * Toggle popover typehead.
         */
        $('.toggle-popover-typeahead').popover({container: 'body', trigger: 'click', 'template' : App.templates.typeaheadpopover, 'trigger' : 'manual', html: true}).keypress(function() {
            var $this = $(this), $form = $this.parents('form:first'), data = {};
            if ($this.val().length > 1 && !$this.data('loading')) {
                $this.data('loading', true);
                $this.popover('show');
                data[this.name] = $this.val();
                $.ajax({
                    url: $form.attr('action'),
                    type: $form.attr('method'),
                    data: data,
                    context: this,
                    success: function(responseText, status) {
                        if (status == 'success' && responseText) {
                            var $this = $(this), $popover = $this.data('bs.popover').tip().find('.popover-content');
                            // $popover.html(responseText);
                            $this.attr('data-content', responseText).data('bs.popover').setContent();
                            $this.data('loaded', true);
                        }
                    },
                    complete: function() {
                        $this.data('loading', false);
                    }
                });
            }
            else {
                $this.attr('data-content', ' ').data('bs.popover').setContent();
            }
        }).on('focus', function(argument) {
            if ($(this).data('loaded')) {
                $(this).popover('show');
            }
        }).on('blur', function() {
            $(this).popover('hide');
        });

        $('#ajaxModalEasy').modal({
            'show' : false,
        });

        /**
         * Load Ajax
         */
        $.post(App.config.ajax.url, {route: App.config.route}, App.config.ajax.success());

    });
})(jQuery, window)