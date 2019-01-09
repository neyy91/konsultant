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
    // Ссылка на ответ.
    Route::get('/q-{question}#answer{answer}', 'QuestionController@view')->name('question.answer');
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
    // Просмотр
    Route::get('/c-{call}', 'CallController@view')->name('call.view');
});


/**
 * Ответы.
 */
Route::get('/a/{id}', 'AnswerController@redirectToAnswer')->name('answer');
Route::get('/answer/{id}', 'AnswerController@redirectToAnswer')->name('answer.view');
Route::group(['prefix' => 'answer'], function() {
    // Создание ответа на вопрос
    Route::post('/{id}/question', 'AnswerController@createForQuestion')->name('answer.create.question');
    // Подходящий ответ на вопрос
    Route::put('/{id}/question/is', 'AnswerController@setIsForQuestion')->name('answer.is.question');

    // Создание ответа(предложения) документ
    Route::post('/{id}/document', 'AnswerController@createForDocument')->name('answer.create.document');
    // Подходящий ответ документ
    Route::put('/{id}/document/is', 'AnswerController@setIsForDocument')->name('answer.is.document');

    // Создание ответа(заявки) на звонок
    Route::post('/{id}/call', 'AnswerController@createForCall')->name('answer.create.call');
    // Подходящий ответ документ
    Route::put('/{id}/call/is', 'AnswerController@setIsForCall')->name('answer.is.call');

    // Создание ответа на другой ответ
    Route::post('/{id}/answer', 'AnswerController@createForAnswer')->name('answer.create.answer');

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
        Route::get('/', 'LawyerController@lawyers')->name('');
    });
    Route::group(['as' => 'lawyer'], function() {
        Route::get('/{lawyer}/counsultations', 'LawyerController@questions')->name('.questions');
        Route::get('/{lawyer}/feedbacks', 'LawyerController@liked')->name('.liked');
        Route::get('/{lawyer}', 'LawyerController@profile')->name('');
    });
});
// Профиль юриста

// Пользователи
Route::get('/users/{user}', 'UserController@view')->name('user');

// Авторизация и регистрация
Route::get('/login', 'UserController@getLogin')->name('login.form');
Route::post('/login', 'UserController@login')->name('login');
Route::post('/logout', 'UserController@logout')->name('logout');
Route::get('/register', 'UserController@showRegistrationForm')->name('register.form');
Route::post('/register', 'UserController@register')->name('register');
Route::get('/password/reset/{token?}', 'PasswordController@showResetForm')->name('password.reset.form');
Route::post('/password/email', 'PasswordController@sendResetLinkEmail')->name('password.email');
Route::post('/password/reset', 'PasswordController@reset')->name('password.reset');

Route::group(['as' => 'user.', 'prefix' => 'user', 'middleware' => 'auth'], function() {
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
    Route::group(['prefix' => 'chats'], function() {
        Route::get('/', 'ChatController@chats')->name('chats');
        Route::group(['as' => 'chat'], function() {
            Route::post('/{user}', 'ChatController@start')->name('');
            Route::get('/{user}/view', 'ChatController@view')->name('.view');
            Route::put('/{user}/viewed', 'ChatController@viewed')->name('.viewed');
            Route::post('/{user}/send', 'ChatController@messageSend')->name('.message.send');
            Route::delete('/{user}/delete', 'ChatController@delete')->name('.delete');
        });
    });

    // Пользовательское соглашение
    Route::get('/terms', 'UserController@terms')->name('terms');
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
        Route::get('/employees', 'UserEditController@employees')->name('employees.form');
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
    Route::put('/bookmarks/category/{id}', 'BookmarkController@categoryUpdate')->name('.category.update');
    Route::delete('/bookmarks/category/{id}', 'BookmarkController@categoryDelete')->name('.category.delete');
    Route::delete('/bookmarks/{id}', 'BookmarkController@delete')->name('.delete');
    Route::post('/bookmarks', 'BookmarkController@create')->name('.create');
});

/**
 * Компании, партнеры.
 */
