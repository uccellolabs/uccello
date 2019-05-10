<?php

return [
    'settings' => 'Paramètres',

    // Breadcrumb
    'breadcrumb' => [
        'dashboard' => 'Tableau de bord',
        'module_manager' => 'Gestion des modules',
        'menu_manager' => 'Gestion du menu',
    ],

    // Stats
    'stats' => [
        'modules' => 'Modules',
    ],

    // Module manager
    'module_manager' => [
        'link' => 'Gestion des modules',
        'main_modules' => 'Modules principaux',
        'admin_modules' => 'Modules d\'administration',
        'description' => 'Vous pouvez activer ou désactiver un module en cliquant sur une case à cocher.',
        'tab' => [
            'main_modules' => 'Principaux',
            'admin_modules' => 'Administration',
        ],
        'notification' => [
            'module_activated' => 'Module activé',
            'module_deactivated' => 'Module désactivé',
        ],
        'error' => [
            'module_is_mandatory' => 'Ce module est obligatoire et ne peut pas être désactivé.',
            'module_not_defined' => 'Vous devez définir un nom de module.',
            'save' => 'Une erreur s\'est produite lors de la sauvegarde.',
        ]
    ],

    // Menu manager
    'menu_manager' => [
        'link' => 'Gestion du menu',
        'page' => [
            'title' => 'Menu manager',
            'description' => 'Configurez le menu pour accéder facilement à toutes les fonctionnalités.',
        ],
        'menu' => [
            'type' => [
                'main' => 'Principal',
                'admin' => 'Administration',
            ],
            'link_type' => [
                'group' => 'Groupe',
                'module' => 'Module',
            ],
            'button' => [
                'add_group' => 'Ajouter un groupe',
                'add_route_link' => 'Ajouter une route',
                'add_link' => 'Ajouter un lien',
                'reset' => 'Réinitialiser',
            ],
            'reset' => [
                'title' => 'Réinitialiser le menu ?',
                'text' => 'Le menu sera remplacé par le menu par défaut.',
            ],
            'error' => [
                'not_empty' => [
                    'title' => 'Supprimez d\'abord les liens',
                    'description' => 'Vous ne pouvez pas supprimer un groupe qui n\'est pas vide.',
                ],
                'save' => 'Une erreur s\'est produite lors de la sauvegarde.',
            ],
        ],
        'modal' => [
            'group' => [
                'title' => 'Groupe',
                'label' => 'Libellé',
                'icon' => 'Icône',
            ],
            'link' => [
                'title' => 'Lien',
                'label' => 'Libellé',
                'icon' => 'Icône',
                'url' => 'URL',
            ],
            'route' => [
                'title' => 'Route',
                'module' => 'Module',
                'route' => 'Route',
            ],
        ],
        'label' => [
            'saved' => 'Sauvegardé',
            'see' => 'Voir',
        ]
    ],
];