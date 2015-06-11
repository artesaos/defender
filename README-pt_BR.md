# Defender

[Readme on English](https://github.com/artesaos/defender/blob/master/README.md).

Defender é um package ACL para Laravel 5 que utiliza grupos e permissões.
Com Segurança e Usabilidade em mente, este projeto tem como objetivo prover o controle de acesso da sua aplicação.

> Estado Atual do Package

[![Build Status](https://travis-ci.org/artesaos/defender.svg?branch=develop)](https://travis-ci.org/artesaos/defender)
[![Code Climate](https://codeclimate.com/github/artesaos/defender/badges/gpa.svg)](https://codeclimate.com/github/artesaos/defender)

> Estatísticas

[![Latest Stable Version](https://poser.pugx.org/artesaos/defender/v/stable.svg)](https://packagist.org/packages/artesaos/defender)
[![Latest Unstable Version](https://poser.pugx.org/artesaos/defender/v/unstable.svg)](https://packagist.org/packages/artesaos/defender) [![License](https://poser.pugx.org/artesaos/defender/license.svg)](https://packagist.org/packages/artesaos/defender)
[![Total Downloads](https://poser.pugx.org/artesaos/defender/downloads.svg)](https://packagist.org/packages/artesaos/defender)
[![Monthly Downloads](https://poser.pugx.org/artesaos/defender/d/monthly.png)](https://packagist.org/packages/artesaos/defender)
[![Daily Downloads](https://poser.pugx.org/artesaos/defender/d/daily.png)](https://packagist.org/packages/artesaos/defender) [![Slack Laravel Brasil](http://laravelbrasil.vluzrmos.com.br/badge.svg)](http://laravelbrasil.vluzrmos.com.br)

> Dicas

<a href="http://zenhub.io" target="_blank"><img src="https://raw.githubusercontent.com/ZenHubIO/support/master/zenhub-badge.png" height="18px" alt="Powered by ZenHub"/></a>

## Instalação

### 1. Dependência

Defender pode ser instalado através do <a href="https://getcomposer.org/" target="_blank">composer</a>.
Para que o package seja adicionado automaticamente ao seu arquivo `composer.json` execute o seguinte comando:

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

Para usar o Defender em sua aplicação Laravel, é necessário registrar o package no seu arquivo `config/app.php`. Adicione o seguinte código no fim da seção `providers`

```php
// file START ommited
    'providers' => [
        // other providers ommited
        'Artesaos\Defender\Providers\DefenderServiceProvider',
    ],
// file END ommited
```

#### 2.1 Publicando o arquivo de configuração e as migrations

Para publicar o arquivo de configuração padrão e as migrations que acompanham o package, execute o seguinte comando:

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

Se você já publicou os arquivos, mas por algum motivo precisa sobrescrevê-los, adicione a flag `--force` no final dos comandos anteriores.

### 3. Facade (opcional)

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

### 4. Middlewares do Defender

Caso você tenha a necessidade de realizar o controle de acesso diretamente nas rotas, o Defender possui alguns middlewares (nativos) que abordam os casos mais comuns. Para utilizá-los é necessário registrá-los no seu arquivo `app/Http/Kernel.php`.

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

#### 4.1 - Crie o seu próprio middleware

Caso os middlewares padrões do Defender não atendam as suas necessidades, você pode criar seu próprio middleware e utilizar a [API do Defender](#usando-a-facade) para realizar o controle de acesso. 

## Usando o Defender

O Defender realiza apenas o controle de acesso em sua aplicação, ou seja, a tarefa de autenticação é realizada pelo `Auth` que faz parte do core do Laravel.

### Tornando o User denfensível
Na sua classe User, você precisa adicionar a trait `Artesaos\Defender\HasDefenderTrait` para que sejá possível que crie permissões e grupos para os usuários:

```php
<?php namespace App;

use Artesaos\Defender\HasDefenderTrait;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class User extends Model implements AuthenticatableContract, CanResetPasswordContract {

	use Authenticatable, CanResetPassword, HasDefenderTrait;
...
```
### Criando Grupos e Permissões

Para criar os grupos e as permissões para a sua aplicação, basta utilizar a API do defender. Você pode realizar esse processo em um seeder ou diretamente no `php artisan tinker` por exemplo.

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


### Usando o Middleware

Para proteger suas rotas, você pode utilizar os middlewares padrões do Defender.

> O Defender depende do Auth padrão do Laravel, portanto declare o middleware `auth` antes do middleware do Defender que você deseja usar.

#### needsPermissionMiddleware

```php
Route::get('foo', ['middleware' => ['auth', 'needsPermission'], 'can' => 'user.create', function()
{
	return 'Sim eu posso!';
}]);
```

Você também pode passar um array de permissões a serem checadas.

```php
Route::get('foo', ['middleware' => ['auth', 'needsPermission'], 'can' => ['user.index', 'user.create'], function()
{
	return 'Sim eu posso!';
}]);
```

Quando você passa um array de permissões, a rota é executada apenas se o usuário possui todas as permissões. Caso você queira que a rota execute quando o usuário tem pelo menos uma das permissões, basta adicionar `'any' => true`.

```php
Route::get('foo', ['middleware' => ['auth', 'needsPermission'], 'can' => ['user.index', 'user.create'], 'any' => true, function()
{
	return 'Sim eu posso!';
}]);
```

----------

#### needsRoleMiddleware

Funciona de maneira semelhante ao middleware anterior, porém apenas os grupos são verificados, ou seja, não leva em consideração as permissões.

```php
Route::get('foo', ['middleware' => ['auth', 'needsRole'], 'is' => 'admin', function()
{
	return 'Sim eu sou!';
}]);
```

Você também pode passar um array de permissões a serem checadas.

```php
Route::get('foo', ['middleware' => ['auth', 'needsRole'], 'can' => ['admin', 'member'], function()
{
	return 'Sim eu sou!';
}]);
```

Quando você passa um array de permissões, a rota é executada apenas se o usuário possui todas as permissões. Caso você queira que a rota execute quando o usuário tem pelo menos uma das permissões, basta adicionar `'any' => true`.

```php
Route::get('foo', ['middleware' => ['auth', 'needsRole'], 'is' => ['admin', 'member'], 'any' => true, function()
{
	return 'Sim eu sou!';
}]);
```

----------

### Usando nas Views

Extensões do Blade para facilitar o uso do defender.

#### @can

```
@can('user.index')
    aqui mostra algo relacionado a essa permissão 
@endcan
```

```
@can('user.index')
    aqui mostra algo relacionado ao usuário a essa permissão
@else
    aqui mostra as informações pra quem não tem a permissão user.index
@endcan
```

#### @is

```
@is('admin')
    Mostra informações para o usuário logado e que esteja no grupo admin
@endis
```

```
@is('admin')
    Mostra informações para o usuário logado e que esteja no grupo admin
@else 
    Aqui mostra um bloqueio ou qualquer coisa não relacionada ao grupo admin
@endis
```

----------

### Usando a Facade

Com a facade do defender você pode acessar a API e utilizá-la em qualquer parte de sua aplicação.

----------

##### `Defender::can($permission)`:

Verifica se o usuário logado possui a permissão `$permission`.

----------

##### `Defender::canWithRolePermissions($permission)`:

Verifica se o usuário logado possui a permissão `$permission` utilizando apenas os grupos.

----------

##### `Defender::hasRole($roleName)`:

Verifica se o usuário logado pertence ao grupo `$roleName`.

----------

##### `Defender::roleExists($roleName)`:

Verifica se o grupo `$roleName` existe no banco de dados.

----------

##### `Defender::permissionExists($permissionName)`:

Verifica se a permissão `$permissionName` existe no banco de dados.

----------

##### `Defender::findRole($roleName)`:

Busca no banco de dados o grupo de nome `$roleName`.

----------

##### `Defender::findRoleById($roleId)`:

Busca no banco de dados o grupo de ID `roleId`.

----------

##### `Defender::findPermission($permissionName)`:

Busca no banco de dados a permissão de nome `$permissionName`.

----------

##### `Defender::findPermissionById($permissionId)`:

Busca no banco de dados a permissão de ID `$permissionId`.

----------

##### `Defender::createRole($roleName)`:

Cria um novo grupo no banco de dados

----------

##### `Defender::createPermission($permissionName)`:

Cria uma nova permissão no banco de dados.

----------

### Usando a trait

Para adicionar as funcionalidades do Defender, é necessário adicionar trait `HasDefenderTrait` no seu modelo de usuário (normalmente o `App\User`).

```php
<?php namespace App;

// Declaração dos outros namespaces omitida
use Artesaos\Defender\HasDefenderTrait;

class User extends Model implements AuthenticatableContract, CanResetPasswordContract {

	use Authenticatable, CanResetPassword, HasDefenderTrait;

    // Restante da classe
}
```

Esta trait, além de configurar os relacionamentos, adicionará os seguintes métodos no seu object `App\User`:

#####`public function can($permission)`:

Este método verifica se o usuário logado no sistema possui a permissão `$permission`  

No Defender, existem 2 tipos de permissões: `Permissões de Usuário` e `Permissões de Grupo`. Por padrão as permissões que o usuário herda, são permissões dos grupos que ele pertence. Porém, sempre que uma permissão de usuário for definida, ela terá precedência sobre a permissão de grupo.

```php
public function foo(Authenticable $user)
{
    if ($user->can('user.create');
}
```

----------

##### `public function canWithRolePermissions($permission)`:

Este método funciona praticamente da mesma forma que o método anterior, a única diferença é que as permissões de usuário não são consideradas, ou seja, apenas as permissões dos grupos que o usuário pertence são usadas na hora de verificar a permissão.

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


##### `public function detachRole($role)`:

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

Semelhante ao `attachRole()`, porém apenas os grupos presentes no array `$roles` estarão presentes no relacionamento após a execução deste método. `$roles` é um array de `ids` dos grupos desejados.

```php
public function foo(Authenticable $user)
{
    $roles = [1, 2, 3]; // Usando array de ids
    
    $user->syncRoles($roles); 
}
```

----------

##### `public function attachPermission($permission, array $options = array())`:

Vincula o usuário a permissão `$permission`. A variável `$permission` é uma instância da classe `Artesaos\Defender\Permission`.

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

Semelhante ao método `syncRoles`. Apenas as permissões presentes no array `$permissions` farão parte do relacionamento após a execução deste método.

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

Remove todas as permissões do usuário.

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

----------


### Permissões Temporárias

Um dos recursos mais interessantes do Defender é possibilidade de atribuir permissões temporárias a um grupo ou um usuário em questão.

#### Exemplos

> *O usuário X é grupo 'admins', porém eu desejo remover temporariamente o poder dele de criar novos usuários*

Neste caso precisamos atribuir um permissão de usuário com o valor `false`, explicitamente proibindo o usuário de executar aquela ação. É necessário adicionar esta permissão com o valor `false`, já que por padrão as permissões de usuário herdam os valores das permissões de seus grupos. Ao atribuir uma permissão de usuário, esta sempre terá precedência.

No exemplo abaixo retiramos por 7 dias a permissão criar usuários (neste exemplo `user.create`) do admin em questão.

```php
public function foo()
{
    $userX = App\User::find(3); // considere '3' a ID do usuário 'X'
    $permission = Defender::findPermission('user.create');

	
	$userX->attachPermission($permission, [
		'value' => false, // false significa que ele não terá essa permissão,
		'expires' => \Carbon\Carbon::now()->addDays(7) // Daqui a quanto tempo essa permissão irá expirar
	]);

}
```

Após passados 7 dias, o usuário em questão terá a permissão restabelecida.

----------

> *Permitir que um usuário realize determinada ação temporariamente.*

Para permitir temporariamente que um usuário realize determinada ação, basta informar o valor do campo `expires`. O campo `value` é considerado `true` por padrão.

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
