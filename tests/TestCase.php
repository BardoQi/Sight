<?php

declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: bardo
 * Date: 2020-09-13
 * Time: 13:52.
 */

namespace Bardoqi\Sight\Tests;

use Orchestra\Testbench\TestCase as Orchestra;

/**
 * Class BaseTestCase.
 */
class TestCase extends Orchestra
{
    /**
     * The base URL to use while testing the application.
     *
     * @var string
     */
    protected $baseUrl = 'http://localhost';

    public function setUp(): void
    {
//        touch('./tests.sqlite');

        parent::setUp();
    }

    public function tearDown(): void
    {
//        unlink('./tests.sqlite');
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
        // Setup default database to use sqlite :memory:
        $app['config']->set('database.default', 'testbench');
        $app['config']->set('database.connections.testbench', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);
    }
}
