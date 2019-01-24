# Installer Uccello

**Uccello** repose sur le Framework [Laravel](http://www.laravel.com) et peut être facilement ajouté à un projet Laravel existant. Il est ainsi possible d'ajouter très facilement un **back-office** complet permettant de gérer les données d'un site web. Ou alors vous préfèrerez peut-être développer une **application** de gestion ou un **CRM** sur mesure vous permettant d'améliorer la productivité de votre entreprise ? Uccello a été également conçu pour cela !

Il existe pour le moment **deux moyens** d'installer Uccello :

> **Pré-requis :** [Composer](https://getcomposer.org/) doit être installé sur le serveur web sur lequel se trouvera votre installation d'Uccello.

- [Installer une version pré-configurée d'Uccello](#installer-une-version-pré-configurée-duccello)
- [Ajouter Uccello à un projet Laravel](#ajouter-uccello-à-un-projet-laravel)



## Installer une version pré-configurée d'Uccello

### 1. Créer le projet

Il est possible de débuter un nouveau projet Uccello très simplement à partir de la commande suivante :

```bash
$ composer create-project --prefer-dist uccello/project NomDuProjet
```

> **Remarque** : Remplacez `NomDuProjet` par le nom de votre projet ou par `.` pour installer Uccello dans le répertoire courant.

Cette commande va clôner le [dépôt Git](https://github.com/uccellolabs/uccello-project) du projet et installer automatiquement toutes les dépendances nécessaires. Vous obiendrez ainsi un nouveau projet **Laravel** dans lequel **Uccello** a été pré-configuré.

### 2. Configurer l'environnement

Une fois le projet créé vous pouvez configurer le fichier `.env` comme expliqué dans la [documentation officielle](https://laravel.com/docs/5.7/database#configuration) de Laravel.

Voici un exemple de configuration :

```
APP_URL=http://uccello.local
...
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=uccello
DB_USERNAME=homestead
DB_PASSWORD=secret
```

Si vous ne voulez pas utiliser la notion de multi domaines vous pouvez ajouter le code suivant dans le fichier :

```
...
UCCELLO_MULTI_DOMAINS=false
```

> **Important** : N'oubliez pas de lancer la commande `php artisan laroute:generate` à chaque fois que vous changez la valeur de la variable `UCCELLO_MULTI_DOMAINS` ou que vous modifiez les routes de votre projet. Vous pourrez ainsi utiliser égelement les routes avec JavaScript.

### 3. Exécuter les migrations

Une fois la base de données configurée, il est maintenant possible d'exécuter les [migrations](https://laravel.com/docs/5.7/migrations) pour créer la structure de la base de données utilisée par **Uccello**.

```bash
$ php artisan migrate
```

> Pour avoir plus d'informations par rapport à la structure de la base de données, vous pouvez visiter la page [Structure de la base de données](../database/structure.md).

### 4. C'est prêt !

Une fois l'installation effectuée vous pouvez aller sur la **page d'accueil** de votre site, par exemple ```http://uccello.local```. Une fois redirigé sur la page de connexion, vous pouvez vous authentifier avec les identifiants suivants :

```
Identifiant: admin
Mot de passe : admin
```



## Ajouter Uccello à un projet Laravel

Il est tout à fait possible d'utiliser Uccello sur un projet **Laravel** existant.

> **Attention :** Étant donné qu'Uccello possède sa propre interface d'authentification et d'erreurs, pensez à faire une sauvegarde des fichiers suivants et à les réadapter si nécessaire :
>
> <u>Pour la mise en page générale</u>
>
> - resources/views/layouts/app.blade.php
>
> <u>Pour l'authentification</u>
>
> - resources/views/auth/login.blade.php
> - resources/views/auth/register.blade.php
> - resources/views/auth/passwords/email.blade.php
> - resources/views/auth/passwords/reset.blade.php
>
> <u>Pour la gestion des erreurs</u>
>
> - resources/views/errors/403.blade.php
> - resources/views/errors/404.blade.php
> - resources/views/errors/500.blade.php

### 1. Installer la librairie Uccello

Vous pouvez installer la dépendance `uccello/uccello` grâce à **Composer**.

```bash
$ composer require uccello/uccello:1.0.*
```

Si vous utilisez une version de Laravel **inférieure à 5.5**, il vous faudra ajouter le code suivant dans le fichier `config/app.php` :

```php
'providers' => [
  ...
  Uccello\Core\Providers\AppServiceProvider::class,
  Uccello\Core\Providers\RouteServiceProvider::class,
  ...
],
...
'aliases' => [
  ...
  'Uccello' => Uccello\Core\Facades\Uccello::class,
],
```

Dans tous les cas, il sera nécessaire d'ajouter le code suivant dans le fichier `config/app.php` afin d'ajouter la librairie [Laroute](https://github.com/aaronlord/laroute) :

```php
'providers' => [
  ...
  Lord\Laroute\LarouteServiceProvider::class,
  ...
],
```

Exécutez ensuite la commande suivante afin d'extraire tous les fichiers nécessaires au bon fonctionnement d'Uccello :

```bash
$ php artisan uccello:install
```

### 2. Ajouter les middlewares

Ouvrez le fichier `app/Http/Kernel.php` et ajoutez le code suivant afin d'ajouter les [middlewares](https://laravel.com/docs/5.7/middleware) utilisés par Uccello :

```php
protected $routeMiddleware = [
  ...
  'uccello.permissions' => \Uccello\Core\Http\Middleware\CheckPermissions::class,
  'uccello.settings' => \Uccello\Core\Http\Middleware\CheckSettingsPanel::class,
];
```

### 3. Configurer les routes

Ajoutez le code suivant dans le fichier `routes/web.php` afin de générer automatiquement la route pour la page d'accueil :

```
Route::get('/', function() {
    $domain = uccello()->useMultiDomains() ? uccello()->getLastOrDefaultDomain()->slug : null;
    $route = ucroute('uccello.home', $domain);
    return redirect($route);
});
...
```

Cela permet de détecter automatiquement si la notion de **multi domaines** est utilisée ou pas et de rediriger l'utilisateur sur le **dernier domaine** qu'il a visité.

### 4. Générer les routes pour JavaScript

Uccello utilise la librairie [Laroute](https://github.com/aaronlord/laroute) afin de pouvoir utiliser les routes avec JavaScript. Il est important **d'exécuter** cette commande **à chaque fois que les routes sont modifiées**.

```
$ php artisan laroute:generate
```

Cette commande va générer le fichier `public/js/laroute.js` utilisé par Uccello.

### 5. Configurer l'API

Uccello utilise la librairie [JWT Tokens](https://jwt.io/) pour sécuriser son API. Pour configurer JWT Token, éditez le fichier `config/auth.php` et modifiez la ligne suivante :

```php
'guards' => [
        ...

        'api' => [
            'driver' => 'jwt', // Remplacer 'token' par 'jwt'
            'provider' => 'users',
        ],
    ],
```

Pour générer la clé de sécurité utilisée par JWT Tokens, exécutez la commande suivante :

```bash
$ php artisan jwt:secret
```

### 6. Configurer l'environnement

Une fois le projet créé vous pouvez configurer le fichier `.env` comme expliqué dans la [documentation officielle](https://laravel.com/docs/5.7/database#configuration) de Laravel.

Voici un exemple de configuration :

```
...
APP_URL=http://uccello.local
...
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=uccello
DB_USERNAME=homestead
DB_PASSWORD=secret
```

Si vous ne voulez pas utiliser la notion de multi domaines vous pouvez ajouter le code suivant dans le fichier :

```
...
UCCELLO_MULTI_DOMAINS=false
```

> **Important** : N'oubliez pas de lancer la commande `php artisan laroute:generate` à chaque fois que vous changez la valeur de la variable `UCCELLO_MULTI_DOMAINS`.

### 7. Exécuter les migrations

Une fois la base de données configurée, il est maintenant possible d'exécuter les [migrations](https://laravel.com/docs/5.7/migrations) pour créer la structure de la base de données utilisée par Uccello.

```bash
$ php artisan migrate
```

Pour avoir plus d'informations par rapport à la structure de la base de données utilisée par Uccello, vous pouvez visiter la page [Structure de la base de données](../database/structure.md).

### 8. C'est prêt !

Une fois l'installation effectuée vous pouvez aller sur la **page d'accueil** de votre site, par exemple ```http://uccello.local```. Une fois redirigé sur la page de connexion, vous pouvez vous authentifier avec les identifiants suivants :

```
Identifiant: admin
Mot de passe : admin
```