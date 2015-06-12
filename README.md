# Defender

[Readme em Português](https://github.com/artesaos/defender/blob/master/README-pt_BR.md).

Defender is a Access Control List (ACL) Solution for Laravel 5.
With security and usability in mind, this project aims to provide you a safe way to control your application access without losing the fun of coding.

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

Using <a href="https://getcomposer.org/" target="_blank">composer</a>, execute the following command to automatically update your `composer.json`:

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

You need to update your application configuration in order to register the package, so it can be loaded by Laravel. Just update your `config/app.php` file adding the following code at the end of your `'providers'` section:

```php
// file START ommited
    'providers' => [
        // other providers ommited
        'Artesaos\Defender\Providers\DefenderServiceProvider',
    ],
// file END ommited
```

#### 2.1 Publishing configuration file and migrations

To publish the default configuration file and database migrations, execute the following command: 

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

If you already published defender files, but for some reason you want to override previous published files, add the `--force` flag.

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
If you have to control the access Defender provides middlewares to protect your routes.
If you have to control the access through the Laravel routes, Defender has some built-in middlewares for the trivial tasks. To use them, just put it in your `app/Http/Kernel.php` file.

```php
protected $routeMiddleware = [
    'auth'            => 'App\Http\Middleware\Authenticate',
    'auth.basic'      => 'Illuminate\Auth\Middleware\AuthenticateWithBasicAuth',
    'guest'           => 'App\Http\Middleware\RedirectIfAuthenticated',

    // Controle de acesso usando permissões
    'needsPermission' => 'Artesaos\Defender\Middlewares\NeedsPermissionMiddleware',

    // Controle de acesso mais simples, utiliza apenas os grupos
    'needsRole' => 'Artesaos\Defender\Middlewares\NeedsRoleMiddleware'
];
```

You'll see how to use the middlewares below.

#### 4.1 - Create your own middleware

If the built-in middlewares doesn't fit your needs, you can make your own by using [Defender's API](#usando-a-facade) to control the access. 

## Usage

Defender handles only access control. The authentication is still made by Laravel's `Auth`.

### Put a shield on my saber I must
On your User class, you need to add the trait `Artesaos\Defender\Traits\HasDefenderTrait` to enable the permission creation and roles creation for the users:

```php
<?php namespace App;

use Artesaos\Defender\Traits\HasDefenderTrait;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class User extends Model implements AuthenticatableContract, CanResetPasswordContract {

    use Authenticatable, CanResetPassword, HasDefenderTrait;
...
```
### Creating roles and permissions

To create roles and permissions for your application, just use the Defender's API. You can create a Laravel Seeder or use `php artisan tinker`.

```php

use App\User;

$grupoAdmin = Defender::createRole('admin');

// O primeiro parâmetro é o nome da permissão
// O segundo é a "versão amigável" desse nome. (geralmente para você mostrar ela na sua aplicação).
$permissaoCriarUsuario =  Defender::createPermission('user.create', 'Criar usuários');

// Aqui eu posso atribuir essa permissão diretamente para um usuário
$user = User::find(1);
$user->attachPermission($permissaoCriarUsuario);

// ou posso adicionar o usuário a um grupo e esse grupo tem a regra de poder criar usuários
$grupoAdmin->attachPermission($permissaoCriarUsuario);

//Agora esse usuário está no grupo dos Administradores 
$user->attachRole($grupoAdmin);
```

### Using the middleware

To protect your routes, you can use the built-in middlewares.

> Defender requires Laravel's Auth, so, use the `auth` middleware before the Defender's middleware that you intend to use.

#### Checking Permissions: needsPermissionMiddleware

```php
Route::get('foo', ['middleware' => ['auth', 'needsPermission'], 'can' => 'user.create', function()
{
    return 'Yes I can!';
}]);
```

If you're using Laravel 5.1 it's possible to use Middleware Parameters.

```php
Route::get('foo', ['middleware' => ['auth', 'needsPermission:user.index'], function() {
	return 'Yes I can!';
}]);
```

With this syntax it's also possible to use the middlewaren within your controllers.

```php
$this->middeware('needsPermission:user.index');
```

You can pass an array of permissions to check on.

```php
Route::get('foo', ['middleware' => ['auth', 'needsPermission'], 'can' => ['user.index', 'user.create'], function()
{
    return 'Yes I can!';
}]);
```

When using middleware parameters, use a `|` to separate multiple permissions.

```php
Route::get('foo', ['middleware' => ['auth', 'needsPermission:user.index|user.create'], function() {
	return 'Yes I can!';
}]);
```

Or within controllers:

```php
$this->middleware('needsPermission:user.index|user.create');
```

When you pass an array of permissions, the route will be fired only if the user has all the permissions. However, if you want to allow the access to the route when the user has at least one of the permissions, just add `'any' => true`.

```php
Route::get('foo', ['middleware' => ['auth', 'needsPermission'], 'can' => ['user.index', 'user.create'], 'any' => true, function()
{
    return 'Yes I can!';
}]);
```

Or, with middleware parameters, pass it as the 2nd parameter

```php
Route::get('foo', ['middleware' => ['auth', 'needsPermission:user.index|user.create,true'], function() {
	return 'Yes I can!';
}]);
```

Or within controllers:

```php
$this->middleware('needsPermission:user.index|user.create,true');
```

----------

#### Checking Roles: needsRoleMiddleware

This is similar to the previous middleware, but only the roles are checked, it means that it doesn't check the permissions.

```php
Route::get('foo', ['middleware' => ['auth', 'needsRole'], 'is' => 'admin', function()
{
    return 'Yes I am!';
}]);
```

If you're using Laravel 5.1 it's possible to use Middleware Parameters.

```php
Route::get('foo', ['middleware' => ['auth', 'needsRole:admin'], function() {
	return 'Yes I am!';
}]);
```

With this syntax it's also possible to use the middlewaren within your controllers.

```php
$this->middeware('needsRole:admin');
```

You can pass an array of permissions to check on.

```php
Route::get('foo', ['middleware' => ['auth', 'needsRole'], 'can' => ['admin', 'member'], function()
{
    return 'Yes I am!';
}]);
```

When using middleware parameters, use a `|` to separate multiple roles.

```php
Route::get('foo', ['middleware' => ['auth', 'needsRole:admin|editor'], function() {
	return 'Yes I am!';
}]);
```

Or within controllers:

```php
$this->middleware('needsRole:admin|editor');
```

When you pass an array of permissions, the route will be fired only if the user has all the permissions. However, if you want to allow the access to the route when the user has at least one of the permissions, just add `'any' => true`.

```php
Route::get('foo', ['middleware' => ['auth', 'needsRole'], 'is' => ['admin', 'member'], 'any' => true, function()
{
    return 'Yes I am!';
}]);
```

Or, with middleware parameters, pass it as the 2nd parameter

```php
Route::get('foo', ['middleware' => ['auth', 'needsRole:admin|editor,true'], function() {
	return 'Yes I am!';
}]);
```

Or within controllers:

```php
$this->middleware('needsRole:admin|editor,true');
```

----------

### Using in Views

Laravel's Blade extension for using Defender.

#### @can

```
@can('user.index')
    shows your protected stuff
@endcan
```

```
@can('user.index')
    shows your protected stuff
@else
    shows the data for those who doesn't have the user.index permission
@endcan
```

#### @is

```
@is('admin')
    Shows data for the logged user and that belongs to the admin role
@endis
```

```
@is('admin')
    Shows data for the logged user and that belongs to the admin role
@else 
    shows the data for those who doesn't have the admin permission
@endis
```

----------

### Using the Facade

With the Defender's Facade you can access the API and use it at any part of your application.

----------

##### `Defender::can($permission)`:

Check if the logged user has the `$permission`.

----------

##### `Defender::canWithRolePermissions($permission)`:

Check if the logged user has the `$permission` checking only the role permissions.

----------

##### `Defender::hasRole($roleName)`:

Check if the logged user belongs to the role `$roleName`.

----------

##### `Defender::roleExists($roleName)`:

Check if the role `$roleName` exists in the database.

----------

##### `Defender::permissionExists($permissionName)`:

Check if the permission `$permissionName` exists in the database.

----------

##### `Defender::findRole($roleName)`:

Find the role in the database by the name `$roleName`.

----------

##### `Defender::findRoleById($roleId)`:

Find the role in the database by the role ID `roleId`.

----------

##### `Defender::findPermission($permissionName)`:

Find the permission in the database by the name `$permissionName`.

----------

##### `Defender::findPermissionById($permissionId)`:

Find the permission in the database by the ID `$permissionId`.

----------

##### `Defender::createRole($roleName)`:

Create a new role in the database.

----------

##### `Defender::createPermission($permissionName)`:

Create a new permission in the database.

----------

### Using the trait

To add the Defender's features, you need to add the trait `HasDefenderTrait` in you User model (usually `App\User`).

```php
<?php namespace App;

// Declaração dos outros namespaces omitida
use Artesaos\Defender\Traits\HasDefenderTrait;

class User extends Model implements AuthenticatableContract, CanResetPasswordContract {

    use Authenticatable, CanResetPassword, HasDefenderTrait;

    // Restante da classe
}
```

This trait, beyond configuring the relationships, will add the following methods to your object `App\User`:

#####`public function can($permission)`:

This method checks if the logged user has the permission `$permission`  

In Defender, there are 2 kind of permissions: `User permissions` and `Role permissions`. By default, the permissions that the user inherits, are permissions of the roles that it belongs to. However, always that a user pemission is set, it will take precedence of role permission.

```php
public function foo(Authenticable $user)
{
    if ($user->can('user.create');
}
```

----------

##### `public function canWithRolePermissions($permission)`:

This method works the same way the previous one, the only diference is that the user permissions are not considered, however, only the role's permissions that the user belongs are used to check the access.

```php
public function foo(Authenticable $user)
{
    if ($user->canWithRolePermissions('user.create');
}
```

----------

##### `public function attachRole($role)`:

Attach the user to the role `$role`. The `$role` variable might be an object of the type `Artesaos\Defender\Role` or an array containing the `ids` of the roles.

```php
public function foo(Authenticable $user)
{
    $role = Defender::findRole('admin'); // Returns an Artesao\Defender\Role
    $user->attachRole($role);

    // or

    $roles = [1, 2, 3]; // Using an array of ids
    $user->attachRole($roles); 
}
```

----------


##### `public function detachRole($role)`:

Deatach the role `$role` from the user (inverse to `attachRole()`).

```php
public function foo(Authenticable $user)
{
    $role = Defender::findRole('admin'); // Returns an Artesao\Defender\Role
    $user->detachRole($role);

    // ou

    $roles = [1, 2, 3]; // Using an array of ids
    $user->detachRole($roles); 
}
```

----------

##### `public function syncRoles(array $roles = array())`:

This is like the `attachRole()` method, but only the roles in the array `$roles` will be on the relationship after the method runs. `$roles` it's an array of `ids` for the needed roles.

```php
public function foo(Authenticable $user)
{
    $roles = [1, 2, 3]; // Using an array of ids
    
    $user->syncRoles($roles); 
}
```

----------

##### `public function attachPermission($permission, array $options = array())`:

Attach the user to the permission `$permission`. The `$permission` variable is an instance of the `Artesaos\Defender\Permission` class.

```php
public function foo(Authenticable $user)
{
    $permission = Defender::findPermission('user.create');
    
    $user->attachPermission($permission, [
        'value' => true // true = has the permission, false = doesn't have the permission,
    ]); 
}
```

----------

##### `public function detachPermission($permission)`:

Remove the permission `$permission` from the user. The `$permission` variable might be an instance of the `Artesaos\Defender\Permission` class or an array of `ids` with the ids of the permissions to be removed.

```php
public function foo(Authenticable $user)
{
    $permission = Defender::findPermission('user.create');
    $user->detachPermission($permission);

    // or

    $permissions = [1, 3];
    $user->detachPermission($permissions);
}
```

----------

##### `public function syncPermissions(array $permissions)`:

This is like the method `syncRoles`. but only the roles in the array `$permissions` be on the relationship after the method runs.

```php
public function foo(Authenticable $user)
{
    $permissions = [
        1 => ['value' => false],
        2 => ['value' => true,
        3 => ['value' => true]
    ];
    
    $user->syncPermissions($permissions); 
}
```

----------

##### `public function revokePermissions()`:

Remove all the user permissions.

```php
public function foo(Authenticable $user)
{
    $user->revokePermissions(); 
}
```

----------

##### `public function revokeExpiredPermissions()`:

Remove all the temporary expired pemissions from the user. More about temporary permissions below.

```php
public function foo(Authenticable $user)
{
    $user->revokeExpiredPermissions(); 
}
```

----------

### Temporary permissions

One of the coolest Defender's features it's to add temporary permissions to a group or an user.

#### For example

> *The user John belongs to the role 'admins', however I want to temporaly remove the John's permission to create new users*

In this case we need to attach an permission with the value equal to `false`, explicitly prohibiting the user to perform that action. You must add this permission, with the `false` value, since by default, the user permissions are inherited of the permissions of their roles. When you assign a user permission, this will always take precedence.

For instance. Below we revoke the permission `user.create` for the user during 7 days.

```php
public function foo()
{
    $userX = App\User::find(3);
    $permission = Defender::findPermission('user.create');

    
    $userX->attachPermission($permission, [
        'value' => false, // false means that he will not have the permission,
        'expires' => \Carbon\Carbon::now()->addDays(7) // Daqui a quanto tempo essa permissão irá expirar
    ]);

}
```

After 7 days, the user will take the permission again.

----------

> *Allow that a user can perform some action by a period of time.*

To allow that a user have temporary access to perform a given action, just set the `expires` key. The `value` key will be `true` by default.

```php
public function foo()
{
    $user = App\User::find(1);
    $permission = Defender::findPermission('user.create');

    $user->attachPermission($permission, [
        'expires' => \Carbon\Carbon::now()->addDays(7)
    ];
}
```

It's also possible to extend an existing temporary:
Just use the `$user->extendPermission($permissionName, array $options)` method.
