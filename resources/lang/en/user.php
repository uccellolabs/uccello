<?php

return [
    'user' => 'Users',
    'user_single' => 'User',
    'block' => [
        'general' => 'General information',
        'roles' => 'Roles',
    ],
    'field' => [
        'username' => 'Username',
        'name' => 'Name',
        'is_admin' => 'Is admin?',
        'password' => 'Password',
        'password_confirmation' => 'Password (confirmation)',
        'email' => 'Email',
        'current_password' => 'Current password',
        'new_password' => 'New password',
        'new_password_confirm' => 'New password (confirmation)',
        'roles' => 'Roles in the current domain',
    ],
    'relatedlist' => [
        'groups' => 'Groups',
        'connection_log' => 'Connection log',
    ],
    'label' => [
        'no_role' => 'No related role',
        'my_account' => 'My account',
        'avatar_type' => 'Avatar type',
        'defined_in' => 'Defined in',
        'no_connections' => 'No connections',
        'total_connections' => 'Total connections:',
        'first_connection' => 'First connection:',
        'last_connections' => 'Last connections:',
    ],
    'account' => [
        'profile' => 'Profile',
        'avatar' => 'Avatar',
        'password' => 'Password',
        'upload_image' => 'Upload an image',
        'avatar_type' => [
            'initials' => 'Initials',
            'gravatar' => 'Gravatar',
            'image' => 'Image',
        ],
        'avatar_description' => [
            'initials' => 'An avatar containing initials will be generated from the user name.',
            'gravatar' => 'Go to :url to configure your avatar. It will be associated to your email address.',
            'image' => 'Upload an image from your computer.',
        ],
    ],
    'error' => [
        'current_password' => 'The current password is not correct.',
    ],
    'success' => [
        'profile_updated' => 'The profile has been updated!',
        'avatar_updated' => 'The avatar has been updated!',
        'password_updated' => 'The password has been updated!'
    ],
    'button' => [
        'import_user' => 'Import',
    ],
    'modal' => [
        'import_user' => [
            'title' => 'Import user',
            'description' => 'You can import a user who has been created in another domain, and assign roles to him in the current domain.',
            'name' => 'User\'s login, email or name',
        ],
    ],
];
