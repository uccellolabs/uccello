<?php

return [
    // Name of the theme you want to use.
    'theme' => 'uccello',

    'domains' => [
        // If true, display the modal for switching between domains.
        'display_tree' => true,

        // If true, the domain tree is automatically opened in the modal.
        // If false, the user have to click on each domain tree node to load its children domains.
        // This option is very useful when you want to manage a big number of domains in a Uccello application.
        'open_tree' => true,
    ],

    'max_results' => [
        // Maximum number of results for each module, for a global search on all modules.
        'search' => 50,

        // Maximum number of results for each module, for an autocomplete field.
        'autocomplete' => 10,
    ],

    'format' => [
        'php' => [
            // Date format to use with PHP.
            'date' => 'm/d/Y',

            // DateTime format to use with PHP.
            'datetime' => 'm/d/Y H:i',

            // Time format to use with PHP.
            'time' => 'H:i',
        ],
        'js' => [
            // Date format to use with JavaScript.
            'date' => 'MM/DD/YYYY',

            // DateTime format to use with JavaScript.
            'datetime' => 'MM/DD/YYYY HH:mm',

            // Time format to use with JavaScript.
            'time' => 'HH:mm',
        ],
    ],

    'roles' => [
        // If true, displays in the current domain, the list of the roles created in an ancestor domain.
        'display_ancestors_roles' => false
    ],

    'treeview' => [
        // If true, the domain tree is automatically opened in the Tree View.  If false, the user have to click on each tree node to load its children records.
        'open_tree' => true
    ],

    'users' => [
        // If true, all users with a role defined in the current domain or in an ancestor one will be shown in the users list.
        'display_all_users_with_role' => false,

        // If true, all admin users will be shown in the users list.
        'display_all_admin_users' => false,
    ],
];
