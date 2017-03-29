<?php

namespace Artesaos\Defender\Testing;

use Artesaos\Defender\Role;
use Artesaos\Defender\Permission;
use Artesaos\Defender\Repositories\Eloquent\EloquentPermissionRepository;

/**
 * Class EloquentPermissionRepositoryTest.
 */
class EloquentPermissionRepositoryTest extends AbstractTestCase
{
    /**
     * Array of service providers.
     * @var array
     */
    protected $providers = [
        'Artesaos\Defender\Providers\DefenderServiceProvider',
        'Orchestra\Database\ConsoleServiceProvider',
    ];

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
     * Asserting if the User model has traits.
     */
    public function testUserShouldHasPermissionsTrait()
    {
        $this->assertUsingTrait(
            'Artesaos\Defender\Traits\HasDefender',
            'Artesaos\Defender\Testing\User'
        );

        $this->assertUsingTrait(
            'Artesaos\Defender\Traits\Permissions\InteractsWithPermissions',
            'Artesaos\Defender\Testing\User'
        );

        $this->assertUsingTrait(
            'Artesaos\Defender\Traits\Users\HasPermissions',
            'Artesaos\Defender\Testing\User'
        );
    }

    /**
     * Testing the criation of permissions.
     */
    public function testShouldCreatePermission()
    {
        $this->createPermission('users.index');

        $this->createPermission('users.create', 'Create Users');

        /** @var Permission $permission */
        /** @var User $user */
        list($permission, $user) = $this->createAndAttachPermission(
            'users.delete',
            ['name' => 'admin'],
            'Delete users'
        );

        $this->assertTrue($permission->users()->get()->contains($user->id));

        $this->assertTrue($user->existPermission('users.delete'));

        $this->assertInstanceOf(
            'Artesaos\Defender\Pivots\PermissionUserPivot',
            $user->permissions->first()->pivot
        );
    }

    /**
     *  Testing if permission is attached to role.
     */
    public function testShouldAttachPermissionToRole()
    {
        $permission = $this->createPermission('users.index');

        $role = Role::where(['name' => 'admin'])->first();

        $permission->roles()->attach($role);

        $this->seePermissionAttachedToRoleInDatabase($permission, $role);

        $this->assertTrue($permission->roles()->get()->contains($role->id));

        $this->assertInstanceOf(
            'Artesaos\Defender\Pivots\PermissionRolePivot',
            $role->permissions->first()->pivot
        );
    }

    /**
     * Create a permission and assert to see in database.
     * @param string $name
     * @param string $readableName
     * @return Permission
     */
    protected function createPermission($name, $readableName = null)
    {
        /** @var EloquentPermissionRepository $repository */
        $repository = $this->app['defender.permission'];

        $permission = $repository->create($name, $readableName);

        $where['name'] = $name;

        if ($readableName) {
            $where['readable_name'] = $readableName;
        }

        $this->assertDatabaseHas(
            config('defender.permission_table', 'permissions'),
            $where
        );

        return $permission;
    }

    /**
     * Create and Attach a Permission to User.
     * @param string $permission
     * @param User|array $user User or array of where clausules.
     * @param string $readableName Permission readable name.
     * @return array Array containing created $permission and $user.
     */
    protected function createAndAttachPermission($permission, $user, $readableName = null)
    {
        $permission = $this->createPermission($permission, $readableName);

        if (! ($user instanceof User)) {
            $user = User::where($user)->first();
        }

        $permission->users()->attach($user);

        $this->seePermissionAttachedToUserInDatabase($permission, $user);

        return [$permission, $user];
    }

    /**
     * Create and Attach a Permission to User.
     * @param string $permission
     * @param Role $role Role or array of where clausules.
     * @param string $readableName Permission readable name.
     * @return array Array containing created $permission and $role.
     */
    protected function createAndAttachPermissionToRole($permission, $role, $readableName = null)
    {
        $permission = $this->createPermission($permission, $readableName);

        if (! ($role instanceof Role)) {
            $role = User::where($role)->first();
        }

        $permission->roles()->attach($role);

        $this->seePermissionAttachedToRoleInDatabase($permission, $role);

        return [$permission, $role];
    }

    /**
     * Assert to see in Database a Permission attached to User.
     * @param Permission $permission
     * @param User $user
     */
    protected function seePermissionAttachedToUserInDatabase(Permission $permission, User $user)
    {
        $this->assertDatabaseHas(
            config('defender.permission_user_table', 'permission_user'),
            [
                config('defender.permission_key', 'permission_id') => $permission->id,
                'user_id' => $user->id,
            ]
        );
    }

    /**
     * Assert to not see in Database a Permission attached to User.
     * @param Permission $permission
     * @param User $user
     */
    protected function notSeePermissionAttachedToUserInDatabase(Permission $permission, User $user)
    {
        $this->assertDatabaseMissing(
            config('defender.permission_user_table', 'permission_user'),
            [
                config('defender.permission_key', 'permission_id') => $permission->id,
                'user_id' => $user->id,
            ]
        );
    }

    /**
     * Assert to see in Database a Permission attached to Role.
     * @param Permission $permission
     * @param Role $role
     */
    protected function seePermissionAttachedToRoleInDatabase(Permission $permission, Role $role)
    {
        $this->assertDatabaseHas(
            config('defender.permission_role_table', 'permission_role'),
            [
                config('defender.permission_key', 'permission_id') => $permission->id,
                config('defender.role_key', 'role_id') => $role->id,
            ]
        );
    }

    /**
     * Assert to not see in Database a Permission attached to Role.
     * @param Permission $permission
     * @param Role $role
     */
    protected function notSeePermissionAttachedToRoleInDatabase(Permission $permission, Role $role)
    {
        $this->assertDatabaseMissing(
            config('defender.permission_role_table', 'permission_role'),
            [
                config('defender.permission_key', 'permission_id') => $permission->id,
                config('defender.role_key', 'role_id') => $role->id,
            ]
        );
    }
}
