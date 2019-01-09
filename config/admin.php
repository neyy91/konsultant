<?php 

return [
    'page' => 20,
    'categories' => [
        'level' => 3,
    ],
    'document_type' => [
        'level' => 3,
    ],
    'menu' => [
        [
            'label' => 'question.title',
            'menu' => [
                [
                    'label' => 'question.title',
                    'route' => 'questions.admin',
                ],
                [
                    'label' => 'category.title',
                    'route' => 'categories.admin',
                ],
                [
                    'label' => 'theme.title',
                    'route' => 'themes.admin',
                ],
                [
                    'label' => 'question.answers_to_questions',
                    'route' => 'answers.admin',
                    'params' => ['question'],
                ],
                [
                    'label' => 'expertise.title',
                    'route' => 'expertises.admin',
                ]
            ],
        ],

        [
            'label' => 'document.title',
            'menu' => [
                [
                    'label' => 'document.title',
                    'route' => 'documents.admin',
                ],
                [
                    'label' => 'document_type.title',
                    'route' => 'document_types.admin',
                ],
                [
                    'label' => 'document.offers_from_law',
                    'route' => 'answers.admin',
                    'params' => ['document'],
                ]
            ]
        ],

        [
            'label' => 'call.title',
            'menu' => [
                [
                    'label' => 'call.title',
                    'route' => 'calls.admin',
                ],
                [
                    'label' => 'call.requests_from_lawyers',
                    'route' => 'answers.admin',
                    'params' => ['call'],
                ]
            ]
        ],

        [
            'label' => 'complaint.title',
            'route' => 'complaints.admin',
        ],

        [
            'label' => 'user.title',
            'route' => 'users.admin'
        ],

        [
            'label' => 'app.datas',
            'menu' => [
                [
                    'label' => 'city.cities_and_regions',
                    'route' => 'cities.admin',
                ],
                [
                    'label' => 'file.title',
                    'route' => 'files',
                ],
                [
                    'label' => 'page.title',
                    'route' => 'pages.admin',
                ]
            ]
        ],
    ],
];