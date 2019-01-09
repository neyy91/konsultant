<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/


Route::get('/', 'HomeController@index')->name('home');
Route::get('/test', 'HomeController@test')->name('test');
Route::get('/test-stream', 'SiteController@testStream')->name('test.stream');
Route::get('/test-stream-page', 'SiteController@testStreamPage')->name('test.stream.page');


Route::post('/ajax/layout', 'AjaxController@getLayout')->name('ajax.layout');


/**
 * Категории права.
 */
Route::get('/cat/{id}', 'CategoryLawController@redirectToCategoryLaw')->name('category');
Route::group(['prefix' => 'categories'], function () {
    // Список
    Route::get('/', 'CategoryLawController@categories')->name('categories');
    // Просмотр
    Route::get('/{category}', 'CategoryLawController@view')->name('category.view');
});

/**
 * Города.
 */
Route::get('/city/{id}', 'CityController@redirectToCity')->name('city');
Route::group(['prefix' => 'cities'], function () {
    // Список
    Route::get('/', 'CityController@cities')->name('cities');
    // Просмотр
    Route::get('/{city}', 'CityController@view')->name('city.view');
});
Route::post('/ajax/city', 'CityController@lawyersCityAjax')->name('lawyers.city.ajax');

/**
 * Типы документов
 */
Route::get('/dot/{id}', 'DocumentTypeController@redirectToDoocumentType')->name('document_type');
Route::group(['prefix' => 'document-types'], function () {
    // Список
    Route::get('/', 'DocumentTypeController@documentTypes')->name('document_types');
    // Просмотр
    Route::get('/{type}', 'DocumentTypeController@view')->name('document_type.view');
});


/**
 * Темы.
 */
Route::get('/th/{id}', 'ThemeController@redirectToTheme')->name('theme');
Route::get('/themes', 'ThemeController@themes')->name('themes');
Route::group(['prefix' => 'themes'], function () {
    // Просмотр
    Route::get('/{theme}', 'ThemeController@view')->name('theme.view');
});


/**
 * Файлы.
 */
Route::get('/f/{file}/{name?}', 'FileController@download')->name('file');


/**
 * Вопросы.
 */
Route::get('q/{id}', 'QuestionController@redirectToQuestion')->name('question');
Route::get('/questions-user/{user}', 'QuestionController@questionsUser')->name('questions.user');
Route::group(['prefix' => 'questions'], function () {
    // Список
    Route::get('/', 'QuestionController@questions')->name('questions');
    Route::get('/cat-{category}', 'QuestionController@questionsCategory')->name('questions.category');
    Route::get('/theme-{theme}', 'QuestionController@questionsTheme')->name('questions.theme');
    Route::get('/city-{city}', 'QuestionController@questionsCity')->name('questions.city');
    // Создание
    Route::get('/ask', 'QuestionController@createForm')->name('question.create.form');
    Route::post('/', 'QuestionController@create')->name('question.create');
    // Экспертиза
    Route::put('/{id}/expertise', 'QuestionController@expertise')->name('question.expertise');
    Route::put('/{id}/expertise-close', 'QuestionController@expertiseClose')->name('question.expertise.close');
    // Ссылка на ответ.
    Route::get('/q-{question}#answer{answer}', 'QuestionController@view')->name('question.answer');
    // Уточнение
    Route::get('/q-{question}#questionClarify{clarify}', 'QuestionController@view')->name('question.clarify');
    // Просмотр
    Route::get('/q-{question}', 'QuestionController@view')->name('question.view');
});


/**
 * Документы.
 */
Route::get('d/{id}', 'DocumentController@redirectToDocument')->name('document');
Route::get('/documents-user/{user}', 'DocumentController@documentsUser')->name('documents.user');
Route::group(['prefix' => 'documents'], function () {
    // Список
    Route::get('/', 'DocumentController@documents')->name('documents');
    Route::get('/type-{type}', 'DocumentController@documentsDocumentType')->name('documents.document_type');
    Route::get('/city-{city}', 'DocumentController@documentsCity')->name('documents.city');
    // Создание
    Route::get('/order', 'DocumentController@createForm')->name('document.create.form');
    Route::post('/', 'DocumentController@create')->name('document.create');
    // Ссылка на ответ.
    Route::get('/d-{document}#answer{answer}', 'DocumentController@view')->name('document.answer');
    // Уточнение
    Route::get('/d-{document}#documentClarify{clarify}', 'DocumentController@view')->name('document.clarify');
    // Просмотр
    Route::get('/d-{document}', 'DocumentController@view')->name('document.view');
});


