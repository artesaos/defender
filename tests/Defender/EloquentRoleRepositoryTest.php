<?php

namespace Artesaos\Defender\Testing;

use Artesaos\Defender\Contracts\Repositories\RoleRepository;
use Artesaos\Defender\Role;

/**
 * Class RepositoriesTest.
 */
class EloquentRoleRepositoryTest extends AbstractTestCase
{
    /**
     * @inheritdoc
     */
    public function setUp()
    {
        parent::setUp();

        $this->migrate([
            $this->stubsPath('database/migrations'),
            $this->resourcePath('migrations'),
        ]);

        $this->seed('UserTableSeeder');
    }

    /**
     * Testing the criation of roles.
     */
    public function testShouldCreateRole()
    {
        $this->createRole('superuser');
    }

    /**
     * Testing attach role to a user.
     */
    public function testShouldAttachRoleToUserAdmin()
    {
        $role = $this->createRole('superuser');

        $user = User::where('name', 'admin')->first();

        $role->users()->attach($user);

        $this->seeInDatabase(
            config('defender.role_user_table', 'role_user'),
            [
                config('defender.role_key', 'role_id') => $role->id,
                'user_id' => $user->id,
            ]
        );

        $this->createRole('anotherCoolRole');

        $this->assertTrue($user->hasRoles('superuser'));

        $this->assertFalse($user->hasRoles('anyOtherNonExistingRole'));

        $this->assertFalse($user->hasRoles('anotherCoolRole'));
    }

    /**
     * Create a role and assert to see in database.
     * @param string $rolename
     * @return Role
     */
    public function createRole($rolename)
    {
        /** @var RoleRepository $repository */
        $repository = $this->app['defender.role'];

        $role = $repository->create($rolename);

        $this->seeInDatabase(config('defender.role_table', 'roles'), ['name' => $rolename]);

        return $role;
    }

    /**
     * @inheritdoc
     * @param \Illuminate\Foundation\Application $app
     * @return array
     */
    public function getPackageProviders($app)
    {
        return [
            'Artesaos\Defender\Providers\DefenderServiceProvider',
        ];
    }
}
