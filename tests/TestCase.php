<?php

declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: bardo
 * Date: 2020-09-13
 * Time: 13:52.
 */

namespace Bardoqi\Sight\Tests;

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

/**
 * Class BaseTestCase.
 */
class TestCase extends BaseTestCase
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
     * Creates the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        $path = dirname(dirname(dirname(dirname(__DIR__))));
        $app = require $path.'/bootstrap/app.php';

        $app->make(Kernel::class)->bootstrap();

        return $app;
    }
}
