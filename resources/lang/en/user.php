<?php

return [
    'user' => 'Users',
    'user_single' => 'User',
    'block' => [
        'auth' => 'Authentication',
        'contact' => 'Contact',
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
    ],
    'label' => [
        'no_role' => 'No related role',
        'my_account' => 'My account',
        'avatar_type' => 'Avatar type',
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
];