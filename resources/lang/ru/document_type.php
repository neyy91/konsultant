<?php 

return [
    'about' => 'Тип документа',
    'title' => 'Типы документов',
    'add_document_type' => 'Добавить новый тип документа',
    'update_document_type' => 'Редактировать тип документа',
    'delete_document_type' => 'Удалить тип документа',
    'form' => [
        'legend' => [
            'create' => 'Создание типа документа',
            'update' => 'Обновление типа документа',
            'delete' => 'Удаление типа документа',
        ],
        'id' => 'ID',
        'name' => 'Название документа',
        'autoslug' => 'Автогенерация URL',
        'slug' => 'Часть URL',
        'parent_id' => 'Родитель документа',
        'parent_id_first' => '(Выберите тип документа)',
        'sort' => 'Сортировка',
        'status' => 'Статус',
        'status_first' => '(Выберите статус)',
        'description' => 'Описание',
        'text' => 'Подробное описание',
    ],
    'field' => [
        'name' => 'Название типа документа',
        'status' => 'Статус',
        'parent_id' => 'Родитель',
    ],
    'message' => [
        'created' => 'Новый тип документа успешно создан.',
        'updated' => 'Новый тип документа успешно обновлен.',
        'deleted' => 'Тип документа был окончательно удален.',
        'childs_exists' => 'Тип документа не может быть удален, т.к. имеются дочерние типы или документы принадлежащие этому типу документа. Удалите их сначала.',
    ],
];