<?php

namespace Artesaos\Defender;

/**
 * Class DefenderTest
 * @package Artesaos\Defender
 */
class MigrationsTest extends AbstractTestCase
{

    /**
     * Should migrate.
     */
    public function setUp()
    {
        parent::setUp();

        /* Migrate stubs tables (users) */
        $code = $this->artisan(
            'migrate',
            ['--realpath' => $this->stubsPath('migrations')]
        );

        $this->assertEquals(0, $code);

        /* Migrate defende tables*/
        $code = $this->artisan(
            'migrate',
            ['--realpath' => $this->resourcePath('migrations')]
        );

        $this->assertEquals(0, $code);
    }

    /**
     *
     */
    public function testShouldSeeTablesOnDatabase()
    {
        /** @var \Illuminate\Database\Schema\Builder $schema */
        $schema = $this->app['db']->connection()->getSchemaBuilder();

        $tables = [
            config('auth.table', 'users'),
            config('defender.role_table', 'roles'),
            config('defender.permission_table', 'permissions'),
            config('defender.permission_user_table', 'permission_user'),
            config('defender.permission_role_table', 'permission_role'),
            config('defender.role_user_table', 'role_user'),
        ];

        foreach ($tables as $table) {
            $this->assertTrue($schema->hasTable($table), sprintf(
                'Table \'%s\' not found in database',
                $table
            ));
        }
    }

    /**
     * Package service provider
     * @return array
     */
    public function getPackageProviders()
    {
        return [
            'Artesaos\Defender\Providers\DefenderServiceProvider',
        ];
    }
}
