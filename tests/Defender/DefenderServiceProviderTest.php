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
    public function testDefenderBladeDirectives()
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
     * {@inheritdoc}
     */
    protected function getPackageProviders($app)
    {
        return [
            'Artesaos\Defender\Providers\DefenderServiceProvider',
        ];
    }
}