/**
 * Звонки.
 */
Route::get('c/{id}', 'CallController@redirectToCall')->name('call');
Route::get('/calls-user/{user}', 'CallController@callsUser')->name('calls.user');
Route::group(['prefix' => 'calls'], function () {
    // Список
    Route::get('/', 'CallController@calls')->name('calls');
    Route::get('/city-{city}', 'CallController@callsCity')->name('calls.city');
    // Создание
    Route::get('/consultation', 'CallController@createForm')->name('call.create.form');
    Route::post('/', 'CallController@create')->name('call.create');
    // Ссылка на запрос.
    Route::get('/c-{call}#answer{answer}', 'CallController@view')->name('call.answer');
    // Уточнение
    Route::get('/c-{call}#callClarify{clarify}', 'CallController@view')->name('call.clarify');
    // Просмотр
    Route::get('/c-{call}', 'CallController@view')->name('call.view');
});


/**
 * Ответы.
 */
Route::get('/a/{id}', 'AnswerController@redirectToAnswer')->name('answer');
Route::get('/answer/{id}', 'AnswerController@redirectToAnswer')->name('answer.view');
Route::group(['prefix' => 'answer', 'as' => 'answer.'], function() {
    // Создание ответа на вопрос
    Route::post('/{id}/question', 'AnswerController@createForQuestion')->name('create.question');
    // Подходящий ответ на вопрос
    Route::put('/{id}/question/is', 'AnswerController@setIsForQuestion')->name('is.question');

    // Создание ответа(предложения) документ
    Route::post('/{id}/document', 'AnswerController@createForDocument')->name('create.document');
    // Подходящий ответ документ
    Route::put('/{id}/document/is', 'AnswerController@setIsForDocument')->name('is.document');

    // Создание ответа(заявки) на звонок
    Route::post('/{id}/call', 'AnswerController@createForCall')->name('create.call');
    // Подходящий ответ документ
    Route::put('/{id}/call/is', 'AnswerController@setIsForCall')->name('is.call');

    // Создание ответа на другой ответ
    Route::post('/{id}/answer', 'AnswerController@createForAnswer')->name('create.answer');

    // Обновление ответа.
    Route::get('/{id}/update', 'AnswerController@updateForm')->name('update.form');
    Route::put('/{id}/update', 'AnswerController@update')->name('update');

});


/**
 * Уточнения.
 */
Route::get('/clf/{id}', 'ClarifyController@redirectToClarify')->name('clarify');
Route::group(['prefix' => 'clarify'], function() {
    // Создание уточнения для вопроса
    Route::post('/{id}/question', 'ClarifyController@createForQuestion')->name('clarify.create.question');

    // Создание уточнения для заказа документа
    Route::post('/{id}/document', 'ClarifyController@createForDocument')->name('clarify.create.document');

    // Создание уточнения для звонка
    Route::post('/{id}/call', 'ClarifyController@createForCall')->name('clarify.create.call');

    // Создание уточнения для ответа
    Route::post('/{id}/answer', 'ClarifyController@createForAnswer')->name('clarify.create.answer');
});


/**
 * Жалобы.
 */
Route::group(['prefix' => 'complain'], function() {
    // Вопрос
    Route::post('/question/{id}', 'ComplaintController@complainOfQuestion')->name('complain.question');

    // Документа
    Route::post('/document/{id}', 'ComplaintController@complainOfDocument')->name('complain.document');

    // Документа
    Route::post('/answer/{id}', 'ComplaintController@complainOfAnswer')->name('complain.answer');

    // Звонок
    Route::post('/call/{id}', 'ComplaintController@complainOfCall')->name('complain.call');
});


/**
 * Оценки. Likes.
 */
Route::group(['prefix' => 'likes'], function() {
    // Список оценок ответа
    Route::get('/answer/{answer}', 'LikeController@answerLikes')->name('likes.answer');
    // Оценка ответа
    Route::put('/{like}', 'LikeController@likedUpdate')->name('like.update');
    Route::post('/answer/{answer}', 'LikeController@createLikeForAnswer')->name('like.answer');
});

/**
 * Пользователи.
 */
