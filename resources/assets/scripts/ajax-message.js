var AJAXMessage = (function($) {
    var AJAXMessage = {
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
        // создания HTML сообщения
        getMessagesHtml: function(messages) {
            if (!$.isArray(messages)) {
                return messages;
            }
            if (messages.length == 1) {
                return messages[0];
            }
            var messageHtml = '<ul>';
            for (var i = 0; i < messages.length; i++) {
                messageHtml += '<li>' + messages[i] + '</li>';
            }
            messageHtml += '</ul>';
            return messageHtml;
        },
        // показать сообщение
        showMessage: function(type, messages, timeout) {
            var $messages = $(this.messagesSelector);
            if (timeout) {
                $messages.data('timeout', timeout);
            }
            if (!this.beforeMessagesRun) {
                this.beforeMessages();
            }
            
            this.maxMessagesSet();
            $messages.append(this.template.replace('{type}', type).replace('{message}', this.getMessagesHtml(messages)));

            if (!this.afterMessagesRun) {
                this.afterMessages();
            }
        },
        // Ограничение сообщений
        maxMessagesSet: function() {
            var $messageItems = $(this.messagesSelector).find(AJAXMessage.messageSelector);
            if ($messageItems.length >= AJAXMessage.maxMessages) {
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
            var $messages = $(this.messagesSelector), timeout = $messages.data('timeout');
            var timeoutId = setInterval(this.intervalFunction, timeout ? timeout : this.timeout);
            $messages.data('messagesTimeoutId', timeoutId);
            this.afterMessagesRun = true;
        },
        // функция для setInterval
        intervalFunction : function() {
            $(AJAXMessage.messagesSelector).find(AJAXMessage.messageSelector + ':first').slideUp(AJAXMessage.animation, AJAXMessage.afterSlideUp);
        },
        // После slideUp
        afterSlideUp: function() {
            $(this).remove();
            var $messages = $(AJAXMessage.messagesSelector);
            if ($messages.find(AJAXMessage.messageSelector).length == 0) {
                clearInterval($messages.data('messagesTimeoutId'));
                $messages.html('');
                $(AJAXMessage.fixedSelector).removeClass(AJAXMessage.showClass);
                AJAXMessage.beforeMessagesRun = AJAXMessage.afterMessagesRun = false;
            }
        }
    };

    return AJAXMessage;
})(jQuery)