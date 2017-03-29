<?php

namespace Artesaos\Defender\Testing;

use Illuminate\View\View;

/**
 * Class JavascriptTest.
 */
class JavascriptTest extends AbstractTestCase
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
     * New defender instance.
     * @var Defender
     */
    protected $defender;

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

        $this->app->singleton('Illuminate\Contracts\Debug\ExceptionHandler', 'Orchestra\Testbench\Exceptions\Handler');

        $this->defender = new Defender($this->app, $this->app['defender.role'], $this->app['defender.permission']);
        $this->defender->setUser(User::first());
    }

    /**
     * Asserting rended blade view.
     */
    public function testShouldRenderJavascript()
    {
        $javascript = $this->defender->javascript();

        /* @var View $script */
        $view = $javascript->render();

        $viewContent = $view->render();

        $data = $view->getData();

        $this->assertArrayHasKey('roles', $data);
        $this->assertArrayHasKey('permissions', $data);

        $this->assertTrue(is_string($data['roles']));
        $this->assertTrue(is_string($data['permissions']));

        /*
         * Not empty string, it could be a empty json array string
         */
        $this->assertNotEmpty($data['roles']);
        $this->assertNotEmpty($data['permissions']);

        $this->assertJson($data['roles']);
        $this->assertJson($data['permissions']);

        $this->assertStringMatchesFormatFile(
            $this->stubsPath('views/javascript.blade.output.txt'),
            $viewContent
        );
    }

    /**
     * Asserting created permission and rendered properly.
     */
    public function testShouldRenderUserPermissions()
    {
        $user = $this->defender->getUser();

        $permission = $this->defender->createPermission('js.permission');

        $user->attachPermission($permission);

        /** @var View $view */
        $view = $this->defender->javascript()->render();

        $data = $view->getData();

        $this->assertArrayHasKey('permissions', $data);

        $this->assertFalse('[]' == $data['permissions']);

        $this->assertStringMatchesFormatFile(
            $this->stubsPath('views/javascript-with-permission.blade.output.txt'),
            $view->render()
        );
    }

    /**
     * Asserting created role and rendered properly.
     */
    public function testShouldRenderUserRole()
    {
        $user = $this->defender->getUser();

        $role = $this->defender->createRole('js.role');

        $user->attachRole($role);

        /** @var View $view */
        $view = $this->defender->javascript()->render();

        $data = $view->getData();

        $this->assertArrayHasKey('roles', $data);

        $this->assertFalse('[]' == $data['roles']);

        $this->assertStringMatchesFormatFile(
            $this->stubsPath('views/javascript-with-role.blade.output.txt'),
            $view->render()
        );
    }
}
