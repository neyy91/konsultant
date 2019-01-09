<?php 
return [
    'doctype' => 'AJAX',
    '$' => [
        'target' => [
            'parents' => '.bookmark-popover',
            'data' => 'bs.popover',
            '$element' => null,
            'attr' => ['data-data' => $targetData],
            'find' => '.glyphicon-star',
            'removeClass' => 'glyphicon-star',
            'addClass' => 'glyphicon-star-empty',
        ],
        'context' => [
            'find' => '.bookmark-categories',
            'html' => $categoriesHtml,
        ],
    ],
    'messages' => [
        'warning' => trans('bookmark.message.bookmark_success_remove'),
    ],
];