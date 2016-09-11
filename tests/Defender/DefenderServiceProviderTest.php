<?php

namespace Artesaos\Defender\Testing;

use Illuminate\Support\Facades\Blade;

/**
 * Class DefenderServiceProviderTest.
 */
class DefenderServiceProviderTest extends AbstractTestCase
{
    /**
     * Array of service providers.
     * @var array
     */
    protected $providers = [
        'Artesaos\Defender\Providers\DefenderServiceProvider',
    ];

    /**
     * TestCases that should not register the service provider.
     * @var array
     */
    protected $skipProvidersFor = [
        'testShouldNotCompileDefenderTemplateHelpers',
        'testShouldNotLoadHelpers',
    ];

    public function testModelBindings()
    {
        $this->assertInstanceOf('Artesaos\Defender\Role', $this->app->make('Artesaos\Defender\Contracts\Role'));

        $this->assertInstanceOf('Artesaos\Defender\Permission', $this->app->make('Artesaos\Defender\Contracts\Permission'));
    }

    /**
     * Verify if all services are in service container.
     */
    public function testContainerShouldBeProvided()
    {
        $contracts = [
            [
                'interface' => 'Artesaos\Defender\Contracts\Defender',
                'implementation' => 'Artesaos\Defender\Defender',
                'alias' => 'defender',
            ],
            [
                'interface' => 'Artesaos\Defender\Contracts\Javascript',
                'implementation' => 'Artesaos\Defender\Javascript',
                'alias' => 'defender.javascript',
            ],
            [
                'interface' => 'Artesaos\Defender\Contracts\Repositories\PermissionRepository',
                'implementation' => 'Artesaos\Defender\Repositories\Eloquent\EloquentPermissionRepository',
                'alias' => 'defender.permission',
            ],
            [
                'interface' => 'Artesaos\Defender\Contracts\Repositories\RoleRepository',
                'implementation' => 'Artesaos\Defender\Repositories\Eloquent\EloquentRoleRepository',
                'alias' => 'defender.role',
            ],
        ];

        foreach ($contracts as $contract) {
            $this->assertInstanceOf($contract['interface'], $this->app[$contract['interface']]);
            $this->assertInstanceOf($contract['interface'], $this->app[$contract['implementation']]);
            $this->assertInstanceOf($contract['interface'], $this->app[$contract['alias']]);
            $this->assertInstanceOf($contract['implementation'], $this->app[$contract['alias']]);
        }
    }

    /**
     * Verify if blade is rendering defender directives.
     */
    public function testShouldCompileDefenderTemplateHelpers()
    {
        $view = $this->stubsPath('views/defender.blade.txt');
        $expected = $this->stubsPath('views/defender.blade.output.txt');

        $compiled = Blade::compileString(file_get_contents($view));

        $this->assertNotEmpty($compiled);

        $this->assertNotContains('@shield', $compiled);
        $this->assertNotContains('@is', $compiled);
        $this->assertNotContains('@endshield', $compiled);
        $this->assertNotContains('@endis', $compiled);

        $this->assertStringEqualsFile($expected, $compiled);
    }

    /**
     * If configuration is disabled, template helpers will not be available.
     * Note: The service provider should not be register before that test.
     */
    public function testShouldNotCompileDefenderTemplateHelpers()
    {
        $this->app['config']->set('defender.template_helpers', false);

        $this->app->register('Artesaos\Defender\Providers\DefenderServiceProvider');

        $view = $this->stubsPath('views/defender.blade.txt');
        $expected = $this->stubsPath('views/defender.blade.output.txt');

        $compiled = Blade::compileString(file_get_contents($view));

        $this->assertNotEmpty($compiled);

        $this->assertContains('@shield', $compiled);
        $this->assertContains('@is', $compiled);
        $this->assertContains('@endshield', $compiled);
        $this->assertContains('@endis', $compiled);

        $this->assertStringNotEqualsFile($expected, $compiled);
    }

    /**
     * Verify if the Defender function helpers are loaded.
     */
    public function testShouldLoadHelpers()
    {
        $this->assertTrue(function_exists('defender'), 'Helper \'defender()\' not loaded.');
        $this->assertTrue(function_exists('hasPermission'), 'Helper \'hasPermission()\'  not loaded.');
        $this->assertTrue(function_exists('roles'), 'Helper \'roles()\'  not loaded.');
    }

    /**
     * Verify if the Defender function helpers are loaded.
     * Note: The service provider should not be register before that test.
     * Note:That test needs to be runned in isolation. Because it depends of helpers.php
     * (file with functions which are always loaded).
     */
    public function testShouldNotLoadHelpers()
    {
        $this->assertFalse(isset($this->app['defender']));

        $this->app['config']->set('defender.helpers', false);

        $this->app->register('Artesaos\Defender\Providers\DefenderServiceProvider');

        if ($this->isInIsolation()) {
            $this->assertFalse(function_exists('defender'), 'Helper \'defender()\' loaded.');
            $this->assertFalse(function_exists('hasPermission'), 'Helper \'hasPermission()\'  loaded.');
            $this->assertFalse(function_exists('roles'), 'Helper \'roles()\'  loaded.');
        }
    }

    /**
     * Publishes the configuration and migrations.
     */
    public function testShouldPublishConfigAndMigrations()
    {
        $this->artisan('vendor:publish');

        $resourcesPath = __DIR__.'/../../src/resources';

        $migrations = [
            $resourcesPath.'/migrations/2015_02_23_161101_create_defender_roles_table.php',
            $resourcesPath.'/migrations/2015_02_23_161102_create_defender_permissions_table.php',
            $resourcesPath.'/migrations/2015_02_23_161103_create_defender_role_user_table.php',
            $resourcesPath.'/migrations/2015_02_23_161104_create_defender_permission_user_table.php',
            $resourcesPath.'/migrations/2015_02_23_161105_create_defender_permission_role_table.php',
        ];

        /*
         * Being sure the number of migrations described is the total expected.
         */
        $this->assertEquals(
            count(glob($resourcesPath.'/migrations/*.php')),
            count(array_unique($migrations))
        );

        $config = $resourcesPath.'/config/defender.php';

        foreach ($migrations as $migration) {
            $this->assertFileExists($migration);

            $this->assertFileExists(base_path('database/migrations/'.basename($migration)));
        }

        $this->assertFileExists(config_path(basename($config)));
    }
}
