<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Языковые ресурсы для проверки значений
    |--------------------------------------------------------------------------
    |
    | Последующие языковые строки содержат сообщения по-умолчанию, используемые
    | классом, проверяющим значения (валидатором).Некоторые из правил имеют
    | несколько версий, например, size. Вы можете поменять их на любые
    | другие, которые лучше подходят для вашего приложения.
    |
    */

    'accepted'             => 'Вы должны принять &laquo;:attribute&raquo;',
    'active_url'           => '&laquo;:attribute&raquo; содержит недействительный URL.',
    'after'                => 'В поле &laquo;:attribute&raquo; должна быть дата после &laquo;:date&raquo;',
    'after_or_equal'       => 'The &laquo;:attribute&raquo; must be a date after or equal to &laquo;:date&raquo',
    'alpha'                => '&laquo;:attribute&raquo; может содержать только буквы.',
    'alpha_dash'           => '&laquo;:attribute&raquo; может содержать только буквы, цифры и дефис.',
    'alpha_num'            => '&laquo;:attribute&raquo; может содержать только буквы и цифры.',
    'array'                => '&laquo;:attribute&raquo; должно быть массивом.',
    'before'               => 'В поле &laquo;:attribute&raquo; должна быть дата до &laquo;:date&raquo;.',
    'before_or_equal'      => 'The &laquo;:attribute&raquo; must be a date before or equal to &laquo;:date&raquo;.',
    'between'              => [
        'numeric' => '&laquo;:attribute&raquo; должно быть между &laquo;:min&raquo; и &laquo;:max&raquo;.',
        'file'    => 'Размер файла в поле &laquo;:attribute&raquo; должен быть между &laquo;:min&raquo; и &laquo;:max&raquo; Килобайт(а).',
        'string'  => 'Количество символов в поле &laquo;:attribute&raquo; должно быть между &laquo;:min&raquo; и &laquo;:max&raquo;.',
        'array'   => 'Количество элементов в поле &laquo;:attribute&raquo; должно быть между &laquo;:min&raquo; и &laquo;:max&raquo;.',
    ],
    'boolean'              => '&laquo;:attribute&raquo; должно иметь значение логического типа.', // калька 'истина' или 'ложь' звучала бы слишком неестественно
    'confirmed'            => '&laquo;:attribute&raquo; не совпадает с подтверждением.',
    'date'                 => '&laquo;:attribute&raquo; не является датой.',
    'date_format'          => '&laquo;:attribute&raquo; не соответствует формату &laquo;:format&raquo;.',
    'different'            => 'Поля &laquo;:attribute&raquo; и &laquo;:other&raquo; должны различаться.',
    'digits'               => 'Длина цифрового поля &laquo;:attribute&raquo; должна быть &laquo;:digits&raquo;.',
    'digits_between'       => 'Длина цифрового поля &laquo;:attribute&raquo; должна быть между &laquo;:min&raquo; и &laquo;:max&raquo;.',
    'dimensions'           => '&laquo;:attribute&raquo; имеет недопустимые размеры изображения.',
    'distinct'             => '&laquo;:attribute&raquo; содержит повторяющееся значение.',
    'email'                => '&laquo;:attribute&raquo; должно быть действительным электронным адресом.',
    'file'                 => '&laquo;:attribute&raquo; должно быть файлом.',
    'filled'               => '&laquo;:attribute&raquo; обязательно для заполнения.',
    'exists'               => 'Выбранное значение для &laquo;:attribute&raquo; некорректно.',
    'image'                => '&laquo;:attribute&raquo; должно быть изображением.',
    'in'                   => 'Выбранное значение для &laquo;:attribute&raquo; ошибочно.',
    'in_array'             => '&laquo;:attribute&raquo; не существует в &laquo;:other&raquo;.',
    'integer'              => '&laquo;:attribute&raquo; должно быть целым числом.',
    'ip'                   => '&laquo;:attribute&raquo; должно быть действительным IP-адресом.',
    'json'                 => '&laquo;:attribute&raquo; должно быть JSON строкой.',
    'max'                  => [
        'numeric' => '&laquo;:attribute&raquo; не может быть более &laquo;:max&raquo;.',
        'file'    => 'Размер файла в поле &laquo;:attribute&raquo; не может быть более &laquo;:max&raquo; Килобайт(а).',
        'string'  => 'Количество символов в поле &laquo;:attribute&raquo; не может превышать &laquo;:max&raquo;.',
        'array'   => 'Количество элементов в поле &laquo;:attribute&raquo; не может превышать &laquo;:max&raquo;.',
    ],
    'mimes'                => '&laquo;:attribute&raquo; должно быть файлом одного из следующих типов: &laquo;:values&raquo;.',
    'mimetypes'            => '&laquo;:attribute&raquo; должно быть файлом одного из следующих типов: &laquo;:values&raquo;.',
    'min'                  => [
        'numeric' => '&laquo;:attribute&raquo; должно быть не менее &laquo;:min&raquo;.',
        'file'    => 'Размер файла в поле &laquo;:attribute&raquo; должен быть не менее &laquo;:min&raquo; Килобайт(а).',
        'string'  => 'Количество символов в поле &laquo;:attribute&raquo; должно быть не менее &laquo;:min&raquo;.',
        'array'   => 'Количество элементов в поле &laquo;:attribute&raquo; должно быть не менее &laquo;:min&raquo;.',
    ],
    'not_in'               => 'Выбранное значение для &laquo;:attribute&raquo; ошибочно.',
    'numeric'              => '&laquo;:attribute&raquo; должно быть числом.',
    'present'              => '&laquo;:attribute&raquo; должно присутствовать.',
    'regex'                => '&laquo;:attribute&raquo; имеет ошибочный формат.',
    'required'             => '&laquo;:attribute&raquo; обязательно для заполнения.',
    'required_if'          => '&laquo;:attribute&raquo; обязательно для заполнения, когда &laquo;:other&raquo; равно &laquo;:value&raquo;.',
    'required_unless'      => '&laquo;:attribute&raquo; обязательно для заполнения, когда &laquo;:other&raquo; не равно &laquo;:values&raquo;.',
    'required_with'        => '&laquo;:attribute&raquo; обязательно для заполнения, когда &laquo;:values&raquo; указано.',
    'required_with_all'    => '&laquo;:attribute&raquo; обязательно для заполнения, когда &laquo;:values&raquo; указано.',
    'required_without'     => '&laquo;:attribute&raquo; обязательно для заполнения, когда &laquo;:values&raquo; не указано.',
    'required_without_all' => '&laquo;:attribute&raquo; обязательно для заполнения, когда ни одно из &laquo;:values&raquo; не указано.',
    'same'                 => 'Значение &laquo;:attribute&raquo; должно совпадать с &laquo;:other&raquo;.',
    'size'                 => [
        'numeric' => '&laquo;:attribute&raquo; должно быть равным &laquo;:size&raquo;.',
        'file'    => 'Размер файла в поле &laquo;:attribute&raquo; должен быть равен &laquo;:size&raquo; Килобайт(а).',
        'string'  => 'Количество символов в поле &laquo;:attribute&raquo; должно быть равным &laquo;:size&raquo;.',
        'array'   => 'Количество элементов в поле &laquo;:attribute&raquo; должно быть равным &laquo;:size&raquo;.',
    ],
    'string'               => '&laquo;:attribute&raquo; должно быть строкой.',
    'timezone'             => '&laquo;:attribute&raquo; должно быть действительным часовым поясом.',
    'unique'               => '&laquo;:attribute&raquo; уже существует в базе.',
    'uploaded'             => 'Загрузка поля &laquo;:attribute&raquo; не удалась.',
    'url'                  => '&laquo;:attribute&raquo; имеет ошибочный формат.',

    /*
    |--------------------------------------------------------------------------
    | Собственные языковые ресурсы для проверки значений
    |--------------------------------------------------------------------------
    |
    | Здесь Вы можете указать собственные сообщения для атрибутов.
    | Это позволяет легко указать свое сообщение для заданного правила атрибута.
    |
    | http://laravel.com/docs/5.1/validation#custom-error-messages
    | Пример использования
    |
    |   'custom' => [
    |       'email' => [
    |           'required' => 'Нам необходимо знать Ваш электронный адрес!',
    |       ],
    |   ],
    |
    */

    'non_present' => '&laquo;:attribute&raquo; запрещено менять',

    /*
    |--------------------------------------------------------------------------
    | Собственные названия атрибутов
    |--------------------------------------------------------------------------
    |
    | Последующие строки используются для подмены программных имен элементов
    | пользовательского интерфейса на удобочитаемые. Например, вместо имени
    | поля "email" в сообщениях будет выводиться "электронный адрес".
    |
    | Пример использования
    |
    |   'attributes' => [
    |       'email' => 'электронный адрес',
    |   ],
    |
    */

    'attributes' => [
        'firstname' => 'Имя',
        'lastname' => 'Фамилия',
        'email' => 'Email',
        'password' => 'Пароль',
        'company' => 'Компания',
    ],

];
