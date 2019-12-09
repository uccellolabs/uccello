<?php

return [
    'user' => 'Utilisateurs',
    'user_single' => 'Utilisateur',
    'block' => [
        'general' => 'Informations générales',
        'roles' => 'Rôles',
    ],
    'field' => [
        'username' => 'Identifiant',
        'name' => 'Nom',
        'is_admin' => 'Est admin ?',
        'password' => 'Mot de passe',
        'password_confirmation' => 'Mot de passe (confirmation)',
        'email' => 'Email',
        'current_password' => 'Mot de passe actuel',
        'new_password' => 'Nouveau mot de passe',
        'new_password_confirm' => 'Nouveau mot de passe (confirmation)',
        'roles' => 'Rôles dans le domaine courant',
    ],
    'relatedlist' => [
        'groups' => 'Groupes',
    ],
    'label' => [
        'no_role' => 'Aucun rôle lié',
        'my_account' => 'Mon compte',
        'avatar_type' => 'Type d\'avatar',
        'role_in_current_domain' => 'Rôles définis dans le domaine courant',
        'role_in_ancestors_domains' => 'Rôles définis dans des domaines ancêtres',
    ],
    'account' => [
        'profile' => 'Profil',
        'avatar' => 'Avatar',
        'password' => 'Mot de passe',
        'upload_image' => 'Transférer une image',
        'avatar_type' => [
            'initials' => 'Initiales',
            'gravatar' => 'Gravatar',
            'image' => 'Image',
        ],
        'avatar_description' => [
            'initials' => 'Un avatar contenant des initiales sera généré à partir du nom de l\'utilisateur.',
            'gravatar' => 'Vous pouvez aller sur :url pour configurer votre avatar. Il sera associé à votre adresse email.',
            'image' => 'Transférez une image depuis votre ordinateur.',
        ],
    ],
    'error' => [
        'current_password' => 'Le mot de passe actuel n\'est pas correct.',
    ],
    'success' => [
        'profile_updated' => 'Le profil a bien été mis à jour !',
        'avatar_updated' => 'L\'avatar a bien été mis à jour !',
        'password_updated' => 'Le mot de passe a bien été mis à jour !'
    ],
    'button' => [
        'import_user' => 'Importer',
    ],
    'modal' => [
        'import_user' => [
            'title' => 'Importer un utilisateur',
            'description' => 'Vous pouvez importer un utilisateur qui a été créé dans un autre domaine, et lui affecter des rôles dans le domaine courant.',
            'name' => 'Identifiant, email ou nom de l\'utilisateur',
        ],
    ],
];