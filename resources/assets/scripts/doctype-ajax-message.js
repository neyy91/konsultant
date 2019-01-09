var DoctypeAJAXMessage = (function($) {
    var DoctypeAJAXMessage = {
        messagesSelector: '.messages-page',
        messageSelector: '.alert',
        showClass: 'show',
        fixedSelector: '.messages-fixed',
        timeout: 3000,
        animation: 300,
        template: '<div class="alert alert-{type}"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>{message}</div>',
        beforeMessagesRun: false,
        afterMessagesRun: false,
        maxMessages: 3,
        init: function() {
            DoctypeAJAX.roots.messages = {
                ajaxSuccess: function(response, event, xhr, settings) {
                    if ('messages' in response && response.messages) {
                        var messages = [], message = '', messagesText = '';
                        var $messages = $(DoctypeAJAXMessage.messagesSelector);
                        for(type in response.messages) {
                            DoctypeAJAXMessage.showMessage(type, DoctypeAJAXMessage.getMessagesHtml(response.messages[type]));
                        }
                    }
                }
            }
        },
        // создания HTML сообщения
        getMessagesHtml: function(messages) {
            var messageHtml = '<ul>';
            var allMessages = $.isArray(messages) ? messages : [messages];
            for (var i = 0; i < allMessages.length; i++) {
                messageHtml += '<li>' + allMessages[i] + '</li>';
            }
            messageHtml += '</ul>';
            return messageHtml;
        },
        // показать сообщение
        showMessage: function(type, message) {
            if (!this.beforeMessagesRun) {
                this.beforeMessages();
            }
            
            this.maxMessagesSet();
            $(this.messagesSelector).append(this.template.replace('{type}', type).replace('{message}', message));

            if (!this.afterMessagesRun) {
                this.afterMessages();
            }
        },
        // Ограничение сообщений
        maxMessagesSet: function() {
            var $messageItems = $(this.messagesSelector).find(DoctypeAJAXMessage.messageSelector);
            if ($messageItems.length >= DoctypeAJAXMessage.maxMessages) {
                $messageItems.filter(':first').remove();
            }
        },
        // Перед отправкой
        beforeMessages : function() {
            if (this.beforeMessagesRun) {
                return;
            }
            $(this.fixedSelector).addClass(this.showClass);
            this.afterMessagesRun = false;
            this.beforeMessagesRun = true;
        },
        // После отправки
        afterMessages: function() {
            if (this.afterMessagesRun) {
                return;
            }
            var timeoutId = setInterval(this.intervalFunction, this.timeout);
            $(this.messagesSelector).data('messagesTimeoutId', timeoutId);
            this.afterMessagesRun = true;
        },
        // функция для setInterval
        intervalFunction : function() {
            $(DoctypeAJAXMessage.messagesSelector).find(DoctypeAJAXMessage.messageSelector + ':first').slideUp(DoctypeAJAXMessage.animation, DoctypeAJAXMessage.afterSlideUp);
        },
        // После slideUp
        afterSlideUp: function() {
            $(this).remove();
            var $messages = $(DoctypeAJAXMessage.messagesSelector);
            if ($messages.find(DoctypeAJAXMessage.messageSelector).length == 0) {
                clearInterval($messages.data('messagesTimeoutId'));
                $messages.html('');
                $(DoctypeAJAXMessage.fixedSelector).removeClass(DoctypeAJAXMessage.showClass);
                DoctypeAJAXMessage.beforeMessagesRun = DoctypeAJAXMessage.afterMessagesRun = false;
            }
        }
    };

    return DoctypeAJAXMessage;
})(jQuery)