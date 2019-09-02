<?php

return [
    'theme' => 'uccello',


    'max_results' => [
        'search' => 50,
        'autocomplete' => 10,
    ],

    'format' => [
        'php' => [
            'date' => 'm/d/Y',
            'datetime' => 'm/d/Y H:i',
            'time' => 'H:i',
        ],
        'js' => [
            'date' => 'MM/DD/YYYY',
            'datetime' => 'MM/DD/YYYY HH:mm',
            'time' => 'HH:mm',
        ],
    ],

    'roles' => [
        'display_ancestors_roles' => false
    ],
];
