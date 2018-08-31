<?php

namespace Artesaos\Defender\Testing;

use Artesaos\Defender\Permission;

/**
 * Class HasDefenderTest.
 */
class HasDefenderTest extends AbstractTestCase
{
    /**
     * Array of service providers.
     * @var array
     */
    protected $providers = [
        'Artesaos\Defender\Providers\DefenderServiceProvider',
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
            'PermissionTableSeeder',
        ]);
    }

    public function testUserCanFindPermissionWithAsterisk()
    {
        $user = User::find(1)->first();

        $permission_create = Permission::find(1);
        $permission_delete = Permission::find(2);

        $user->attachPermission($permission_create);
        $user->attachPermission($permission_delete);

        $this->assertTrue($user->hasPermission('user.*'));
    }

    public function testUserCanNotFindPermissionWithAsterisk()
    {
        $user = User::find(1)->first();

        $permission_create = Permission::find(1);
        $permission_delete = Permission::find(2);

        $user->attachPermission($permission_create);
        $user->attachPermission($permission_delete);

        $this->assertFalse($user->hasPermission('admin.*'));
    }
}