Route::get('/partners', 'CompanyController@companies')->name('companies');
Route::get('/partners/{company}', 'CompanyController@view')->name('company');
Route::get('/partners/{company}/edit', 'CompanyController@edit')->name('company.update.form');
Route::put('/partners/{company}', 'CompanyController@update')->name('company.update');

/**
 * Экспертиза.
 */
Route::get('/exp', 'ExpertiseController@redirectToExpertise')->name('expertises');
Route::group(['as' => 'expertise', 'prefix' => 'expertises'], function() {
    Route::post('/question-{id}', 'ExpertiseController@create')->name('.create'); // {id} - id вопроса
    Route::put('/{expertise}', 'ExpertiseController@update')->name('.update');
    Route::delete('/{expertise}', 'ExpertiseController@delete')->name('.delete');
});

/**
 * Дополнительные роуты.
 */
Route::get('/feedback', 'SiteController@feedbackForm')->name('feedback');
Route::post('/feedback', 'SiteController@feedbackSend')->name('feedback.send');


/**
 * Админка.
 */
Route::group(['prefix' => 'admin'], function() {

    Route::get('/', 'AdminController@index')->name('admin');

    /**
     * Вопросы.
     */
    Route::get('/questions', 'QuestionController@questionsAdmin')->name('questions.admin');
    // Обновление
    Route::get('/question/{id}/update', 'QuestionController@updateForm')->name('question.update.form');
    Route::put('/question/{id}', 'QuestionController@update')->name('question.update');
    // Удаление
    Route::get('/question/{id}/delete', 'QuestionController@deleteForm')->name('question.delete.form');
    Route::delete('/question/{id}', 'QuestionController@delete')->name('question.delete');

    /**
     * Документы.
     */
    Route::get('/documents', 'DocumentController@documentsAdmin')->name('documents.admin');
    // Обновление
    Route::get('/document/{id}/update', 'DocumentController@updateForm')->name('document.update.form');
    Route::put('/document/{id}', 'DocumentController@update')->name('document.update');
    // Удаление
    Route::get('/document/{id}/delete', 'DocumentController@deleteForm')->name('document.delete.form');
    Route::delete('/document/{id}', 'DocumentController@delete')->name('document.delete');

    /**
     * Звонки.
     */
    Route::get('/calls', 'CallController@callsAdmin')->name('calls.admin');
    // Обновление
    Route::get('/call/{id}/update', 'CallController@updateForm')->name('call.update.form');
    Route::put('/call/{id}', 'CallController@update')->name('call.update');
    // Удаление
    Route::get('/call/{id}/delete', 'CallController@deleteForm')->name('call.delete.form');
    Route::delete('/call/{id}', 'CallController@delete')->name('call.delete');

    /**
     * Ответы.
     */
    Route::get('/answers/{type}', 'AnswerController@answersAdmin')->name('answers.admin');
    Route::get('/answer/{id}/delete', 'AnswerController@deleteForm')->name('answer.delete.form');
    Route::delete('/answer/{id}', 'AnswerController@delete')->name('answer.delete');
    Route::get('/answer/{answer}', 'AnswerController@updateForm')->name('answer.update.form');

    /**
     * Категории права.
     */
    // Создание 
    Route::get('/categories', 'CategoryLawController@categoriesAdmin')->name('categories.admin');
    Route::get('/category/create', 'CategoryLawController@createForm')->name('category.create.form');
    Route::post('/category', 'CategoryLawController@create')->name('category.create');
    // Обновление
    Route::get('/category/{id}/update', 'CategoryLawController@updateForm')->name('category.update.form');
    Route::put('/category/{id}', 'CategoryLawController@update')->name('category.update');
    // Удаление
    Route::get('/category/{id}/delete', 'CategoryLawController@deleteForm')->name('category.delete.form');
    Route::delete('/category/{id}', 'CategoryLawController@delete')->name('category.delete');

    /**
     * Темы.
     */
    // Список
    Route::get('/themes', 'ThemeController@themesAdmin')->name('themes.admin');
    // Создание 
    Route::get('/theme/create', 'ThemeController@createForm')->name('theme.create.form');
    Route::post('/theme', 'ThemeController@create')->name('theme.create');
    // Обновление
    Route::get('/theme/{id}/update', 'ThemeController@updateForm')->name('theme.update.form');
    Route::put('/theme/{id}', 'ThemeController@update')->name('theme.update');
    // Удаление
    Route::get('/theme/{id}/delete', 'ThemeController@deleteForm')->name('theme.delete.form');
    Route::delete('/theme/{id}', 'ThemeController@delete')->name('theme.delete');

    /**
     * Тип документа.
     */
    // Список
    Route::get('/document-types', 'DocumentTypeController@documentTypesAdmin')->name('document_types.admin');
    // Создание 
    Route::get('/document-type/create', 'DocumentTypeController@createForm')->name('document_type.create.form');
    Route::post('/document-type', 'DocumentTypeController@create')->name('document_type.create');
    // Обновление
    Route::get('/document-type/{id}/update', 'DocumentTypeController@updateForm')->name('document_type.update.form');
    Route::put('/document-type/{id}', 'DocumentTypeController@update')->name('document_type.update');
    // Удаление
    Route::get('/document-type/{id}/delete', 'DocumentTypeController@deleteForm')->name('document_type.delete.form');
    Route::delete('/document-type/{id}', 'DocumentTypeController@delete')->name('document_type.delete');

    /**
     * Города.
     */
    // Список
    Route::get('/cities', 'CityController@citiesAdmin')->name('cities.admin');
    // Создание
    Route::get('/city/create', 'CityController@createForm')->name('city.create.form');
    Route::post('/city', 'CityController@create')->name('city.create');
    // Обновление
    Route::get('/city/{id}/update', 'CityController@updateForm')->name('city.update.form');
    Route::put('/city/{id}', 'CityController@update')->name('city.update');
    // Удаление
    Route::get('/city/{id}/delete', 'CityController@deleteForm')->name('city.delete.form');
    Route::delete('/city/{id}', 'CityController@delete')->name('city.delete');
    // Добавление региона
    Route::post('/region', 'CityController@createRegion')->name('region.create');
    // Обновление региона
    Route::put('/region', 'CityController@updateRegion')->name('region.update');

    /**
     * Файлы.
     */
    // Список
    Route::get('/files', 'FileController@files')->name('files');
    Route::get('/file/{id}/delete', 'FileController@deleteForm')->name('file.delete.form');
    Route::delete('/file/{id}', 'FileController@delete')->name('file.delete');

    /**
     * Жалобы.
     */
    // Список
    Route::get('/complaints', 'ComplaintController@complaints')->name('complaints');
    // Удаление
    Route::get('/complaint/{id}/delete', 'ComplaintController@deleteForm')->name('complaint.delete.form');
    Route::delete('/complaint/{id}', 'ComplaintController@delete')->name('complaint.delete');

    /**
     * Образование.
     */
    Route::get('/education/{education}', 'UserEditController@educationAdminForm')->name('education.admin.form');
    Route::put('/education/{education}', 'UserEditController@educationAdminUpdate')->name('education.admin.update');

    /**
     * Уточнения.
     */
    Route::get('/clarify/{clarify}', 'ClarifyController@updateForm')->name('clarify.update.form');

    /**
     * Пользователи.
     */
    Route::get('/user/{user}', 'UserController@userUpdateForm')->name('user.update.form');

    /**
     * Юристы.
     */
    Route::get('/lawyer/{lawyer}', 'UserController@lawyerUpdateForm')->name('lawyer.update.form');

    /**
     * Компании.
     */
    Route::get('/companies', 'CompanyController@companiesAdmin')->name('companies.admin');
    Route::get('/companies/{company}/edit', 'CompanyController@adminEdit')->name('company.admin.edit');
    Route::put('/companies/{company}', 'CompanyController@adminUpdate')->name('company.admin.update');
    Route::get('/companies/{company}/delete', 'CompanyController@adminDeleteForm')->name('company.admin.delete.form');
    Route::delete('/companies/{company}', 'CompanyController@adminDelete')->name('company.admin.delete');

});

// Route::auth();
