<?php 
if ($type == 'popover') {
    $context = [
        'find' => '.bookmark-categories',
        'html' => $categoriesHtml,
    ];
}
else {
    $context = [
        'find' => '.categories-content',
        'html' => $categoriesHtml,
    ];
}
// $context['html'] = $categoriesHtml;

return [
    'doctype' => 'AJAX',
    '$' => [
        [
            'context' => $context,
        ],
        [
            'context' => [
                'find' => '.form-categories-create:first',
                'get' => 0,
                'reset' => null
            ],
        ]
    ],
    'messages' => [
        'success' => trans('bookmark.message.bookmark_category_success_create'),
    ],
];