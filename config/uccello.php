<?php

return [
    'workspace' => [
        'multi_workspaces' => env('UCCELLO_MULTI_WorkspaceS', false),
    ],
    'database' => [
        'table_prefix' => 'uccello_',
    ],
    'datatable' => [
        // Number of results by page
        'length' => env('UCCELLO_DATATABLE_RESULTS_BY_PAGE', 15),
    ],
    'format' => [
        'php' => [
            // Date format to use with PHP.
            'date' => 'd/m/Y',

            // DateTime format to use with PHP.
            'datetime' => 'd/m/Y H:i',

            // Time format to use with PHP.
            'time' => 'H:i',
        ],
        'js' => [
            // Date format to use with JavaScript.
            'date' => 'DD/MM/YYYY',

            // DateTime format to use with JavaScript.
            'datetime' => 'DD/MM/YYYY HH:mm',

            // Time format to use with JavaScript.
            'time' => 'HH:mm',
        ],
    ],
];
