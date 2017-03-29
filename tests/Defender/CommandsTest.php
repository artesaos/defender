<?php

/**
 * Created by PhpStorm.
 * User: vluzrmos
 * Date: 12/07/15
 * Time: 01:01.
 */

namespace Artesaos\Defender\Testing;

use Artesaos\Defender\Role;
use Artesaos\Defender\Contracts\Repositories\RoleRepository;

class CommandsTest extends AbstractTestCase
{
    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        parent::setUp();

        $this->migrate([
            $this->stubsPath('database/migrations'),
            $this->resourcePath('migrations'),
        ]);

        $this->seed([
            'UserTableSeeder',
            'RoleTableSeeder',
        ]);
    }

    /**
     * {@inheritdoc}
     * @return array
     */
    public function getPackageProviders($app)
    {
        return [
            'Artesaos\Defender\Providers\DefenderServiceProvider',
            'Orchestra\Database\ConsoleServiceProvider',
        ];
    }

    /**
     * Creating a Permission.
     */
    public function testCommandShouldMakeAPermission()
    {
        $this->artisan('defender:make:permission', ['name' => 'a.permission', 'readableName' => 'A permission.']);

        $this->assertDatabaseHas(
            config('defender.permission_table', 'permissions'),
            [
                'name' => 'a.permission',
                'readable_name' => 'A permission.',
            ]
        );
    }

    /**
     * Creating a permission to User.
     */
    public function testCommandShouldMakeAPermissionToUser()
    {
        $this->artisan('defender:make:permission', ['name' => 'user.index', 'readableName' => 'List Users', '--user' => 1]);

        $this->assertDatabaseHas(
            config('defender.permission_table', 'permissions'),
            [
                'name' => 'user.index',
                'readable_name' => 'List Users',
            ]
        );

        $user = User::find(1);

        $this->assertEquals('user.index', $user->permissions->where('name', 'user.index')->first()->name);
    }

    /**
     * Creating a permission to Role.
     */
    public function testCommandShouldMakeAPermissionToRole()
    {
        $this->artisan('defender:make:permission', ['name' => 'user.delete', 'readableName' => 'Remove Users', '--role' => 'admin']);
        $this->assertDatabaseHas(
            config('defender.permission_table', 'permissions'),
            [
                'name' => 'user.delete',
                'readable_name' => 'Remove Users',
            ]
        );

        /* @var RoleRepository $role */
        $rolesRepository = app('defender.role');

        /** @var Role $role */
        $role = $rolesRepository->findByName('admin');

        $this->assertEquals('user.delete', $role->permissions->where('name', 'user.delete')->first()->name);
    }

    /**
     * Creating a Role.
     */
    public function testCommandShouldMakeARole()
    {
        $this->artisan('defender:make:role', ['name' => 'a.role']);

        $this->assertDatabaseHas(
            config('defender.role_table', 'roles'),
            [
                'name' => 'a.role',
            ]
        );
    }

    /**
     * Creating a Role to User.
     */
    public function testCommandShouldMakeARoleToUser()
    {
        $this->artisan('defender:make:role', ['name' => 'user.role', '--user' => 1]);

        $this->assertDatabaseHas(
            config('defender.role_table', 'roles'),
            [
                'name' => 'user.role',
            ]
        );

        $user = User::find(1);

        $this->assertEquals('user.role', $user->roles->where('name', 'user.role')->first()->name);
    }
}
