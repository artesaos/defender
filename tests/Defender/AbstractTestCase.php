<?php

namespace Artesaos\Defender;

use Orchestra\Testbench\TestCase;

/**
 * Class AbstractTestCase.
 */
abstract class AbstractTestCase extends TestCase
{
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
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);
    }
}
