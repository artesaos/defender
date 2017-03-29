<?php

namespace Artesaos\Defender\Testing;

use Orchestra\Testbench\TestCase;

/**
 * Class AbstractTestCase.
 */
abstract class AbstractTestCase extends TestCase
{
    /**
     * Array of service providers should be loaded before tests.
     * @var array
     */
    protected $providers = [];

    /**
     * Array of test case which should not load the service providers.
     * @var array
     */
    protected $skipProvidersFor = [];

    /**
     * Performs migrations.
     * @param string|array $path string or array of paths to find migrations.
     */
    public function migrate($path = null)
    {
        $paths = is_array($path) ? $path : [$path];

        foreach ($paths as $path) {
            $this->loadMigrationsFrom($path);
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
        return __DIR__.'/../../src'.$this->parseSubPath($path);
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
        return $this->srcPath('resources').$this->parseSubPath($path);
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
        return __DIR__.'/../stubs'.$this->parseSubPath($path);
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

        $app['config']->set('defender.user_model', 'Artesaos\Defender\Testing\User');
        $app['config']->set('defender.role_model', 'Artesaos\Defender\Role');
        $app['config']->set('defender.permission_model', 'Artesaos\Defender\Permission');
        $app['config']->set('auth.model', $app['config']->get('defender.user_model'));
    }

    /**
     * Get package providers.
     * @param \Illuminate\Foundation\Application $app
     * @return array
     */
    protected function getPackageProviders($app)
    {
        if (in_array($this->getName(), $this->skipProvidersFor)) {
            return [];
        }

        return $this->providers;
    }

    /**
     * Trim slashes of path and return prefixed by DIRECTORY_SEPARATOR.
     * @param string $path
     * @return string
     */
    protected function parseSubPath($path)
    {
        return $path ? DIRECTORY_SEPARATOR.trim($path, DIRECTORY_SEPARATOR) : '';
    }
}
