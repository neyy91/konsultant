<?php 

namespace App\Libs;

/**
 * Определение статусов.
 */
interface EventTypeDefine {

    /**
     * Типы.
     * @var integer
     */
    const TYPE_TIPS_ADVICE_LAWYERS = 'tal'; // Советы и рекомендации юристов по актуальным темам
    const TYPE_NEWS = 'n'; // Новости об акциях и скидках
    const TYPE_CHAT_MESSAGES = 'cm'; // Сообщения в чате
    const TYPE_NEW_ANSWERS = 'na'; // Новые ответы на вопросы
    const TYPE_CONSULTATIONS_CITY = 'cc'; // Консультация из моего города
    const TYPE_CONSULTATIONS_SPECIALIZATION = 'cs'; // Консультация по моей специализации
    const TYPE_VOTE_POST_COMMENTS = 'vpc'; // Оценка моих постов и комментариев
    const TYPE_PAY_SUCCESS = 'ps'; // Успешная оплата
    const TYPE_WITHDRAWAl = 'wd'; // Успешный вывод денег
    const TYPE_CLARIFY_ADD = 'ca'; // Добавление уточнений
    const TYPE_PERSONAL_CONSULTATIONS = 'pc'; // Персональная консультация
    const TYPE_VOTE_ANSWERS = 'va'; // Оценка ответа
    const TYPE_COMPLAINT = 'c'; // Жалоба на меня
    const TYPE_MESSAGES_ADMIN = 'ma'; // Сообщения от администрации
    const TYPE_TELEPHONE_CONSULTATION = 'tc'; // Телефонная консультация
    const TYPE_EXPERTISE = 'e'; // Экспертиза
    const TYPE_ANSWER_IS = 'ai'; // Ответ выбран
    const TYPE_USER_REGISTRATION = 'ur'; // Зарегистрирован пользователь
    const TYPE_USER_COMPLAINT = 'uc'; // Жалоба на меня
}