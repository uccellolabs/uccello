# uccello

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-travis]][link-travis]
[![Coverage Status][ico-scrutinizer]][link-scrutinizer]
[![Quality Score][ico-code-quality]][link-code-quality]
[![Total Downloads][ico-downloads]][link-downloads]

Laravel Package for providing an advanced CRUD interface. For the moment, this package can be integrated easily only on a fresh installation. Soon it will be possible to install it alson on an existing one.

## Installation

Via Composer

``` bash
$ composer require uccello/uccello
```

If you are using Laravel 5.5 or above skip this step, but if aren't then add this code on ```config/app.php```, on providers

``` php
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

And then run,

``` bash
$ php artisan make:uccello
```

This command will extract needed views for **auth**, and **errors**.

### Add check permissions middleware
Open ```app/Http/Kernel.php``` file and add the following code:

```php
protected $routeMiddleware = [
  ...
  'uccello.permissions' => \Uccello\Core\Http\Middleware\CheckPermissions::class,
];
```

### Migrate and seed the database
Configure ```.env``` file then run this command to migrate the database

```bash
 php artisan migrate
```


### Set the default routes
Add this code in ```routes/web.php```

``` php
Route::get('/', function() {
    $domain = uccello()->useDomains() ? 'default' : null;
    $route = ucroute('uccello.home', $domain);
    return redirect($route);
});
...
```

If you don't want to use multi domains, add this code in ```.env```

```
...
UCCELLO_MULTI_DOMAINS=false
```

Then go to your **homepage**. You must be redirected to the **login page**.
You can easily **sign up** to create a new account or **sign in** with the following credentials

```
Login: admin@uccello.io
Password: admin
```

## Testing

``` bash
$ composer test
```

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) and [CODE_OF_CONDUCT](CODE_OF_CONDUCT.md) for details.

## Security

If you discover any security related issues, please email sardoj@gmail.com instead of using the issue tracker.

## Credits

- [Jonathan SARDO][link-author]
- [All Contributors][link-contributors]

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/uccello/uccello.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/uccello-io/uccello/master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/uccello-io/uccello.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/uccello-io/uccello.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/uccello/uccello.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/uccello/uccello
[link-travis]: https://travis-ci.org/uccello-io/uccello
[link-scrutinizer]: https://scrutinizer-ci.com/g/uccello-io/uccello/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/uccello-io/uccello
[link-downloads]: https://packagist.org/packages/uccello/uccello
[link-author]: https://github.com/sardoj
[link-contributors]: ../../contributors