// Юристы
Route::group(['prefix' => 'lawyers'], function() {
    Route::group(['as' => 'lawyers'], function() {
        Route::get('/cat-{category}+city-{city}', 'LawyerController@lawyers')->name('.category_city');
        Route::get('/cat-{category}', 'LawyerController@lawyers')->name('.category');
        Route::get('/city-{city}', 'LawyerController@lawyers')->name('.city');
        Route::post('/thanking', 'LawyerController@thanking')->name('.thanking');
        Route::get('/pay/{pay}', 'LawyerController@pay')->name('.pay');
        Route::get('/', 'LawyerController@lawyers')->name('');
    });
    Route::group(['as' => 'lawyer'], function() {
        Route::get('/{lawyer}/counsultations', 'LawyerController@questions')->name('.questions');
        Route::get('/{lawyer}/feedbacks', 'LawyerController@liked')->name('.liked');
        Route::get('/{lawyer}', 'LawyerController@profile')->name('.view');
        Route::get('/{lawyer}', 'LawyerController@profile')->name('');
    });
});
// Профиль юриста

// Пользователи
Route::get('/users/{user}', 'UserController@view')->name('user');
Route::group(['as' => 'user.', 'prefix' => 'user'], function() {
    // Личный кабинет
    Route::get('/', 'UserController@dashboard')->name('dashboard');
    // Закладки
    Route::get('/bookmarks/{category?}', 'UserController@bookmarks')->name('bookmarks');
    // Вопросы
    Route::get('/questions', 'UserController@questions')->name('questions');
    // Консультации по телефону
    Route::get('/calls', 'UserController@calls')->name('calls');
    // Документы
    Route::get('/documents', 'UserController@documents')->name('documents');
    // Чат
    Route::group(['prefix' => 'dialogs'], function() {
        Route::get('/', 'ChatController@chats')->name('chats');
        Route::group(['as' => 'chat'], function() {
            Route::put('/viewed', 'ChatController@setViewedMessages')->name('.viewed');
            Route::get('/view-layout', 'ChatController@viewForLayout')->name('.view.layout');
            Route::get('/incoming-layout', 'ChatController@incomingForLayout')->name('.incoming.layout');

            Route::post('/{user}', 'ChatController@start')->name('');
            Route::delete('/{user}', 'ChatController@delete')->name('.delete');
            Route::get('/{user}/view', 'ChatController@view')->name('.view');
            Route::post('/{user}/message', 'ChatController@messageSend')->name('.message.send');
            Route::delete('/{user}/all', 'ChatController@deleteAllChats')->name('.delete.all');
            
            Route::post('/{user}/message-layout', 'ChatController@messageSendForLayout')->name('.message.send.layout');
        });
    });

    // Пользовательское соглашение
    // Route::get('/terms', 'UserController@terms')->name('terms');
    // Редактирование
    Route::get('/edit', 'UserEditController@index')->name('edit');
    Route::group(['as' => 'edit.', 'prefix' => 'edit'], function () {
        // Основное
        Route::get('/personal', 'UserEditController@personalForm')->name('personal.form');
        Route::put('/personal', 'UserEditController@personal')->name('personal');
        // Емайл и пароль
        Route::get('/email-password', 'UserEditController@emailPasswordForm')->name('email_password.form');
        Route::put('/email-password', 'UserEditController@emailPassword')->name('email_password');
        Route::get('/email-change/{token}', 'UserEditController@emailChange')->name('email_change');
        // Фото
        Route::get('/photo', 'UserEditController@photoForm')->name('photo.form');
        Route::put('/photo', 'UserEditController@photo')->name('photo');
        // Чат
        Route::get('/chat', 'UserEditController@chatForm')->name('chat.form');
        Route::put('/chat', 'UserEditController@chat')->name('chat');
        // Фото
        Route::get('/photo', 'UserEditController@photoForm')->name('photo.form');
        Route::put('/photo', 'UserEditController@photo')->name('photo');
        // Контакты
        Route::get('/contacts', 'UserEditController@contactsForm')->name('contacts.form');
        Route::put('/contacts', 'UserEditController@contacts')->name('contacts');
        // Образование
        Route::get('/education', 'UserEditController@educationForm')->name('education.form');
        Route::put('/education', 'UserEditController@education')->name('education');
        Route::post('/education/file', 'UserEditController@educationFile')->name('education.file');
        // Опыт
        Route::get('/experience', 'UserEditController@experienceForm')->name('experience.form');
        Route::put('/experience', 'UserEditController@experience')->name('experience');
        // Дполнительно
        Route::get('/advanced', 'UserEditController@advancedForm')->name('advanced.form');
        Route::put('/advanced', 'UserEditController@advanced')->name('advanced');
        // Награды
        Route::get('/honors', 'UserEditController@honorsForm')->name('honors.form');
        Route::post('/honors', 'UserEditController@honors')->name('honors');
        Route::delete('/honor/{honor}', 'UserEditController@honorDelete')->name('honor.delete');
        // Оповещения
        Route::get('/notifications', 'UserEditController@notificationsForm')->name('notifications.form');
        Route::put('/notifications', 'UserEditController@notifications')->name('notifications');
        // Сотрудники
        Route::get('/employees', 'UserEditController@employeesForm')->name('employees.form');
        Route::post('/employees', 'UserEditController@employeesAdd')->name('employees.add');
        Route::delete('/employees/{employee}', 'UserEditController@employeesDelete')->name('employees.delete');
    });
});

