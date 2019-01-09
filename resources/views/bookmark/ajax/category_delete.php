<?php 
return [
    'doctype' => 'AJAX',
    '$' => [
        'context' => [
            'find' => '.categories-content',
            'html' => $categoriesHtml,
        ],
    ],
    'messages' => [
        'success' => trans('bookmark.message.bookmark_category_success_delete'),
    ],
];