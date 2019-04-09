<?php

return [
    // Field
    'field' => [
        'name' => 'Identifiant',
        'email' => 'Adresse email',
        'identity' => 'Identifiant ou Email',
        'password' => 'Mot de passe',
        'password_confirmation' => 'Mot de passe (confirmation)',
    ],

    // Button
    'button' => [
        'signin' => 'Connexion',
        'signup' => 'Inscription',
        'password' => [
            'lost' => 'Mot de passe oublié ?',
            'send_link' => 'Envoyer le lien de réinitalisation',
            'reset' => 'Réinitialiser le mot de passe',
        ],
    ],

    // Info
    'info' => [
        'password' => [
            'reset' => 'Saisissez l\'adresse email que vous avez utilisée lors de l\'inscription.<br>Nous allons vous envoyer un email contenant un lien de réinitialisation de votre mot de passe.',
        ],
    ],

    // Error
    'error' => [
        'identity_required' => 'Identifiant ou Email obligatoire',
        'password_required' => 'Mot de passe obligatoire',
    ]
];