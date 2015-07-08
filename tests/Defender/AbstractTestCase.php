<?php

namespace Artesaos\Defender\Testing;

use Orchestra\Testbench\TestCase;

/**
 * Class AbstractTestCase.
 */
abstract class AbstractTestCase extends TestCase
{
    /**
     * Performs migrations.
     * @param string|array $path string or array of paths to find migrations.
     */
    public function migrate($path = null)
    {
        $paths = is_array($path) ? $path : [$path];

        foreach ($paths as $path) {
            $code = $this->artisan(
                'migrate',
                ['--realpath' => $path]
            );

            $this->assertEquals(
                0,
                $code,
                sprintf(
                    'Something went wrong when migrating %s.',
                    str_replace(realpath($this->srcPath('..')), '', realpath($path))
                )
            );
        }
    }

    /**
     * Seed database.
     * @param string|array $seeder String or Array of classes to seed.
     */
    public function seed($seeder = 'DatabaseSeeder')
    {
        $seeders = is_array($seeder) ? $seeder : [$seeder];

        foreach ($seeders as $seeder) {
            $code = $this->artisan(
                'db:seed',
                ['--class' => str_contains($seeder, '\\') ? $seeder : 'Artesaos\Defender\Testing\\'.$seeder]
            );

            $this->assertEquals(0, $code, sprintf('Something went wrong when seeding %s.', $seeder));
        }
    }

    /**
     * Assert if the instance or classname uses a trait.
     * @param string $trait    Name of the trait (namespaced)
     * @param mixed  $instance Instance or name of the class
     */
    public function assertUsingTrait($trait, $instance)
    {
        $this->assertTrue(
            in_array($trait, class_uses_recursive($instance)),
            sprintf(
                'Fail to assert the class %s uses trait %s.',
                is_string($instance) ? $instance : get_class($instance),
                $trait
            )
        );
    }

    /**
     * Get source package path.
     *
     * @param string $path
     *
     * @return string
     */
    public function srcPath($path = null)
    {
        return __DIR__.'/../../src'.($path ? '/'.trim($path, '/') : '');
    }

    /**
     * Get the resources path.
     *
     * @param string $path
     *
     * @return string
     */
    public function resourcePath($path = null)
    {
        return $this->srcPath('resources').($path ? '/'.trim($path, '/') : '');
    }

    /**
     * Stubs path.
     *
     * @param string $path
     *
     * @return string
     */
    public function stubsPath($path = null)
    {
        return __DIR__.'/../stubs'.($path ? '/'.trim($path, '/') : '');
    }

    /**
     * Define environment setup.
     *
     * @param \Illuminate\Foundation\Application $app
     *
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'testing');

        $app['config']->set('database.connections.testing', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);

        $app['config']->set('auth.model', 'Artesaos\Defender\Testing\User');
    }
}
