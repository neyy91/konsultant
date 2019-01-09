/**
 * Скрипт для старниц со списком вопросов, документов, звонков,
 */
;(function($, window) {

    /**
     * Модальное окно.
     */
    App.showAjaxModal = function(response) {
        if (response.modal) {
            $('#ajaxModalEasy').find('.modal-title').html(response.modal.title).end()
                .find('.modal-body').html(response.modal.body).end().modal('show');
        }
    };
    App.hideAjaxModal = function() {
        $('#ajaxModalEasy').modal('hide').find('.modal-body, .modal-title').html('');
    };

    /**
     * Жалоба.
     */
    App.complaintOf = function(response) {
        App.showMessages(response.messages);
        var $actionComplain = $('.action-complain');
        $($actionComplain.attr('href')).remove();
        $actionComplain.remove();
    };

    /**
     * Оценки пользователей.
     */
     App.createLikeForAnswer = function(response) {
        var $likes = $(this).parents('.info-likes:first');

        App.showMessages(response.messages);

        App.delegateEvents($likes);
        $likes.html(response.html);

        App.showAjaxModal(response);
     };

     App.likedUpdate = function(response) {
        App.showMessages(response.messages);
        App.hideAjaxModal();
     };

     /**
      * Ответы.
      */
    App.answerSetIsShow = function() {
        $('#answerSetIs').on('show.bs.modal', function(event) {
            var $target = $(event.relatedTarget);
            $(this).find('form:first').attr('data-ajax-url', $target.attr('data-set-url'));
            $target.tooltip('hide');
        });
    }
    App.beforeSetIsForAll = function() {
        App.disableForm.apply(this);
        $('#answerSetIs').modal('hide');
    };
    App.setIsForAll = function(response) {
        App.showMessages(response.messages);
    };
    App.setAnswerCost = function(response) {
        if (response.cost) {
            $(this).parents('.answer:first').find('.answer-cost').replaceWith(response.cost);
        }
    };
    // Вопрос
    App.runQuestion = function() {
        $(function() {
            App.answerSetIsShow();
        })
    };
    App.beforeSetIsForQuestion = function() {
        App.beforeSetIsForAll();
    };
    App.setIsForQuestion = function(response) {
        App.setIsForAll.apply(this, arguments);
        $question = $('#question');
        App.delegateEvents($question.get(0));
        $question.html(response.html);
    };
    // Документы
    App.runDocument = function() {
        $(function() {
            App.answerSetIsShow();
        })
    };
    App.beforeSetIsForDocument = function() {
        App.beforeSetIsForAll();
    };
    App.setIsForDocument = function(response) {
        App.setIsForAll.apply(this, arguments);
        $document = $('#document');
        App.delegateEvents($document.get(0));
        $document.html(response.html);
    };
    // Звонки
    App.runCall = function() {
        $(function() {
            App.answerSetIsShow();
        })
    }
    App.beforeSetIsForCall = function() {
        App.beforeSetIsForAll();
    };
    App.setIsForCall = function(response) {
        App.setIsForAll.apply(this, arguments);
        $call = $('#call');
        App.delegateEvents($call.get(0));
        $call.html(response.html);
    };

    App.answerUpdateFormSuccess = function(response) {
        $(this).find('.content').find('.text').hide().end().append(response.form).end().find('.update-answer').hide();
    };

    /**
     * Экспертиза.
     */
    App.questionExpertise = function(response) {
        App.showMessages(response.messages);
        if (response.expertises) {
            $(this).find('.expertises-container').html(response.expertises)
                .end().find('.question-onexpertise').remove();
        }
    };
    App.expertiseCreate = function(response) {
        App.showMessages(response.messages);
        $('.expertise-items').html(response.html);
        App.defaultFields.apply(this, null);
    };


    /*
     * События.
     */
    $(function() {

        $('.toggle-parent').each(function() {
            var $child = $('#' + this.getAttribute('data-child'));
            $('#' + this.getAttribute('data-parent')).height($child.height());
        });
        $('.child-container').hide();
        $(document).on('click', '.toggle-parent.toggle-collapse', function() {
            var $notCollapsed = $('.toggle-parent.toggle-collapse').filter(':not(.collapsed)');
            if ($notCollapsed.length > 0) {
                $notCollapsed.each(function() {
                    var $this = $(this);
                    if (!$this.hasClass('collapsed')) {
                        $(this.getAttribute('href')).collapse('hide');
                    }
                });
            }
            var parent = document.getElementById(this.getAttribute('data-parent'));
            var child = document.getElementById(this.getAttribute('data-child'));
            if (!parent || !child || (parent && child && parent.firstChild && parent.firstChild.id == child.id)) {
                return;
            }
            parent.appendChild(child);
        }).on('click', '.action-complain, .toggle-reply-answers, .toggle-clarify-answers', function() {
            var $this = $(this);
            var $child = $('#' + $this.attr('data-child'));
            var url = $this.attr('data-url');
            if (!$child.is('form')) {
                $child = $child.find('form');
            }
            $child.attr('action', url);
            if ($child.hasClass('ajax')) {
                $child.attr('data-ajax-url', url);
            }
        }).on('click', '.answer-form-remove', function() {
            var $answer = $(this).parents('.answer:first');
            $answer.find('.update-answer, .text').show();
            $($(this).attr('href')).remove();
            return false;
        });

    });
})(jQuery, window)