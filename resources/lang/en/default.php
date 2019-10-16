<?php

return [
    // General
    'yes' => 'Yes',
    'no' => 'No',
    'me' => 'Me',

    // Menu
    'menu' => [
        'title' => 'Menu',
        'return' => 'Return',
        'admin' => 'Admin',
        'dashboard' => 'Dashboard',
        'security' => 'Security',
        'settings' => 'Settings',
    ],

    // Domain
    'domain' => [
        'search' => 'Search a domain',
    ],

    // Breadcrumb
    'breadcrumb' => [
        'create' => 'Create',
        'edit' => 'Edit',
        'admin' => 'Administration',
    ],

    // Button
    'button' => [
        'return' => 'Return',
        'save' => 'Save',
        'save_new' => 'Save and new',
        'new' => 'New',
        'edit' => 'Edit',
        'delete' => 'Delete',
        'clear_search' => 'Clear',
        'manage_filters' => 'Manage filters',
        'add_filter' => 'Add a filter',
        'delete_filter' => 'Delete filter',
        'action' => 'Actions',
        'yes' => 'Yes',
        'no' => 'No',
        'cancel' => 'Cancel',
        'add_widget' => 'Add widget',
        'export' => 'Export',
        'columns' => 'Columns',
        'download_file' => 'Download',
        'logout' => 'Logout',
        'delete_related_record' => 'Delete relation',
        'user_account' => 'My account',
        'see_descendants_records' => 'Descendant view',
    ],

    // Block
    'block' => [
        'general' => 'General information',
    ],

    // Field
    'field' => [
        'id' => 'Id',
        'domain' => 'Domain',
        'created_at' => 'Created at',
        'updated_at' => 'Updated at',
        'select_empty_value' => '- Select a value -',
        'info' => [
            'new_line' => 'Press ENTER to create a new line.',
        ],
    ],

    // Filter
    'filter' => [
        'all' => 'All',
        'show_n_records' => ':number lines',
        'delete' => [
            'message' => 'Delete this filter?',
        ],
        'exists' => [
            'title' => 'A filter already exists with the same name',
            'message' => 'VDo you want to update it?',
        ],
        'search_flag' => [
            'empty' => '- Empty -',
            'not_empty' => '- Not Empty -',
        ]
    ],

    // Tabs
    'tab' => [
        'main' => 'Details',
        'summary' => 'Summary',
    ],

    // Datatable
    'datatable' => [
        'search' => 'Search',
        'no_results' => 'No results',
        'loading' => 'Loading...',
    ],

    // Related list
    'relatedlist' => [
        'button' => [
            'add' => 'Add',
            'select' => 'Select',
            'columns' => 'Columns',
            'lines' => 'Lines',
        ],
    ],

    // Confirm
    'confirm' => [
        'dialog' => [
            'title' => 'Are you sure?',
        ],
        'button' => [
            'delete_record' => 'Delete this record?',
            'delete_relation' => 'Delete this relation?',
        ],
    ],

    // Notification
    'notification' => [
        'record' => [
            'created' => 'The record has been created.',
            'deleted' => 'The record has been deleted.',
        ],
        'relation' => [
            'deleted' => 'The relation has been deleted',
        ],
        'form' => [
            'not_valid' => 'Some fields are not valid!',
        ],
    ],

    // Dialog
    'dialog' => [
        'error' => [
            'title' => 'Error',
            'message' => 'An error occured',
        ],
        'success' => [
            'title' => 'Success',
        ],
    ],

    // Errors
    'error' => [
        'field' => [
            'mandatory' => 'Some mandatory fields are not defined.',
        ],
        'filter' => [
            'not_found' => 'The filter does not exist.',
            'read_only' => 'The filter is read-only',
        ],
    ],

    // Success
    'success' => [
        'filter' => [
            'deleted' => 'The filter has been deleted.',
        ]
    ],

    // Modal
    'modal' => [
        'add_filter' => [
            'title' => 'Add a filter',
            'description' => 'You can create a new filter from the configuration of the current list.',
            'name' => 'Filter name',
            'save_columns' => 'Save displayed columns',
            'save_conditions' => 'Save search conditions',
            'save_order' => 'Save sort order',
            'save_page_length' => 'Save the number of rows displayed',
            'is_public' => 'Share the filter with other users',
            'is_default' => 'Apply this filter by default',
        ],

        'export' => [
            'title' => 'Export',
            'description' => 'You can export your data by define several options.',
            'format' => [
                'xlsx' => 'Microsoft Excel (.xlsx)',
                'xls' => 'Microsoft Excel 97-2003 (.xls)',
                'ods' => 'ODF Spreadsheet (.ods)',
                'csv' => 'CSV Document (.csv)',
                'pdf' => 'PDF Document (.pdf)',
                'html' => 'HTML Document (.html)',
            ],
            'format_label' => 'Format',
            'keep_conditions' => 'Keep the filter conditions',
            'keep_sort' => 'Keep the sort order',
            'with_hidden_columns' => 'Export hidden columns',
            'with_id' => 'Export record id',
            'with_timestamps' => 'Export create and update dates',
        ],

        'domains' => [
            'title' => 'All domains',
        ],
    ],

    // Widgets
    'summary' => [
        'no_widget' => 'No widgets for the moment.',
    ],

    // Calendar
    'calendar' => [
        'apply' => 'Apply',
        'cancel' => 'Cancel',
        'clear' => 'Clear',
        'separator' => ' - ',
        'from' => 'From',
        'to' => 'To',
        'custom' => 'Custom',
        'week' => 'W',
        'day' => [
            'mo' =>  'Mo',
            'tu' =>  'Tu',
            'we' =>  'We',
            'th' =>  'Th',
            'fr' =>  'Fr',
            'sa' =>  'Sa',
            'su' =>  'Su',
        ],
        'month' => [
            'january' =>  'January',
            'february' =>  'February',
            'march' =>  'March',
            'april' =>  'April',
            'may' =>  'May',
            'june' =>  'June',
            'july' =>  'July',
            'august' =>  'August',
            'september' =>  'September',
            'october' =>  'October',
            'november' =>  'November',
            'december' =>  'December',
        ],
        'ranges' => [
            'today' => 'Today',
            'month' => 'This month',
            'last_month' => 'Last month',
            'next_month' => 'Next month',
            'quarter' => 'This quarter',
            'last_quarter' => 'Last quarter',
            'next_quarter' => 'Next quarter',
            'year' => 'This year',
            'last_year' => 'Last year',
            'next_year' => 'Next year',
        ],
    ],

    // Autocomplete
    'autocomplete' => [
        'currently_selected' => 'Selection',
        'error' => 'An error occurred',
        'placeholder' => 'Search',
        'status' => [
            'initialized' => 'Start to type',
            'no_results' => 'No results',
            'searching' => 'Searching...',
            'too_short' => 'Enter more characters',
        ],
    ],
];