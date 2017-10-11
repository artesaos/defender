# Defender

#### A documentação em pt_BR esta desatualizada, dê preferencia para a en_US.

[Readme on English](https://github.com/artesaos/defender/blob/master/README.md).

Defender é um package ACL para Laravel 5 que utiliza grupos e permissões.
Com Segurança e Usabilidade em mente, este projeto tem como objetivo prover o controle de acesso da sua aplicação.

> Estado Atual do Package

[![Build Status](https://travis-ci.org/artesaos/defender.svg?branch=develop)](https://travis-ci.org/artesaos/defender)
[![Code Climate](https://codeclimate.com/github/artesaos/defender/badges/gpa.svg)](https://codeclimate.com/github/artesaos/defender)
[![StyleCI](https://styleci.io/repos/31179862/shield)](https://styleci.io/repos/31179862)

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
Para que o package seja adicionado automaticamente ao seu arquivo `composer.json` execute o seguinte comando utilizando a versão do pacote correspondente:

| Versão Laravel       | Versão do Pacote |
|----------------------|------------------|
| >= 5.0.* && <= 5.3.* | 0.6.*            |
| ~5.4, ~5.5           | 0.7.*            |

```shell
composer require artesaos/defender `versao-pacote`
```

ou se preferir, adicione o seguinte trecho manualmente:

```json
{
	"require": {
		"artesaos/defender": "versao-pacote"
	}
}
```

### 2. Provider

> Se você está utilizando Laravel 5.5+ essa seção não é necessária pois o pacote suporta a função de auto-discovery.

Para usar o Defender em sua aplicação Laravel, é necessário registrar o package no seu arquivo `config/app.php`. Adicione o seguinte código no fim da seção `providers`

```php
// file START ommited
    'providers' => [
        // other providers ommited
        'Artesaos\Defender\Providers\DefenderServiceProvider',
    ],
// file END ommited
```

### 3. User Class

Na sua classe de usuário, adicione a trait `Artesaos\Defender\Traits\HasDefender` para disponibilizar a criação de grupos e permissões:

```php
<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Artesaos\Defender\Traits\HasDefender;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class User extends Model implements AuthenticatableContract, CanResetPasswordContract
{
    use Authenticatable, CanResetPassword, HasDefender;
```

Se você está utilizando o Laravel 5.2, há uma pequena diferença:

```php
<?php

namespace App;

use Artesaos\Defender\Traits\HasDefender;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasDefender;
...
```

#### 4 Publicando o arquivo de configuração e as migrations

Para publicar o arquivo de configuração padrão e as migrations que acompanham o package, execute o seguinte comando:

```shell
php artisan vendor:publish
```

Execute as migrations, para que sejam criadas as tabelas no banco de dados:

```shell
php artisan migrate
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

### 5. Facade (opcional)

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

### 6. Middlewares do Defender

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

#### 6.1 - Crie o seu próprio middleware

Caso os middlewares padrões do Defender não atendam as suas necessidades, você pode criar seu próprio middleware e utilizar a [API do Defender](#usando-a-facade) para realizar o controle de acesso.

## Usando o Defender

O Defender realiza apenas o controle de acesso em sua aplicação, ou seja, a tarefa de autenticação é realizada pelo `Auth` que faz parte do core do Laravel.

**Nota: Se você utilizar um model diferente para os usuários ou mudou o namespace, atualize a chave `user_model` no seu arquivo de configuração do `defender`**

### Criando Grupos e Permissões

O Defender lida apenas com o acesso à sua aplicação. A autenticação ainda é feita pelo Laravel Auth.

#### Através de comandos

Para criar grupos e permissões estão disponíveis os seguintes comandos:

```shell
php artisan defender:make:role admin  # creates the role admin
php artisan defender:make:role member --user=1 # creates the role admin and attaches this role to the user where id=1
php artisan defender:make:permission users.index "List all the users" # creates the permission
php artisan defender:make:permission users.create "Create user" --user=1 # creates the permission and attaches it to user where id=1
php artisan defender:make:permission users.destroy "Delete user" --role=admin # creates the permission and attaches it to the role admin
```

#### Através de um seeder ou o artisan tinker

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
Route::get('foo', ['middleware' => ['auth', 'needsPermission'], 'shield' => 'user.create', function()
{
	return 'Sim eu posso!';
}]);
```

Você também pode passar um array de permissões a serem checadas.

```php
Route::get('foo', ['middleware' => ['auth', 'needsPermission'], 'shield' => ['user.index', 'user.create'], function()
{
	return 'Sim eu posso!';
}]);
```

Quando você passa um array de permissões, a rota é executada apenas se o usuário possui todas as permissões. Caso você queira que a rota execute quando o usuário tem pelo menos uma das permissões, basta adicionar `'any' => true`.

```php
Route::get('foo', ['middleware' => ['auth', 'needsPermission'], 'shield' => ['user.index', 'user.create'], 'any' => true, function()
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
Route::get('foo', ['middleware' => ['auth', 'needsRole'], 'shield' => ['admin', 'member'], function()
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

#### @shield

```
@shield('user.index')
    aqui mostra algo relacionado a essa permissão
@endshield
```

```
@shield('user.index')
    aqui mostra algo relacionado ao usuário a essa permissão
@else
    aqui mostra as informações pra quem não tem a permissão user.index
@endshield
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

##### `Defender::hasPermission($permission)`:

Verifica se o usuário logado possui a permissão `$permission`.

----------

##### `Defender::roleHasPermission($permission)`:

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

Para adicionar as funcionalidades do Defender, é necessário adicionar trait `HasDefender` no seu modelo de usuário (normalmente o `App\User`).

```php
<?php namespace App;

// Declaração dos outros namespaces omitida
use Artesaos\Defender\HasDefender;

class User extends Model implements AuthenticatableContract, CanResetPasswordContract {

	use Authenticatable, CanResetPassword, HasDefender;

    // Restante da classe
}
```

Esta trait, além de configurar os relacionamentos, adicionará os seguintes métodos no seu object `App\User`:

#####`public function hasPermission($permission)`:

Este método verifica se o usuário logado no sistema possui a permissão `$permission`

No Defender, existem 2 tipos de permissões: `Permissões de Usuário` e `Permissões de Grupo`. Por padrão as permissões que o usuário herda, são permissões dos grupos que ele pertence. Porém, sempre que uma permissão de usuário for definida, ela terá precedência sobre a permissão de grupo.

```php
public function foo(Authenticable $user)
{
    if ($user->hasPermission('user.create');
}
```

----------

##### `public function roleHasPermission($permission)`:

Este método funciona praticamente da mesma forma que o método anterior, a única diferença é que as permissões de usuário não são consideradas, ou seja, apenas as permissões dos grupos que o usuário pertence são usadas na hora de verificar a permissão.

```php
public function foo(Authenticable $user)
{
    if ($user->roleHasPermission('user.create');
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
	    2 => ['value' => true],
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

## Usando modelos personalizados para Role e Permission

Para utilizar suas próprias classes para os modelos Role e Permission, primeiramente defina os valores para as chaves `role_model` e `permission_model` no arquivo de configuração `defender.php`.

A seguir dois exemplos de como devem ser implementados os modelos de Role e Permission para MongoDB usando o driver [jenssegers/laravel-mongodb](https://github.com/jenssegers/laravel-mongodb):

```php
    <?php
    
    // Role model
    
    namespace App;
    
    use Jenssegers\Mongodb\Eloquent\Model;
    use Artesaos\Defender\Traits\Models\Role;
    use Artesaos\Defender\Contracts\Role as RoleInterface;
    
    /**
     * Class Role.
     */
    class Role extends Model implements RoleInterface {
        use Role;
    }

```

```php
    <?php
    
    // Permission model
    
    namespace App;
    
    use Jenssegers\Mongodb\Eloquent\Model;
    use Artesaos\Defender\Traits\Models\Permission;
    use Artesaos\Defender\Contracts\Permission as PermissionInterface;
    
    /**
     * Class Permission.
     */
    class Permission extends Model implements PermissionInterface
    {
        use Permission;    
    }
```

Você deve utilizar os traits corretos e cada classe deve implementar o contrato de interface correspondente.
