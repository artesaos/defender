# Defender

Defender um package ACL para Laravel 5 que utiliza grupos e permissões.
Com Segurança e Usabilidade em mente, este projeto tem como objetivo prover o controle de acesso da sua aplicação.

> Estado Atual do Package

[![Build Status](https://travis-ci.org/artesaos/defender.svg?branch=develop)](https://travis-ci.org/artesaos/defender)
[![Code Climate](https://codeclimate.com/github/artesaos/defender/badges/gpa.svg)](https://codeclimate.com/github/artesaos/defender)

> Estatísticas

[![Latest Stable Version](https://poser.pugx.org/artesaos/defender/v/stable.svg)](https://packagist.org/packages/artesaos/defender)
[![Latest Unstable Version](https://poser.pugx.org/artesaos/defender/v/unstable.svg)](https://packagist.org/packages/artesaos/defender) [![License](https://poser.pugx.org/artesaos/defender/license.svg)](https://packagist.org/packages/artesaos/defender)
[![Total Downloads](https://poser.pugx.org/artesaos/defender/downloads.svg)](https://packagist.org/packages/artesaos/defender)
[![Monthly Downloads](https://poser.pugx.org/artesaos/defender/d/monthly.png)](https://packagist.org/packages/artesaos/defender)
[![Daily Downloads](https://poser.pugx.org/artesaos/defender/d/daily.png)](https://packagist.org/packages/artesaos/defender)

> Dicas

<a href="http://zenhub.io" target="_blank"><img src="https://raw.githubusercontent.com/ZenHubIO/support/master/zenhub-badge.png" height="18px" alt="Powered by ZenHub"/></a>

## Instalação

### 1. Dependência

Defender pode ser instalado através do composer. Para que o package seja adicionado automaticamente ao seu arquivo `composer.json` execute o seguinte comando:

```shell
composer require artesaos/defender
```

ou se preferir, adicione o seguinte trecho manualmente:

```json
{
	"require": {
		"artesaos/defender": "0.2.x"
	}
}
```

### 2. Provider

Para usar o Defender em sua aplicação Laravel, é necessário registrar o package no seu arquivo `config/app.php`. Adicione o seguinte código na no fim da seção `providers`

```php
// file START ommited
    'providers' => [
        // other providers ommited
        'Artesaos\Defender\Providers\DefenderServiceProvider',
    ],
// file END ommited
```

#### 2.1 Publicando o arquivo de configuração e as migrations

Para publicar o arquivo de configuração padrão e as migrations que acompanham o package execute o seguinte comando:

```shell
php artisan vendor:publish
```

Você também pode publicar separadamente utilizando a flag `--tag`

```shell
php artisan vendor:publish --tag=config
```

Ou

```shell
php artisan vendor:publish --tag=migrations
```

Se você já publicou os arquivos, mas por algum motivo precisa sobrescrevê-los, adicione a flag `--force` no final dos comandos antetiores.

### 3. Facade (optional)

Para usar a facade `Defender`, você precisa registrá-la no seu arquivo `config/app.php` adicionando o seguinte código na seção `aliases`:

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

Caso você tenha a necessidade realizar o controle de acesso diretamente nas rotas, o Defender vem "de fábrica" com alguns middlewares que abordam os casos mais comuns. Para utilizá-los é necessário registrá-los no seu arquivo `app/Http/Kernel.php`.

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

A utilização desses middlewares é explicada na próxima seção.

#### 4.1 - Create your own middleware

Caso os middlewares padrões do Defender não atendam as suas necessidades, você pode criar seu próprio middleware e utilizar a API do Defender para realizar o controle de acesso. 

//TODO: Link para api

## Usando o Defender

O Defender realiza apenas o controle de acesso em sua aplicação, ou seja, a tarefa de autenticação é realizada pelo `Auth` que faz parte do core do Laravel.

### Usando o Middleware

Para proteger suas rotas, você pode utilizar os middlewares padrões do Defender.

#### needsPermissionMiddleware

```php
Route::get('foo', ['middleware' => 'needsPermission', 'can' => 'user.create', function()
{
	return 'Yes we can!";
}]);
```

Você também pode passar um array de permissões a serem checadas.

```php
Route::get('foo', ['middleware' => 'needsPermission', 'can' => ['user.index', 'user.create'], function()
{
	return 'Yes we can!';
}]);
```

Quando você passa um array de permissões, a rota é executada apenas se o usuário possui todas as permissões. Caso você queira que a rota execute quando o usuário tem pelo menos uma das permissões, basta adicionar `'any' => true`.

```php
Route::get('foo', ['middleware' => 'needsPermission', 'can' => ['user.index', 'user.create'], 'any' => true, function()
{
	return 'Yes we can!';
}]);
```

----------

#### needsRoleMiddleware

Funciona de maneira semelhante ao middleware anterior, porém apenas os grupos são verificados, ou seja, não leva em consideração as permissões.

```php
Route::get('foo', ['middleware' => 'needsPermission', 'is' => 'admin', function()
{
	return 'Yes I am!";
}]);
```

Você também pode passar um array de permissões a serem checadas.

```php
Route::get('foo', ['middleware' => 'needsPermission', 'can' => ['admin', 'member'], function()
{
	return 'Yes I am!';
}]);
```

Quando você passa um array de permissões, a rota é executada apenas se o usuário possui todas as permissões. Caso você queira que a rota execute quando o usuário tem pelo menos uma das permissões, basta adicionar `'any' => true`.

```php
Route::get('foo', ['middleware' => 'needsPermission', 'is' => ['admin', 'member'], 'any' => true, function()
{
	return 'Yes I am!';
}]);
```

----------

### Usando a Facade

##### `Defender::can($permission)`:

----------

##### `Defender::hasRole($roleName)`:

----------

##### `Defender::roleExists($roleName)`:

----------

##### `Defender::permissionExists($permissionName)`:

----------

##### `Defender::findRole($roleName)`:

----------

##### `Defender::findRoleById($roleId)`:

----------

##### `Defender::findPermission($permissionName)`:

----------

##### `Defender::findPermissionById($permissionId)`:

----------

##### `Defender::createRole($roleName)`:

----------

##### `Defender::createPermission($permissionName)`:

----------

### Usando a trait

Para adicionar as funcionalidades do Defender, é necessário adicionar trait `HasDefenderTrait`no seu modelo de usuário (normalmente o `App\User`).

```php
<?php namespace App;

// Declaração dos outros namespaces omitida
use Artesaos\Defender\HasDefenderTrait;

class User extends Model implements AuthenticatableContract, CanResetPasswordContract {

	use Authenticatable, CanResetPassword, HasDefenderTrait;

    // Restante da clase
}
```

Esta trait, além de configurar os relacionamentos, adicionará os seguintes métodos no seu object `App\User`:

#####`public function can($permission)`:

Este método verificar se o usuário logado no sistema possui a permissão `$permission`  

No Defender existem 2 tipos de permissões: `Permissões de Usuário` e `Permissões de Grupo`. Por padrão as permissões o usuário herda as permissões dos grupos que ele pertence. Porém, sempre que uma permissão de usuário for definida, ela terá precedência sobre a permissão de grupo.

```php
public function foo(Authenticable $user)
{
    if ($user->can('user.create');
}
```

----------

##### `public function canWithRolePermissions($permission)`:

Este método funciona praticamente da mesma forma que o método anterior, a única diferença é que as permissões de usuário não são consideradas, ou seja, apenas as permissões dos grupos que usuário pertence são usadas na hora de verificar a permissão.

```php
public function foo(Authenticable $user)
{
    if ($user->canWithRolePermissions('user.create');
}
```

----------

##### `public function attachRole($role)`:

Adiciona o usuário no grupo `$role`. A variável `$role` pode ser um objeto do tipo `Artesaos\Defender\Role` ou um array de com os `ids` dos grupos.

```php
public function foo(Authenticable $user)
{
    $role = Defender::findRole('admin'); // Retorna um Artesao\Defender\Role
	$user->attachRole($role);

    // ou

    $roles = [1, 2, 3]; // Usando array de ids
    $user->attachRole($roles); 
}
```

----------


##### `public function attachRole($role)`:

Remove o grupo `$role` do usuário (método inverso ao `attachRole()`).

```php
public function foo(Authenticable $user)
{
    $role = Defender::findRole('admin'); // Retorna um Artesao\Defender\Role
	$user->detachRole($role);

    // ou

    $roles = [1, 2, 3]; // Usando array de ids
    $user->detachRole($roles); 
}
```

----------

##### `public function syncRoles(array $roles = array())`:

Semelhante ao `attachRole()`, porém apenas os grupos presentes no array `$roles` estarão presentes no relacionamento após a execução desde método. `$roles` é um array de `ids` dos grupos desejados.

```php
public function foo(Authenticable $user)
{
    $roles = [1, 2, 3]; // Usando array de ids
    
    $user->syncRoles($roles); 
}
```

----------

##### `public function attachPermission($permission, array $options = array())`:

Vincula o usuário a permissão `$permission`. A variável `$permission` é ums instância da classe `Artesaos\Defender\Permission`.

```php
public function foo(Authenticable $user)
{
    $permission = Defender::findPermission('user.create');
    
    $user->attachPermission($permission, [
	    'value' => true // true = tem a permissão, false = não tem a permissão,
	]); 
}
```

----------

##### `public function detachPermission($permission)`:

Remove a permissão `$permission` do usuário. A variável `$permission` pode ser uma instância da classe `Artesaos\Defender\Permission` ou um array de `ids` com os ids das permissões a serem removidas.

```php
public function foo(Authenticable $user)
{
    $permission = Defender::findPermission('user.create');
    $user->detachPermission($permission);

	// ou

	$permissions = [1, 3];
	$user->detachPermission($permissions);
}
```

----------

##### `public function syncPermissions(array $permissions)`:

Semelhante ao método `syncRoles`. Apenas as permissões presentes no array `$permissions` farão parte do relacionamente após a execução desde método.

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

Remove todas as permissões de usuário do usuário.

```php
public function foo(Authenticable $user)
{
    $user->revokePermissions(); 
}
```

----------

##### `public function revokeExpiredPermissions()`:

Remove todas as permissões temporárias expiradas do usuário. Veja mais a respeito de permissões temporárias na próxima seção.

```php
public function foo(Authenticable $user)
{
    $user->revokeExpiredPermissions(); 
}
```

### Permissões Temporárias

// TODO