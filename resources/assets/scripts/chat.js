App.Chat = (function($, Window, Cookies, Messager, undefined) {
    var $dialogUser;
    var Chat = {
        config: {
            url: {
                view: null,
                incoming: null,
                viewed: null,
            },
            comet: {
                host: Window.location.host,
                port: Window.location.port,
                modes: 'websocket|eventsource|longpolling',
            }
        },

        comet: null,

        pageInit: function(config) {
            var id = parseInt($('#chatMessages').attr('data-user-id')),
                chatsInPage = Cookies.getJSON('chats_in_page');

            Chat.config = $.extend(Chat.config, config);

            if (chatsInPage == undefined || Array.isArray(chatsInPage) && chatsInPage.indexOf(id) == -1) {
                chatsInPage.push(id);
                Cookies.set('chats_in_page', chatsInPage);
            }
            Chat.scrollToBottom('#chatMessages', '.chat-messages-items');

            $(Window).on('unload', function() {
                var id = parseInt($('#chatMessages').attr('data-user-id')),
                    chatsInPage = Cookies.getJSON('chats_in_page'),
                    num = chatsInPage.indexOf(id);

                if (num !== -1 ) {
                    if (num == 0 && chatsInPage.length == 1) {
                        chatsInPage = [];
                    }
                    else {
                        chatsInPage.slice(num, num + 1);
                    }
                    Cookies.set('chats_in_page', chatsInPage);
                }
            });

            $('#chatPage').find('.chat-messages-items, .chat-message-text-enter').on('click', function() {
                Chat.viewedMessage('#chatMessages');
            });

            Chat.subscribe();
            Chat.setLineBreakEnter();
        },

        layoutInit: function(config) {
            var $chat = $('#chat'), $dialogUser = $('#dialogUser');
            
            Chat.config = $.extend(Chat.config, config);

            if (Cookies.get('chats_in_page') == undefined) {
                Cookies.set('chats_in_page', []);
            }

            // проверка отключение чатов на новых страницах
            Chat.checkChatUserInPage();

            setInterval(Chat.checkChatUserInPage, 3000);

            $chat.on('click', '.chat-user', function() {
                if ($(this).hasClass('chat-user-in-page')) {
                    Messager.showMessage('info', $('#dialogUser .dialog-to-chat-page').attr('data-message'));
                    return false;
                }
                Chat.showDialog($(this).attr('data-id'));
                Chat.checkChatUserInPage();
                Chat.toggleBlinkIcon(false);
                return false;
            });

            $('#chatList').on('shown.bs.collapse hidden.bs.collapse', function() {
                Chat.toggleBlinkIcon(false);
            })

            $dialogUser.find('.dialog-hide').on('click', function() {
                Chat.hideDialog();
            });

            $dialogUser.find('.dialog-to-chat-page').on('click', function() {
                var id = $('#dialogUser').attr('data-user-id'), $chatUser = Chat.getChatUser(id);
                Chat.hideDialog();
                $chatUser.addClass('chat-user-in-page disabled');
                return true;
            });

            $dialogUser.find('.dialog-messages, .chat-message-text-enter').on('click', function() {
                Chat.viewedMessage('#dialogUser');
                Chat.toggleBlinkIcon(false);
            });

            Chat.subscribe();
            Chat.setLineBreakEnter();
        },

        subscribe: function() {
            Chat.pushstream.subscribe();
        },

        pushstream: {
            subscribe: function() {
                var comet = new PushStream({
                    host: Chat.config.comet.host,
                    port: Chat.config.comet.port,
                    modes: Chat.config.comet.modes,
                    useSSL: Chat.config.comet.ssl
                });
                comet.onmessage = Chat.pushstream.onmessage;
                comet.onstatuschange = function(state) {
                    // console.log(state);
                }

                try {
                    comet.addChannel(Chat.config.channel);
                    comet.connect();
                }
                catch(error) {
                    console.error(error);
                }

                Chat.comet = comet;
            },
            onmessage: function(response) {
                // console.log(response);
                var event = response.event ? response.event.replace(/\\/g, '.') : null;
                if (event && Chat.events[event] && $.isFunction(Chat.events[event])) {
                    Chat.events[event].call(Chat, response);
                }
                else {
                    console.log('No event listener');
                }
            }
        },

        events: {
            'chat.messages' : function(response) {
                var chatsInPage = Cookies.getJSON('chats_in_page'), from = parseInt(response.data.from);
                    // чат шаблона
                if (chatsInPage.indexOf(from) == -1 && $('#dialogUser').length > 0) {
                    Chat.toggleBlinkIcon();
                    Chat.getIncomingMessage(from);
                    return;
                }
                // страница чата
                Chat.getIncomingMessagePage(from);
            }

        },

        viewedMessage: function(parent, success) {
            var $parent = $(parent), ids = [], $unviewed = null;

            if ($parent.length == 0) {
                return;
            }
            $unviewed = $parent.find('.message-unviewed');
            if ($unviewed.length == 0) {
                return;
            }

            $unviewed.each(function() {
                ids.push($(this).attr('data-id'));
            });

            if (ids.length > 0) {
                $.ajax({
                    url: Chat.config.url.viewed,
                    type: 'PUT',
                    data: {id: ids, from: $(parent).attr('data-user-id')},
                    dataType: 'json',
                    context: $parent.get(0),
                    success: function(response) {
                        if (response.vieweds && Array.isArray(response.vieweds)) {
                            var $messages = $(this).find('.message-unviewed');
                            $.each(response.vieweds, function() {
                                $messages.filter('[data-id=' + this + ']').removeClass('message-unviewed');
                            })
                        }
                        var id = parseInt(response.id);
                        Chat.setChatMessagesCount(id, 0, Chat.getAllMessagesCount() - response.vieweds.length);
                        if (success && $.isFunction(success)) {
                            success.call(this, response);
                        }
                    }
                });
            }
        },

        messageSend: function(response) {
            Chat.insertMessages('#chatMessages', '.chat-messages-items', response.messages)
            Chat.setLastMessage('#chatMessages');
            Chat.scrollToBottom('#chatMessages', '.chat-messages-items');
            App.defaultFields.apply(this);
        },

        messageSendLayout: function(response) {
            Chat.insertMessages('#dialogUser', '.dialog-messages', response.messages)
            Chat.setLastMessage('#dialogUser');
            Chat.scrollToBottom('#dialogUser .dialog-messages-wrap', '.dialog-messages');
            Chat.viewedMessage('#dialogUser');
            App.defaultFields.apply(this);
        },

        setDialogHtml: function(data) {
            var $dialogUser = $('#dialogUser');

            $dialogUser.find('.dialog-title').html(data.title);
            $dialogUser.find('.dialog-messages').html(data.messages);
            $dialogUser.find('.dialog-message-form').attr('data-ajax-url', data.url.send);
            $dialogUser.find('.dialog-to-chat-page').attr('href', data.url.chat);
            $dialogUser.find('.dialog-close').attr('data-ajax-url', data.url.delete);
            $dialogUser.attr('data-user-id', data.id);

            Chat.setLastMessage('#dialogUser');

            if (!$dialogUser.hasClass('in')) {
                Chat.setDisplayDialog(true, Chat.onShowDialog);
            }
            else {
                Chat.onShowDialog();
            }
        },

        showDialog: function(id) {
            var $chatUser = Chat.getChatUser(id), data = $chatUser.data('data'), $dialogUser = $('#dialogUser');

            if (data &&  data.id && Chat.hasUserDialog(data.id)) {
                $chatUser.toggleClass('active');
                Chat.setDisplayDialog(!$dialogUser.hasClass('in'));
                return;
            }

            $('#chat').find('.chat-user.active').removeClass('active');
            $chatUser.addClass('active');

            Chat.loadDialog(id, Chat.setDialogHtml);
        },

        onShowDialog: function() {
            Chat.scrollToBottom('#dialogUser .dialog-messages-wrap', '.dialog-messages');
            Chat.viewedMessage('#dialogUser');
        },

        hideDialog: function() {
            var $dialogUser = $('#dialogUser');

            Chat.setDisplayDialog(false);
            
            $('#chat').find('.chat-user').removeClass('active');
        },


        setDisplayDialog: function(show, success) {
            var $dialogUser = $('#dialogUser');
            if (show) {
                if ($dialogUser.is(':hidden')) {
                    $dialogUser.css('display', 'block');
                    setTimeout(function() {
                        $('#dialogUser').addClass('in');
                    }, 50);
                    setTimeout(function() {
                        if (success && $.isFunction(success)) {
                            success();
                        }
                    }, 250);
                }
                else if (success && $.isFunction(success)) {
                    success();
                }
            }
            else {
                $('#dialogUser').removeClass('in');
                setTimeout(function() {
                    $('#dialogUser').css('display', 'none');
                    if (success  && $.isFunction(success)) {
                        success();
                    }
                }, 250);
            }
        },

        removeDialogSuccess: function(response) {
            if (response.delete) {
                Chat.removeDialog($('#dialogUser').attr('data-user-id'));
            }
        },

        removeDialog: function(id) {
            var $chatUser = Chat.getChatUser(id);
            Chat.setDialogHtml({id: '', title: '', messages: '', url: {send: '', chat: '', delete: ''}});
            Chat.hideDialog();
            $chatUser.remove();
            var $chat = $('#chat');
            if ($chat.find('.chat-user').length == 0) {
                $chat.find('.chat-user-empty').removeClass('hidden');
                $('#chatList').collapse('hide');
            }
        },

        getChatUser: function(id) {
            return $('#chatUser' + id);
        },

        loadDialog: function(id, success) {
            var $chatUser = Chat.getChatUser(id), data = $chatUser.data('data');
            if (data && $.isFunction(success)) {
                success(data);
                return;
            }
            if (Chat.config.url) {
                $.get(Chat.config.url.view, {id : id}, function(response) {
                    var $chatUser = Chat.getChatUser(id);

                    $chatUser.data('data', response);

                    if (success && $.isFunction(success)) {
                        success(response);
                    }
                });
            }
        },

        startSuccess: function(response) {
            if (response.redirect) {
                document.location.href = response.redirect;
            }
            var chatsInPage = Cookies.getJSON('chats_in_page');

            if (chatsInPage.indexOf(response.id) != -1) {
                Messager.showMessage('info', $('#dialogUser .dialog-to-chat-page').attr('data-message'));
                return;
            }

            if (response.id && !Chat.checkDialog(response.id)) {
                $('#chat .chat-users').prepend(response.dialog);
                Chat.toggleChatUserEmpty();
            }
            $('#chatList').collapse('show');
            Chat.showDialog(response.id);
        },

        checkDialog: function(id) {
            return $('#chat .chat-users').find('.chat-user[data-id=' + id + ']').length > 0;
        },

        getIncomingMessage: function(id) {
            var last = null;
            if (Chat.hasUserDialog(id)) {
                 last = $('#dialogUser .chat-form-last').val();
            }
            $.get(Chat.config.url.incoming, {from: id, last: last}, function(response) {
                var id = parseInt(response.id), newDialog = false;
                if (response.dialog && !Chat.checkDialog(id)) {
                    $('#chat .chat-users').prepend(response.dialog);
                    newDialog = true;
                }
                if (response.messages) {
                    if (Chat.hasUserDialog(id)) {
                        Chat.insertMessages('#dialogUser', '.dialog-messages', response.messages)
                        Chat.scrollToBottom('#dialogUser .dialog-messages-wrap', '.dialog-messages');
                    }
                    else {
                        var $chatUser = Chat.getChatUser(response.id);
                        var data = $chatUser.data('data');
                        if (data) {
                            data.messages += response.messages;
                            $chatUser.data('data', data);
                        }
                    }
                }
                Chat.setLastMessage('#dialogUser');
                Chat.setChatMessagesCount(id, Chat.getUserChatCount(id) + (newDialog ? 0 : 1), Chat.getAllMessagesCount() + 1);
                Chat.toggleChatUserEmpty();
            });
        },

        getIncomingMessagePage: function(id) {
            var last = $('.chat-page .chat-form-last').val();
            $.get(Chat.config.url.incoming, {from: id, last: last ? last : null}, function(response) {
                if (response.messages) {
                    Chat.insertMessages('#chatMessages', '.chat-messages-items', response.messages)
                    Chat.scrollToBottom('#chatMessages', '.chat-messages-items');
                }
                Chat.setLastMessage('#chatPage');
            });
        },

        insertMessages: function(parent, items, messages) {
            var $parent = $(parent), $last = $parent.find('.chat-message-item:last');
            if ($last.length > 0) {
                $last.after(messages);
            }
            else {
                $parent.find(items).html(messages);
            }
        },

        scrollToBottom : function(parent, items) {
            var $parent = $(parent), height = $parent.height(), scrolTop = $parent.scrollTop(), $items = $parent.find(items + ':first'), heightItems = $items.height();
            $parent.get(0).scrollTop = heightItems - height;
        },

        setLastMessage: function(parent) {
            var $parent = $(parent), $last = $parent.find('.chat-message:last');
            if ($last) {
                $parent.find('.chat-form-last').val($last.attr('data-id'));
            }
        },

        hasUserDialog: function(id) {
            return parseInt($('#dialogUser').attr('data-user-id')) == id;
        },

        checkChatUserInPage: function() {
            var chatsInPage = Cookies.getJSON('chats_in_page'),
                $chatUserInPage = null,
                $chatUserItems = $('#chat .chat-user');
            if ($chatUserItems.length == 0) {
                return;
            }
            if (chatsInPage.length == 0) {
                $chatUserItems.filter('.chat-user-in-page').removeClass('disabled chat-user-in-page');
                return;
            }
            $chatUserItems.each(function() {
                var $this = $(this),
                    id = parseInt($this.attr('data-id')),
                    idx = chatsInPage.indexOf(id);
                if (idx == -1 && $this.hasClass('chat-user-in-page')) {
                    $this.removeClass('disabled chat-user-in-page');
                }
                else if (idx !== -1 && !$this.hasClass('chat-user-in-page')) {
                    $this.addClass('disabled chat-user-in-page');
                }
            })
        },

        toggleChatUserEmpty: function () {
            var $chat = $('#chat'), $empty = $chat.find('.chat-user-empty'), $chatUserItems = $chat.find('.chat-user');
            if ($chatUserItems.length == 0 && $empty.hasClass('hidden')) {
                $empty.removeClass('hidden');
            }
            else if ($chatUserItems.length > 0 && !$empty.hasClass('hidden')) {
                $empty.addClass('hidden');
            }
        },

        toggleBlinkIcon: function(state) {
            $('#chat .icon-message').toggleClass('blink text-primary', state);

        },

        setLineBreakEnter: function() {
            $('.chat-message-text-enter').on('keypress', function(event) {
                if (Chat.config.linebreak == 'ctrenter' && event.charCode == 10 && event.ctrlKey
                    || Chat.config.linebreak == 'shiftenter' && event.charCode == 13 && event.shiftKey) {
                    $(this).parents('form:first').trigger('submit');
                    return false;
                }
            })
        },

        setChatMessagesCount: function(id, count, countAll) {
            // console.log('setChatMessagesCount', id, count, countAll);
            var $chatUser = Chat.getChatUser(id);
            if ($chatUser.length > 0) {
                $chatUser.find('.chat-user-message-count').text( count == 0 ? '' : count);
            }
            var $chatMessagesCount = $('#chat .chat-messages-count');
            if (countAll == 0) {
                $chatMessagesCount.text('');
            }
            else {
                $chatMessagesCount.text(countAll);
            }
        },

        getAllMessagesCount: function() {
            var count = $('#chat .chat-messages-count').text();
            return count ? parseInt(count) : 0;
        },

        getUserChatCount: function(id) {
            var count = Chat.getChatUser(id).find('.chat-user-message-count').text();
            return count ? parseInt(count) : 0;
        }

    };

    return Chat;

})(jQuery, window, Cookies, AJAXMessage)