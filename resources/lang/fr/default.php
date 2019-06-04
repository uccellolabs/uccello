<?php

return [
    // General
    'yes' => 'Oui',
    'no' => 'Non',
    'me' => 'Moi',

    // Menu
    'menu' => [
        'title' => 'Menu',
        'return' => 'Retour',
        'admin' => 'Administration',
        'dashboard' => 'Tableau de bord',
        'security' => 'Sécurité',
        'settings' => 'Paramètres',
    ],

    // Domain
    'domain' => [
        'search' => 'Rechercher un domaine',
    ],

    // Breadcrumb
    'breadcrumb' => [
        'create' => 'Création',
        'edit' => 'Édition',
        'admin' => 'Administration',
    ],

    // Button
    'button' => [
        'return' => 'Retour',
        'save' => 'Sauvegarder',
        'save_new' => 'Sauvegarder et nouveau',
        'new' => 'Nouveau',
        'edit' => 'Éditer',
        'delete' => 'Supprimer',
        'clear_search' => 'Effacer',
        'manage_filters' => 'Gestion des filtres',
        'add_filter' => 'Ajouter un filtre',
        'delete_filter' => 'Supprimer le filtre',
        'action' => 'Actions',
        'yes' => 'Oui',
        'no' => 'Non',
        'cancel' => 'Annuler',
        'add_widget' => 'Ajouter widget',
        'export' => 'Exporter',
        'columns' => 'Colonnes',
        'download_file' => 'Télécharger',
        'logout' => 'Déconnexion',
        'delete_related_record' => 'Supprimer la relation',
        'user_account' => 'Mon compte',
    ],

    // Block
    'block' => [
        'general' => 'Informations générales',
    ],

    // Field
    'field' => [
        'id' => 'Id',
        'created_at' => 'Créé le',
        'updated_at' => 'Mis à jour le',
        'select_empty_value' => '- Selectionnez une valeur -',
        'info' => [
            'new_line' => 'Appuyez sur ENTRÉE pour aller à la ligne.',
        ]
    ],

    // Filter
    'filter' => [
        'all' => 'Tous',
        'show_n_records' => ':number lignes',
        'delete' => [
            'message' => 'Supprimer ce filtre ?',
        ],
        'exists' => [
            'title' => 'Un filtre du même nom existe déjà',
            'message' => 'Voulez-vous le mettre à jour ?',
        ],
    ],

    // Tabs
    'tab' => [
        'main' => 'Détails',
        'summary' => 'Résumé',
    ],

    // Datatable
    'datatable' => [
        'search' => 'Rechercher',
        'no_results' => 'Aucun résultat',
        'loading' => 'Chargement en cours...',
    ],

    // Related list
    'relatedlist' => [
        'button' => [
            'add' => 'Ajouter',
            'select' => 'Sélectionner',
            'columns' => 'Colonnes',
            'lines' => 'Lignes',
        ],
    ],

    // Confirm
    'confirm' => [
        'dialog' => [
            'title' => 'Êtes-vous sûr?',
        ],
        'button' => [
            'delete_record' => 'Supprimer cet enregistrement ?',
            'delete_relation' => 'Supprimer cette relation ?',
        ],
    ],

    // Notification
    'notification' => [
        'record' => [
            'created' => 'L\'enregistrement a bien été créé.',
            'deleted' => 'L\'enregistrement a bien été supprimé.',
        ],
        'relation' => [
            'deleted' => 'La relation a bien été supprimée.',
        ],
        'form' => [
            'not_valid' => 'Certains champs ne sont pas valides !',
        ],
    ],

    // Dialog
    'dialog' => [
        'error' => [
            'title' => 'Erreur',
            'message' => 'Une erreur s\'est produite',
        ],
        'success' => [
            'title' => 'Réussi',
        ],
    ],

    // Errors
    'error' => [
        'field' => [
            'mandatory' => 'Certains champs obligatoires ne sont pas définis.',
        ],
        'filter' => [
            'not_found' => 'Le filtre n\'existe pas.',
            'read_only' => 'Le filtre est en lecture seule.',
        ],
        'mandatory_fields' => 'Certains champs obligatoires ne sont pas définis.',

    ],

    // Success
    'success' => [
        'filter' => [
            'deleted' => 'Le filtre a bien été supprimé.',
        ]
    ],

    // Modal
    'modal' => [
        'add_filter' => [
            'title' => 'Ajouter un filtre',
            'description' => 'Vous pouvez créer un nouveau filtre à partir de la configuration actuelle de la liste.',
            'name' => 'Nom du filtre',
            'save_columns' => 'Sauvegarder les colonnes affichées',
            'save_conditions' => 'Sauvegarder les conditions de recherche',
            'save_order' => 'Sauvegarder l\'ordre de tri',
            'save_page_length' => 'Sauvegarder le nombre de lignes affichées',
            'is_public' => 'Partager le filtre avec les autres utilisateurs',
            'is_default' => 'Appliquer ce filtre par défaut',
        ],

        'export' => [
            'title' => 'Export',
            'description' => 'Vous pouvez exporter vos données en définissant différentes options.',
            'format' => [
                'xlsx' => 'Microsoft Excel (.xlsx)',
                'xls' => 'Microsoft Excel 97-2003 (.xls)',
                'ods' => 'ODF Spreadsheet (.ods)',
                'csv' => 'Fichier CSV (.csv)',
                'pdf' => 'Fichier PDF (.pdf)',
                'html' => 'Fichier HTML (.html)',
            ],
            'format_label' => 'Format',
            'keep_conditions' => 'Conserver les conditions de filtrage',
            'keep_sort' => 'Conserver l\'ordre de tri',
            'with_hidden_columns' => 'Exporter les colonnes cachées',
            'with_id' => 'Exporter l\'id des enregistrements',
            'with_timestamps' => 'Exporter les dates de création et de modification',
        ]
    ],

    // Widgets
    'summary' => [
        'no_widget' => 'Aucun widget pour le moment.',
    ],

    // Calendar
    'calendar' => [
        'apply' => 'Appliquer',
        'cancel' => 'Annuler',
        'clear' => 'Vider',
        'separator' => ' - ',
        'from' => 'De',
        'to' => 'À',
        'custom' => 'Personnalisé',
        'week' => 'Sem',
        'day' => [
            'mo' => 'Lu',
            'tu' => 'Ma',
            'we' => 'Me',
            'th' => 'Je',
            'fr' => 'Ve',
            'sa' => 'Sa',
            'su' => 'Di',
        ],
        'month' => [
            'january' => 'Janvier',
            'february' => 'Février',
            'march' => 'Mars',
            'april' => 'Avril',
            'may' => 'Mai',
            'june' => 'Juin',
            'july' => 'Juillet',
            'august' => 'Août',
            'september' => 'Septembre',
            'october' => 'Octobre',
            'november' => 'Novembre',
            'december' => 'Décembre',
        ],
    ],

    // Autocomplete
    'autocomplete' => [
        'currently_selected' => 'Sélection',
        'error' => 'Une erreur s\'est produite',
        'placeholder' => 'Rechercher',
        'status' => [
            'initialized' => 'Saisir du texte pour lancer la recherche',
            'no_results' => 'Aucun résultat',
            'searching' => 'Recherche en cours...',
            'too_short' => 'Saisir plus de caractères',
        ],
    ],
];