/**
 * Закладки.
 */
Route::group(['as' => 'bookmark'], function() {
    Route::post('/bookmarks/categories', 'BookmarkController@categoriesPopover')->name('.ajax.categories.popover');
    Route::post('/bookmarks/category', 'BookmarkController@categoryCreate')->name('.category.create');
    Route::put('/bookmarks/category/{category}', 'BookmarkController@categoryUpdate')->name('.category.update');
    Route::delete('/bookmarks/category/{category}', 'BookmarkController@categoryDelete')->name('.category.delete');
    Route::delete('/bookmarks/{bookmark}', 'BookmarkController@delete')->name('.delete');
    Route::post('/bookmarks', 'BookmarkController@create')->name('.create');
});

/**
 * Компании, партнеры.
 */
Route::get('/partners', 'CompanyController@companies')->name('companies');
Route::get('/partners/{company}', 'CompanyController@view')->name('company');
Route::get('/partners/{company}/update', 'CompanyController@updateForm')->name('company.update.form');
Route::put('/partners/{company}', 'CompanyController@update')->name('company.update');

/**
 * Экспертиза.
 */
Route::get('/exp', 'ExpertiseController@redirectToExpertise')->name('expertises');
Route::group(['as' => 'expertise', 'prefix' => 'expertises'], function() {
    // Экспертиза вопроса
    Route::post('/q-{id}', 'ExpertiseController@createMessage')->name('.create'); // {id} - id вопроса
});

/**
 * Yandex.
 */
Route::group(['as' => 'yandex'], function() {
    Route::group(['as' => '.kassa', 'prefix' => 'kassa'], function() {
        Route::get('/failTest', 'YandexKassaAppController@fail')->name('.pay.fail.test');
        Route::get('/fail', 'YandexKassaAppController@fail')->name('.pay.fail');
        /* Тестирование */
        Route::post('/payTest', 'YandexKassaAppController@payLocal')->name('.pay.test');
        Route::get('/succes', 'YandexKassaAppController@success')->name('.success');
        Route::get('/succesTest', 'YandexKassaAppController@success')->name('.success.test');
    });
});


/**
 * Дополнительные роуты.
 */
Route::get('/feedback', 'SiteController@feedbackForm')->name('feedback');
Route::post('/feedback', 'SiteController@feedbackSend')->name('feedback.send');



/**
 * Админка.
 */
