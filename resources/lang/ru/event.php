<?php 

return [
    'title' => 'События',
    'about' => 'Событие',
    'to_service' => [
        'question' => 'к вопросу',
        'document' => 'к заказу документа',
        'call' => 'к заказу звонка',
    ],
    'for_service' => [
        'question' => 'для вопроса',
        'document' => 'для заказа документа',
        'call' => 'для заказа звонка',
    ],
    'answer_actions' => [
        'answer' => 'ответил',
        'update_answer' => 'обновил свой ответ',
    ],
    'is_actions' => [
        'question' => 'выбрал ответ',
        'document' => 'выбрал исполнителя',
        'call' => 'выбрал подходящего',
    ],
    'types' => [
        'tips_advice_lawyers' => [
            'about' => 'Советы и рекомендации юристов по актуальным темам',
        ],
        'news' => [
            'about' => 'Новости об акциях и скидках',
        ],
        'chat_messages' => [
            'about' => 'Сообщение в чате',
        ],
        'new_answers' => [
            'about' => 'Новые ответы на вопросы',
            'message' => ':status <a href=":lawyer_url">:user</a> <a href=":url">:action</a> на Ваш вопрос &laquo;:title&raquo;'
        ],
        'consultations_city' => [
            'about' => 'Консультация из Вашего города',
        ],
        'consultations_specialization' => [
            'about' => 'Консультация по Вашей специализации',
        ],
        'vote_post_comments' => [
            'about' => 'Оценка Ваших постов и комментариев',
        ],
        'change_balance' => [
            'about' => 'Изменения в Вашем балансе',
        ],
        'clarify_add' => [
            'about' => 'Клиент добавил уточнение :to_service',
            'message' => 'Пользователь :user <a href=":url">добавил уточнение</a> :to_service &laquo;:title&raquo;'
        ],
        'personal_consultations' => [
            'about' => 'Персональная консультация',
        ],
        'vote_answers' => [
            'about' => 'Оценка Ваших ответов',
        ],
        'complaint' => [
            'about' => 'Жалоба на Ваш ответ',
        ],
        'messages_admin' => [
            'about' => 'Сообщение от администрации',
        ],
        'telephone_consultation' => [
            'about' => 'Заявка на телефонную консультацию',
        ],
        'user_registration' => [
            'about' => 'Регистрация пользователя',
        ],
        'expertise' => [
            'about' => 'Экспертиза вопроса',
            'message' => '<a href=":lawyer_url">:user</a> отправил на экспертизу вопрос <a href=":url">&laquo;:title&raquo;</a>',
        ],
        'answer_is' => [
            'about' => 'Ответ выбран',
            'message' => 'Пользователь :user <a href=":url">:action</a> :for_service &laquo;:title&raquo;',
        ],
    ]
];