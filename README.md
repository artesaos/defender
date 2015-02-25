# Guardian

Guardian is a Access Control List (ACL) Solution for Laravel 5.
With Secutiry and Usability in mind, this project aims to provide you a safe way to control your application access without losing the fun of coding.

> Current Build Status

[![Build Status](https://travis-ci.org/artesaos/guardian.svg?branch=develop)](https://travis-ci.org/artesaos/guardian)
[![Code Climate](https://codeclimate.com/github/artesaos/guardian/badges/gpa.svg)](https://codeclimate.com/github/artesaos/guardian)

> Statistics

[![Powered by ZenHub](https://raw.githubusercontent.com/ZenHubIO/support/master/zenhub-badge.png | height = 18px)](http://zenhub.io)
[![Latest Stable Version](https://poser.pugx.org/artesaos/guardian/v/stable.svg)](https://packagist.org/packages/artesaos/guardian)
[![Latest Unstable Version](https://poser.pugx.org/artesaos/guardian/v/unstable.svg)](https://packagist.org/packages/artesaos/guardian) [![License](https://poser.pugx.org/artesaos/guardian/license.svg)](https://packagist.org/packages/artesaos/guardian)
[![Total Downloads](https://poser.pugx.org/artesaos/guardian/downloads.svg)](https://packagist.org/packages/artesaos/guardian)
[![Monthly Downloads](https://poser.pugx.org/artesaos/guardian/d/monthly.png)](https://packagist.org/packages/artesaos/guardian)
[![Daily Downloads](https://poser.pugx.org/artesaos/guardian/d/daily.png)](https://packagist.org/packages/artesaos/guardian)

### Install

### 1 - Dependency
The first step is using composer to install the package and automatically update your `composer.json` file, you can do this by running:
```shell
composer require artesaos/guardian
```

### 2 - Provider
You need to update your application configuration in order to register the package so it can be loaded by Laravel, just update your `config/app.php` file adding the following code at the end of your `'providers'` section:

> `config/app.php`
```php
// file START ommited
    'providers' => [
        // other providers ommited
        'Artesaos\Guardian\Providers\GuardianServiceProvider',
    ],
// file END ommited
```

### 3 - Facade (optional)
In order to use the `Guardian` facade, you need to register it on the `config/app.php` file, you can do that the following way:

> `config/app.php`
```php
// file START ommited
    'aliases' => [
        // other Facades ommited
        'Guardian'  => 'Artesaos\Guardian\Facades\Guardian',
    ],
// file END ommited
```

### 4 - Guardian Middlewares (optional)
Guardian provides middlewares to protect your routes.

#### 4.1 - Create your own middleware

### Usage
@todo
