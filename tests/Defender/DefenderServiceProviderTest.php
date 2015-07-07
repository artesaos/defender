<?php

namespace Artesaos\Defender;

use Illuminate\Support\Facades\Blade;

/**
 * Class DefenderServiceProviderTest
 * @package Artesaos\Defender
 */
class DefenderServiceProviderTest extends AbstractTestCase
{
    /**
     * TestCases that should not register the service provider.
     * @var array
     */
    protected $dotNotRegisterForThatTestCases = [
        'testShouldNotCompileDefenderTemplateHelpers',
    ];

    /**
     * Verify if all services are in service container.
     */
    public function testContainerShouldBeProvided()
    {
        $contracts = [
            [
              "interface" => 'Artesaos\Defender\Contracts\Defender',
              "implementation" => 'Artesaos\Defender\Defender',
              "alias"=>'defender',
            ],
            [
                "interface" => 'Artesaos\Defender\Contracts\Javascript',
                "implementation" => 'Artesaos\Defender\Javascript',
                "alias"=>'defender.javascript',
            ],
            [
                "interface" => 'Artesaos\Defender\Contracts\Repositories\PermissionRepository',
                "implementation" => 'Artesaos\Defender\Repositories\Eloquent\EloquentPermissionRepository',
                "alias"=>'defender.permission',
            ],
            [
                "interface" => 'Artesaos\Defender\Contracts\Repositories\RoleRepository',
                "implementation" => 'Artesaos\Defender\Repositories\Eloquent\EloquentRoleRepository',
                "alias"=>'defender.role',
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
        $view = __DIR__.'/stubs/view_with_defender.blade.txt';
        $expected = __DIR__.'/stubs/view_with_defender.blade.output.txt';

        $compiled = Blade::compileString(file_get_contents($view));

        $this->assertNotEmpty($compiled);

        $this->assertNotContains('@can', $compiled);
        $this->assertNotContains('@is', $compiled);
        $this->assertNotContains('@endcan', $compiled);
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

        $view = __DIR__.'/stubs/view_with_defender.blade.txt';
        $expected = __DIR__.'/stubs/view_with_defender.blade.output.txt';

        $compiled = Blade::compileString(file_get_contents($view));

        $this->assertNotEmpty($compiled);

        $this->assertContains('@can', $compiled);
        $this->assertContains('@is', $compiled);
        $this->assertContains('@endcan', $compiled);
        $this->assertContains('@endis', $compiled);

        $this->assertStringNotEqualsFile($expected, $compiled);
    }

    /**
     * {@inheritdoc}
     */
    protected function getPackageProviders($app)
    {
        if (in_array($this->getName(), $this->dotNotRegisterForThatTestCases)) {
            return [];
        }

        return [
            'Artesaos\Defender\Providers\DefenderServiceProvider',
        ];
    }
}
