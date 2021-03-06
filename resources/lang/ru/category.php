<?php 

return [
    'title' => 'Категории',
    'title_law' => 'Категория права',
    'private_person' => 'Частное лицо',
    'business' => 'Представитель бизнеса',
    'view_all_questions_category' => 'Посмотреть все вопросы категории &laquo;:name&raquo;',
    'view_all_questions' => 'Посмотреть все вопросы',
    'create_category' => 'Создать категорию',
    'add_category' => 'Добавить категорию',
    'update_category' => 'Обновить категорию',
    'delete_category' => 'Удалить категории',
    'select_category' => 'Выберите категорию права',
    'categories_themes' => 'Категории и актуальные темы',
    'form' => [
        'legend' => [
            'create' => 'Создание категории права',
            'update' => 'Обновление категории права',
            'delete' => 'Удаление категории права',
            'filter' => 'Фильтрация категории',
        ],
        'id' => 'ID',
        'name' => 'Название категории',
        'autoslug' => 'Автогенерация URL',
        'slug' => 'Часть URL',
        'parent_id' => 'Категория',
        'parent_id_first' => '(Выберите категорию права)',
        'sort' => 'Сортировка',
        'status' => 'Статус',
        'status_first' => '(Выберите статус)',
        'description' => 'Описание',
        'text' => 'Подробное описание',
    ],
    'field' => [
        'name' => 'Название',
        'parent_id' => 'Родитель',
        'sort' => 'Сортировка',
        'status' => 'Статус',
    ],
    'message' => [
        'childs_exists' => 'У категории есть подкатегории или вопросы принадлежащие этой категории. Удалите их сначала.',
        'created' => 'Категория права успешно создана.',
        'updated' => 'Категория права успешно обновлена.',
        'deleted' => 'Категория права успешно была удалена.',
    ],
];