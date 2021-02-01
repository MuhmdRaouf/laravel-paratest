# Parallel Integration Tests in Laravel

[![Latest Version on Packagist](https://img.shields.io/packagist/v/muhmdraouf/laravel-paratest?style=flat-square&label=Packagist)](https://packagist.org/packages/muhmdraouf/laravel-paratest)
[![Build Status](https://img.shields.io/travis/com/muhmdraouf/laravel-paratest/master?style=flat-square&label=Build)](https://travis-ci.org/muhmdraouf/laravel-paratest)
[![Quality Score](https://img.shields.io/scrutinizer/build/g/MuhmdRaouf/laravel-paratest/master?style=flat-square&label=Code%20Quality)](https://scrutinizer-ci.com/g/muhmdraouf/laravel-paratest)
[![Total Downloads](https://img.shields.io/packagist/dt/muhmdraouf/laravel-paratest?style=flat-square&label=Downloads)](https://packagist.org/packages/muhmdraouf/laravel-paratest)

This package ships with some helper Artisan commands and testing traits to allow you running your Feature Tests in parallel using [Paratest](https://github.com/paratestphp/paratest) against a MySQL or PostgreSQL database without conflicts.

The package will create 1 database for each testing process you have running to avoid race conditions when your Feature Test try to run a test creating some fixtures while another test in a another process runs the `artisan migrate:fresh`.

You also don't have to worry about creating the test databases. They will be created when you run your tests. There's is even a helper runner to clean up the test databases afterwards.

## Installation

You can install the package via composer:

```bash
composer require muhmdraouf/laravel-paratest --dev
```

## Usage

**Attention: You will need a user with rights to create databases.**

Instead of using Laravel's _RefreshDatabase_ trait, use the package one:

```php
<?php

use MuhmdRaouf\LaravelParatest\Testing\RefreshDatabase;

class MyTest extends TestCase
{
    use RefreshDatabase;
}
```

Tip: to replace all existing usages of Laravel's RefreshDatabase trait with the package's, you can use the following command:

```bash
grep -rl 'Illuminate\\Foundation\\Testing\\RefreshDatabase' tests/ | xargs sed -i 's/Illuminate\\Foundation\\Testing\\RefreshDatabase/MuhmdRaouf\\LaravelParatest\\Testing\\RefreshDatabase/g'
```

You need to boot this setup trait in your base TestCase manually, because Laravel does not do it automatically:

```php
<?php

namespace Tests;

use MuhmdRaouf\LaravelParatest\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function setUpTraits()
    {
        $uses = parent::setUpTraits();
        if (isset($uses[RefreshDatabase::class])) {
            $this->refreshDatabase();
        }
        return $uses;
    }
}
```

You can keep running you tests with PHPUnit:

``` bash
phpunit
```

Or you can use Paratest:

``` bash
paratest
```

When using paratest, one database will be created for each process. If you want to clean up these databases at the end of the tests, use the runner provided. First, register the runner alias in your `composer.json` file, something like this:

```json
{
    "autoload-dev": {
        "psr-4": {
            "Tests\\": "tests/"
        },
        "files": [
            "vendor/muhmdraouf/laravel-paratest/src/ParatestLaravelRunner.php"
        ]
    }
}
```

Now, run `composer dump-autoload --optimize`, and then you can use the runner, like so:

```bash
paratest --runner ParatestLaravelRunner
```

This will clean up the test databases after your test finishes running.

This package also gives you the following Artisan commands:

- `php artisan db:create`
- `php artisan db:drop`
- `php artisan db:recreate`

**THESE COMMANDS RUN ONLY IN TESTING ENVIRONMENT.**

### Testing

``` bash
composer test
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email mohammed@raouf.me instead of using the issue tracker.

## Credits

- [Tony Messias](https://github.com/tonysm)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## Laravel Package Boilerplate

This package was generated using the [Laravel Package Boilerplate](https://laravelpackageboilerplate.com).
