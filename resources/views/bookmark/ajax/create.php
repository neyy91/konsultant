<?php 
return [
    'doctype' => 'AJAX',
    '$' => [
        'target' => [
            'parents' => '.popover:first',
            'data' => 'bs.popover',
            '$element' => null,
            'attr' => ['data-data' => $targetData],
            'find' => '.glyphicon-star-empty',
            'removeClass' => 'glyphicon-star-empty',
            'addClass' => 'glyphicon-star',
        ],
        'context' => [
            'find' => '.bookmark-categories',
            'html' => $categoriesHtml,
        ],
        '.dropdown-categories' => [
        ],
    ],
    'messages' => $messages
];