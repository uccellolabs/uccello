<?php

return [
    'settings' => 'Settings',

    // Breadcrumb
    'breadcrumb' => [
        'dashboard' => 'Dashboard',
        'module_manager' => 'Module manager',
        'menu_manager' => 'Menu manager',
    ],

    // Stats
    'stats' => [
        'modules' => 'Modules',
    ],

    // Module manager
    'module_manager' => [
        'link' => 'Module manager',
        'main_modules' => 'Main modules',
        'admin_modules' => 'Settings modules',
        'description' => 'You can activate or deactivate a module by clicking on a checkbox.',
        'tab' => [
            'main_modules' => 'Main',
            'admin_modules' => 'Settings',
        ],
        'notification' => [
            'module_activated' => 'Module activated',
            'module_deactivated' => 'Module deactivated',
        ],
        'error' => [
            'module_is_mandatory' => 'This module is mandatory and cannot be deactivated.',
            'module_not_defined' => 'You must define a module name.',
            'save' => 'An error occurred when saving.',
        ]
    ],

    // Menu manager
    'menu_manager' => [
        'link' => 'Menu manager',
        'page' => [
            'title' => 'Menu manager',
            'description' => 'Configure menu to access easily to all functionalities.',
        ],
        'menu' => [
            'type' => [
                'main' => 'Main',
                'admin' => 'Admin',
            ],
            'link_type' => [
                'group' => 'Group',
                'module' => 'Module',
            ],
            'button' => [
                'add_group' => 'Add a group',
                'add_route_link' => 'Add a route link',
                'add_link' => 'Add a link',
                'reset' => 'Reset',
            ],
            'reset' => [
                'title' => 'Reset the menu?',
                'text' => 'The menu will be replaced by the default one.',
            ],
            'error' => [
                'not_empty' => [
                    'title' => 'Remove links first!',
                    'description' => 'Yan cannot delete a group not empty.',
                ],
            ],
        ],
        'modal' => [
            'group' => [
                'title' => 'Group',
                'label' => 'Label',
                'icon' => 'Icon',
            ],
            'link' => [
                'title' => 'Link',
                'label' => 'Label',
                'icon' => 'Icon',
                'url' => 'URL',
            ],
            'route' => [
                'title' => 'Route',
                'module' => 'Module',
                'route' => 'Route',
            ],
        ],
        'label' => [
            'saved' => 'Saved',
            'see' => 'See',
        ]
    ],
];