Route::group(['prefix' => 'admin', 'middleware' => 'can:admin,App\Models\User'], function() {

    Route::get('/', 'AdminController@index')->name('admin');

    /**
     * Вопросы.
     */
    Route::get('/questions', 'QuestionController@questionsAdmin')->name('questions.admin');
    // Обновление
    Route::get('/question/{id}/update', 'QuestionController@updateFormAdmin')->name('question.update.form.admin');
    Route::put('/question/{id}', 'QuestionController@updateAdmin')->name('question.update.admin');
    // Удаление
    Route::get('/question/{id}/delete', 'QuestionController@deleteFormAdmin')->name('question.delete.form.admin');
    Route::delete('/question/{id}', 'QuestionController@deleteAdmin')->name('question.delete.admin');

    /**
     * Документы.
     */
    Route::get('/documents', 'DocumentController@documentsAdmin')->name('documents.admin');
    // Обновление
    Route::get('/document/{id}/update', 'DocumentController@updateFormAdmin')->name('document.update.form.admin');
    Route::put('/document/{id}', 'DocumentController@updateAdmin')->name('document.update.admin');
    // Удаление
    Route::get('/document/{id}/delete', 'DocumentController@deleteFormAdmin')->name('document.delete.form.admin');
    Route::delete('/document/{id}', 'DocumentController@deleteAdmin')->name('document.delete.admin');

    /**
     * Звонки.
     */
    Route::get('/calls', 'CallController@callsAdmin')->name('calls.admin');
    // Обновление
    Route::get('/call/{id}/update', 'CallController@updateFormAdmin')->name('call.update.form.admin');
    Route::put('/call/{id}', 'CallController@updateAdmin')->name('call.update.admin');
    // Удаление
    Route::get('/call/{id}/delete', 'CallController@deleteFormAdmin')->name('call.delete.form.admin');
    Route::delete('/call/{id}', 'CallController@deleteAdmin')->name('call.delete.admin');

    /**
     * Ответы.
     */
    Route::get('/answers/{type}', 'AnswerController@answersAdmin')->name('answers.admin');
    Route::get('/answer/{id}/delete', 'AnswerController@deleteFormAdmin')->name('answer.delete.form.admin');
    Route::delete('/answer/{id}', 'AnswerController@deleteAdmin')->name('answer.delete.admin');

    /**
     * Экспертиза.
     */
    Route::get('/experiences', 'ExpertiseController@expertisesAdmin')->name('expertises.admin');
    Route::get('/expertise/{expertise}/delete', 'ExpertiseController@deleteFormAdmin')->name('expertise.delete.form.admin');
    Route::delete('/expertise/{expertise}', 'ExpertiseController@deleteAdmin')->name('expertise.delete.admin');


    /**
     * Категории права.
     */
    // Создание 
    Route::get('/categories', 'CategoryLawController@categoriesAdmin')->name('categories.admin');
    Route::get('/category/create', 'CategoryLawController@createFormAdmin')->name('category.create.form.admin');
    Route::post('/category', 'CategoryLawController@createAdmin')->name('category.create.admin');
    // Обновление
    Route::get('/category/{id}/update', 'CategoryLawController@updateFormAdmin')->name('category.update.form.admin');
    Route::put('/category/{id}', 'CategoryLawController@updateAdmin')->name('category.update.admin');
    // Удаление
    Route::get('/category/{id}/delete', 'CategoryLawController@deleteFormAdmin')->name('category.delete.form.admin');
    Route::delete('/category/{id}', 'CategoryLawController@deleteAdmin')->name('category.delete.admin');

    /**
     * Темы.
     */
    // Список
    Route::get('/themes', 'ThemeController@themesAdmin')->name('themes.admin');
    // Создание 
    Route::get('/theme/create', 'ThemeController@createFormAdmin')->name('theme.create.form.admin');
    Route::post('/theme', 'ThemeController@createAdmin')->name('theme.create.admin');
    // Обновление
    Route::get('/theme/{id}/update', 'ThemeController@updateFormAdmin')->name('theme.update.form.admin');
    Route::put('/theme/{id}', 'ThemeController@updateAdmin')->name('theme.update.admin');
    // Удаление
    Route::get('/theme/{id}/delete', 'ThemeController@deleteFormAdmin')->name('theme.delete.form.admin');
    Route::delete('/theme/{id}', 'ThemeController@deleteAdmin')->name('theme.delete.admin');

    /**
     * Тип документа.
     */
    // Список
    Route::get('/document-types', 'DocumentTypeController@documentTypesAdmin')->name('document_types.admin');
    // Создание 
    Route::get('/document-type/create', 'DocumentTypeController@createFormAdmin')->name('document_type.create.form.admin');
    Route::post('/document-type', 'DocumentTypeController@createAdmin')->name('document_type.create.admin');
    // Обновление
    Route::get('/document-type/{id}/update', 'DocumentTypeController@updateFormAdmin')->name('document_type.update.form.admin');
    Route::put('/document-type/{id}', 'DocumentTypeController@updateAdmin')->name('document_type.update.admin');
    // Удаление
    Route::get('/document-type/{id}/delete', 'DocumentTypeController@deleteFormAdmin')->name('document_type.delete.form.admin');
    Route::delete('/document-type/{id}', 'DocumentTypeController@deleteAdmin')->name('document_type.delete.admin');

    /**
     * Города.
     */
    // Список
    Route::get('/cities', 'CityController@citiesAdmin')->name('cities.admin');
    // Создание
    Route::get('/city/create', 'CityController@createFormAdmin')->name('city.create.form.admin');
    Route::post('/city', 'CityController@createAdmin')->name('city.create.admin');
    // Обновление
    Route::get('/city/{id}/update', 'CityController@updateFormAdmin')->name('city.update.form.admin');
    Route::put('/city/{id}', 'CityController@updateAdmin')->name('city.update.admin');
    // Удаление
    Route::get('/city/{id}/delete', 'CityController@deleteFormAdmin')->name('city.delete.form.admin');
    Route::delete('/city/{id}', 'CityController@deleteAdmin')->name('city.delete.admin');
    // Добавление региона
    Route::post('/region', 'CityController@createRegionAdmin')->name('region.create.admin');
    // Обновление региона
    Route::put('/region', 'CityController@updateRegionAdmin')->name('region.update.admin');

    /**
     * Файлы.
     */
    // Список
    Route::get('/files', 'FileController@filesAdmin')->name('files');
    Route::get('/file/{id}/delete', 'FileController@deleteFormAdmin')->name('file.delete.form.admin');
    Route::delete('/file/{id}', 'FileController@deleteAdmin')->name('file.delete.admin');

    /**
     * Жалобы.
     */
    // Список
    Route::get('/complaints', 'ComplaintController@complaintsAdmin')->name('complaints.admin');
    // Удаление
    Route::get('/complaint/{id}/delete', 'ComplaintController@deleteForm')->name('complaint.delete.form.admin');
    Route::delete('/complaint/{id}', 'ComplaintController@delete')->name('complaint.delete.admin');

    /**
     * Образование.
     */
    Route::put('/education/{education}', 'EducationController@updateAdmin')->name('education.update.admin');
    Route::get('/education/{education}/delete', 'EducationController@deleteFormAdmin')->name('education.delete.form.admin');
    Route::delete('/education/{education}', 'EducationController@deleteAdmin')->name('education.delete.admin');

    /**
     * Уточнения.
     */
    Route::get('/clarify/{clarify}', 'ClarifyController@updateFormAdmin')->name('clarify.update.form.admin');

    /**
     * Пользователи.
     */
    Route::get('/users', 'UserController@usersAdmin')->name('users.admin');
    Route::get('/user/{user}', 'UserController@updateFormAdmin')->name('user.update.form.admin');
    Route::put('/user/{user}', 'UserController@updateAdmin')->name('user.update.admin');
    Route::get('/user/{user}/delete', 'UserController@deleteFormAdmin')->name('user.delete.form.admin');
    Route::delete('/user/{user}', 'UserController@deleteAdmin')->name('user.delete.admin');

    /**
     * Юристы.
     */
    Route::put('/lawyer/{lawyer}', 'LawyerController@updateAdmin')->name('lawyer.update.admin');
    Route::get('/lawyer/{lawyer}/delete', 'LawyerController@deleteFormAdmin')->name('lawyer.delete.form.admin');
    Route::delete('/lawyer/{lawyer}', 'LawyerController@deleteAdmin')->name('lawyer.delete.admin');

    /**
     * Компании.
     */
    Route::get('/companies', 'CompanyController@companiesAdmin')->name('companies.admin');
    Route::get('/companies/{company}/update', 'CompanyController@updateFormAdmin')->name('company.update.form.admin');
    Route::put('/companies/{company}', 'CompanyController@updateAdmin')->name('company.update.admin');
    Route::get('/companies/{company}/delete', 'CompanyController@deleteFormAdmin')->name('company.delete.form.admin');
    Route::delete('/companies/{company}', 'CompanyController@deleteAdmin')->name('company.delete.admin');

    /**
     * Страницы.
     */
    Route::group(['prefix' => 'pages'], function() {
        Route::get('/', 'PageController@pagesAdmin')->name('pages.admin');
        Route::get('/create', 'PageController@createFormAdmin')->name('page.create.form.admin');
        Route::post('/', 'PageController@createAdmin')->name('page.create.admin');
        Route::get('/{id}/update', 'PageController@updateFormAdmin')->name('page.update.form.admin');
        Route::put('/{id}', 'PageController@updateAdmin')->name('page.update.admin');
        Route::get('/{id}/delete', 'PageController@deleteFormAdmin')->name('page.delete.form.admin');
        Route::delete('/{id}', 'PageController@deleteAdmin')->name('page.delete.admin');
    });

});

Route::auth();

// Страницы
Route::get('/{page}', 'PageController@view')->name('page');
