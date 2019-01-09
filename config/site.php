<?php 

return [
    'global' => [
        'max_text' => 5000,
    ],
    'slug' => [
        'separator' => '-',
        'limit' => 100,
        'suffix' => '',
    ],
    'mail' => [
        'feedback' => 'kratkar@yandex.ru',
    ],
    'date' => [
        'short' => 'd.m.Y',
        'middle' => 'd.m.Y H:i',
        'long' => 'j F Y, H:i',
    ],
    'breadcrumb' => [
        'limit' => 50,
    ],
    'question' => [
        'max_text' => 5000,
        'file' => [
            'max_size' => 500,
            'mimes' => 'doc,docx,pdf,odt,txt,jpg,jpeg,gif,png',
            'directory' => 'private/questions',
        ],
        'update_time' => 180,
        'page' => 10,
        'take' => 5,
        'pay' => [
            'vip' => 190,
            'standart' => 90,
            'free' => 0,
        ],
        'pay_old' => [
            'vip' => 500
        ],
    ],
    'document' => [
        'max_text' => 5000,
        'file' => [
            'max_size' => 500,
            'mimes' => 'doc,docx,pdf,odt,txt,jpg,jpeg,gif,png',
            'directory' => 'private/documents',
        ],
        'page' => 10,
        'take' => 5,
        'min_price' => 800,
    ],
    'call' => [
        'max_text' => 5000,
        'file' => [
            'max_size' => 500,
            'mimes' => 'doc,docx,pdf,odt,txt,jpg,jpeg,gif,png',
            'directory' => 'private/calls',
        ],
        'page' => 10,
        'min_price' => 500,
        'pay' => [
            'free' => 0,
            'simple' => 390,
            'standart' => 690,
            'vip' => 990,
        ],
        'pay_old' => [
        ],
    ],
    'answer' => [
        'max_text' => 5000,
        'file' => [
            'max_size' => 500,
            'mimes' => 'doc,docx,pdf,odt,txt,jpg,jpeg,gif,png',
            'directory' => 'private/answers',
        ],
    ],
    'clarify' => [
        'max_text' => 5000,
        'file' => [
            'max_size' => 500,
            'mimes' => 'doc,docx,pdf,odt,txt,jpg,jpeg,gif,png',
            'directory' => 'private/clarifies',
        ],
    ],
    'lawyers' => [
        'page' => 10,
        'take' => 5,
    ],
    'user' => [
        'online_time' => 120,
        'order' => [
            'page' => 5,
            'take' => 5,
            'limit' => 50,
        ],
        'answer' => [
            'page' => 10,
            'take' => 5,
            'limit' => 50,
        ],
        'question' => [
            'page' => 10,
            'take' => 5,
            'limit' => 50,
        ],
        'like' => [
            'page' => 12,
            'take' => 4,
            'limit' => 40,
            'text_max' => 1000,
        ],
        'perpages' => [10, 20, 50, 100],
        'photo' => [
            'directory' => 'public/photos',
            'mimes' => 'jpg,jpeg,png',
            'filesize' => 500,
            'sizes' => [
                'xsmall' => [30, 50],
                'small' => [60, 70],
                'middle' => [120, 110],
                'large' => [180, 140],
                'big' => [240, 180],
            ],
            'default' => '/storage/default/photo_{gender}.jpg'
        ],
        'aboutmyself' => [
            'max_string' => 150,
        ],
        'honors' => [
            'directory' => 'public/honors',
            'mimes' => 'jpg,jpeg',
            'filesize' => 500,
        ],
        'education' => [
            'file' => [
                'mimes' => 'jpeg,jpg',
                'filesize' => 500,
                'directory' => 'private/educations',
            ],
        ],
        'bookmark' => [
            'page' => 10,
            'max_category' => 20,
        ],
    ],
    'lawyer' => [
        'pay' => [100, 200, 400, 900],
    ],
    'company' => [
        'page' => 10,
        'logo' => [
            'max_size' => 500,
            'mimes' => 'jpg,jpeg,png',
            'directory' => 'public/company/logo',
        ],
    ],
    'chat' => [
        'min_price' => 500,
        'count' => [
            'messages' => 20,
            'chats' => 20,
        ],
        'message' => [
            'max_text' => 5000,
        ],
        'file' => [
            'max_size' => 500,
            'mimes' => 'doc,docx,pdf,odt,txt,jpg,jpeg,gif,png',
            'directory' => 'private/chat'
        ],
    ],
    'city' => [
        'count' => 10,
    ],
    'expertise' => [
        'max_message' => 5000,
        'expert_count' => 3,
    ],
    'page' => [
        'layouts' => ['app'],
        'page_layouts' => ['one']
    ],
];