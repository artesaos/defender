# Defender

Defender is a Access Control List (ACL) Solution for Laravel 5.
With Secutiry and Usability in mind, this project aims to provide you a safe way to control your application access without losing the fun of coding.

> Current Build Status

[![Build Status](https://travis-ci.org/artesaos/defender.svg?branch=develop)](https://travis-ci.org/artesaos/defender)
[![Code Climate](https://codeclimate.com/github/artesaos/defender/badges/gpa.svg)](https://codeclimate.com/github/artesaos/defender)

> Statistics

[![Latest Stable Version](https://poser.pugx.org/artesaos/defender/v/stable.svg)](https://packagist.org/packages/artesaos/defender)
[![Latest Unstable Version](https://poser.pugx.org/artesaos/defender/v/unstable.svg)](https://packagist.org/packages/artesaos/defender) [![License](https://poser.pugx.org/artesaos/defender/license.svg)](https://packagist.org/packages/artesaos/defender)
[![Total Downloads](https://poser.pugx.org/artesaos/defender/downloads.svg)](https://packagist.org/packages/artesaos/defender)
[![Monthly Downloads](https://poser.pugx.org/artesaos/defender/d/monthly.png)](https://packagist.org/packages/artesaos/defender)
[![Daily Downloads](https://poser.pugx.org/artesaos/defender/d/daily.png)](https://packagist.org/packages/artesaos/defender)

> Tips

<a href="http://zenhub.io" target="_blank"><img src="https://raw.githubusercontent.com/ZenHubIO/support/master/zenhub-badge.png" height="18px" alt="Powered by ZenHub"/></a>

## Installation

### 1. Dependency

Using composer, execute the following command to automatically update your `composer.json` :

```shell
composer require artesaos/defender
```

or manually update your `composer.json` file

```json
{
	"require": {
		"artesaos/defender": "dev-master"
	}
}
```

### 2. Provider

You need to update your application configuration in order to register the package so it can be loaded by Laravel, just update your `config/app.php` file adding the following code at the end of your `'providers'` section:

> `config/app.php`
```php
// file START ommited
    'providers' => [
        // other providers ommited
        'Artesaos\Defender\Providers\DefenderServiceProvider',
    ],
// file END ommited
```

#### 2.1 Publishing configuration file and migrations

To publish our default configuration file and database migrations, execute the following command: 
Execute the following command to publish Defender configuration file and migrations:

```shell
php artisan vendor:publish
```

You can also publish only the configuration file or the migrations:

```shell
php artisan vendor:publish --tag=config
```
Or
```shell
php artisan vendor:publish --tag=migrations
```

If you already published defender files, but for some reason your want to override previous published files, add the `--force` flag.

### 3. Facade (optional)
In order to use the `Defender` facade, you need to register it on the `config/app.php` file, you can do that the following way:

```php
// config.php file
// file START ommited
    'aliases' => [
        // other Facades ommited
        'Defender' => 'Artesaos\Defender\Facades\Defender',
    ],
// file END ommited
```

### 4. Defender Middlewares (optional)
Defender provides middlewares to protect your routes.

#### 4.1 - Create your own middleware

## Usage

You can find a detailed usage guide in our [wiki](http://linktothewiki).